<?php

declare(strict_types=1);

namespace RD\Autotranslate\Controller;

use Doctrine\DBAL\ParameterType;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class AutotranslateController extends ActionController
{
    protected const NON_TRANSLATABLE_FIELDS = [
        'uid', 'pid', 'tstamp', 'crdate', 'hidden', 'deleted', 'sorting',
        'sys_language_uid', 'l18n_parent', 'l10n_parent', 'l10n_source', 'l10n_diffsource',
        'CType', 'list_type', 'layout', 'colPos', 'imageorient', 'header_layout', 'frame_class',
        'parentid', 'parenttable', 'slug'
    ];

    protected const FAL_COUNT_FIELDS = ['fal_media', 'fal_related_files', 'image', 'media', 'assets'];

    public function indexAction(): ResponseInterface
    {
        $pageUid = (int)($this->request->getQueryParams()['id'] ?? 0);
        $doTranslate = (int)($this->request->getQueryParams()['doTranslate'] ?? 0);
        $finish = (int)($this->request->getQueryParams()['finish'] ?? 0);

        if ($pageUid <= 0) return $this->htmlResponse();

        if ($doTranslate === 1) {
            $this->runTranslateProcess($pageUid);
            return $this->redirect('index', null, null, ['id' => $pageUid, 'finish' => 1]);
        }

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId($pageUid);
        
        // --- Fluid માટે Content Elements Fetch કરવા (જે ટેમ્પલેટમાં વપરાય છે) ---
        $qb = $connectionPool->getQueryBuilderForTable('tt_content');
        $qb->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        
        $contentElements = $qb->select('uid', 'header', 'CType', 'colPos')
            ->from('tt_content')
            ->where(
                $qb->expr()->eq('pid', $qb->createNamedParameter($pageUid, ParameterType::INTEGER)),
                $qb->expr()->eq('sys_language_uid', $qb->createNamedParameter(0, ParameterType::INTEGER))
            )
            ->executeQuery()->fetchAllAssociative();

        // --- View Assign (બધા variables જે ટેમ્પલેટમાં છે) ---
        $this->view->assignMultiple([
            'pageUid' => $pageUid,
            'languages' => $site->getLanguages(),
            'contentElements' => $contentElements,
            'contentCount' => count($contentElements), // {contentCount} માટે
            'detectedTables' => $this->getDetectedTables($pageUid), // {detectedTables} માટે
            'translationDone' => ($finish === 1)
        ]);

        return $this->htmlResponse();
    }

    protected function runTranslateProcess(int $pageUid): void
    {
        $site = GeneralUtility::makeInstance(SiteFinder::class)->getSiteByPageId($pageUid);
        $languages = $site->getLanguages();
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $allTableNames = $connectionPool->getConnectionForTable('pages')->createSchemaManager()->listTableNames();

        foreach ($languages as $language) {
            $targetLangId = $language->getLanguageId();
            if ($targetLangId === 0) continue;
            $iso = substr((string)$language->getLocale(), 0, 2);

            foreach ($allTableNames as $tableName) {
                if ($this->isSystemTable($tableName)) continue;

                $queryBuilder = $connectionPool->getQueryBuilderForTable($tableName);
                $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
                
                $sm = $connectionPool->getConnectionForTable($tableName)->createSchemaManager();
                $columns = $sm->listTableColumns($tableName);
                if (!isset($columns['pid'], $columns['sys_language_uid'])) continue;

                $sourceRecords = $queryBuilder->select('*')->from($tableName)
                    ->where(
                        $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pageUid, ParameterType::INTEGER)),
                        $queryBuilder->expr()->eq('sys_language_uid', $queryBuilder->createNamedParameter(0, ParameterType::INTEGER))
                    )->executeQuery()->fetchAllAssociative();

                foreach ($sourceRecords as $record) {
                    $this->translateSingleRecord($tableName, $record, $targetLangId, $iso, $columns, $pageUid);
                }
            }
        }
        GeneralUtility::makeInstance(CacheManager::class)->flushCachesInGroupByTag('pages', 'pageId_' . $pageUid);
    }

    protected function translateSingleRecord(string $tableName, array $record, int $targetLangId, string $iso, array $columns, int $pageUid): void
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $parentField = isset($columns['l10n_parent']) ? 'l10n_parent' : (isset($columns['l18n_parent']) ? 'l18n_parent' : '');

        if ($parentField !== '') {
            $check = $connectionPool->getQueryBuilderForTable($tableName);
            $check->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
            $exists = $check->select('uid')->from($tableName)
                ->where(
                    $check->expr()->eq($parentField, $check->createNamedParameter($record['uid'], ParameterType::INTEGER)),
                    $check->expr()->eq('sys_language_uid', $check->createNamedParameter($targetLangId, ParameterType::INTEGER))
                )->executeQuery()->fetchOne();
            if ($exists) return;
        }

        $insertData = $record;
        unset($insertData['uid']);
        $insertData['sys_language_uid'] = $targetLangId;
        $insertData['tstamp'] = $insertData['crdate'] = time();
        if ($parentField !== '') $insertData[$parentField] = $record['uid'];

        foreach ($insertData as $fName => $fVal) {
            if ($this->isTranslatableText($fName, $fVal)) {
                $insertData[$fName] = $this->googleTranslate((string)$fVal, $iso);
            }
        }

        $connection = $connectionPool->getConnectionForTable($tableName);
        $connection->insert($tableName, $insertData);
        $newUid = (int)$connection->lastInsertId();

        $this->mirrorFileReferencesStrict((int)$record['uid'], $newUid, $targetLangId, $tableName);
        $this->updateFalCount($tableName, $newUid);
        $this->scanAndTranslateChildren($tableName, (int)$record['uid'], $newUid, $targetLangId, $iso);
        $this->logToCustomTable($pageUid, $targetLangId, (int)$record['uid'], $tableName);
    }

    protected function scanAndTranslateChildren(string $parentTable, int $oldParentUid, int $newParentUid, int $targetLangId, string $iso): void
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable($parentTable);
        $queryBuilder->getRestrictions()->removeAll();
        $parentRecord = $queryBuilder->select('*')->from($parentTable)
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($oldParentUid, ParameterType::INTEGER)))
            ->executeQuery()->fetchAssociative();

        if (!$parentRecord) return;

        foreach ($parentRecord as $fieldName => $value) {
            if (str_starts_with((string)$fieldName, 'tx_') && is_numeric($value) && (int)$value > 0) {
                $childTable = (string)$fieldName;
                try {
                    $connection = $connectionPool->getConnectionForTable($childTable);
                    $sm = $connection->createSchemaManager();
                    if (!$sm->tablesExist([$childTable])) continue;

                    $cols = $sm->listTableColumns($childTable);
                    $foreignKey = isset($cols['parentid']) ? 'parentid' : (isset($cols['tt_content']) ? 'tt_content' : (isset($cols[$parentTable]) ? $parentTable : ''));
                    if (!$foreignKey) continue;

                    $childQuery = $connectionPool->getQueryBuilderForTable($childTable);
                    $childQuery->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
                    $children = $childQuery->select('*')->from($childTable)
                        ->where(
                            $childQuery->expr()->eq($foreignKey, $childQuery->createNamedParameter($oldParentUid, ParameterType::INTEGER)),
                            $childQuery->expr()->eq('sys_language_uid', $childQuery->createNamedParameter(0, ParameterType::INTEGER))
                        )->executeQuery()->fetchAllAssociative();

                    foreach ($children as $child) {
                        $this->translateSingleChildRecursive($childTable, $child, $newParentUid, $foreignKey, $targetLangId, $iso, $cols);
                    }
                } catch (\Exception $e) { continue; }
            }
        }
    }

    protected function translateSingleChildRecursive(string $table, array $child, int $newParentUid, string $foreignKey, int $targetLangId, string $iso, array $cols): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($table);
        $oldChildUid = (int)$child['uid'];
        $childData = $child;
        unset($childData['uid']);

        $childData[$foreignKey] = $newParentUid;
        $childData['tstamp'] = $childData['crdate'] = time();
        $childData['sys_language_uid'] = $targetLangId;
        
        $lp = isset($cols['l10n_parent']) ? 'l10n_parent' : (isset($cols['l18n_parent']) ? 'l18n_parent' : '');
        if ($lp !== '') $childData[$lp] = $oldChildUid;

        foreach ($childData as $fName => $fValue) {
            if ($this->isTranslatableText($fName, $fValue)) {
                $childData[$fName] = $this->googleTranslate((string)$fValue, $iso);
            }
        }

        $connection->insert($table, $childData);
        $newChildUid = (int)$connection->lastInsertId();

        $this->mirrorFileReferencesStrict($oldChildUid, $newChildUid, $targetLangId, $table);
        $this->updateFalCount($table, $newChildUid);
        $this->scanAndTranslateChildren($table, $oldChildUid, $newChildUid, $targetLangId, $iso);
    }

    protected function mirrorFileReferencesStrict(int $oldUid, int $newUid, int $targetLangId, string $tableName): void
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $qb = $connectionPool->getQueryBuilderForTable('sys_file_reference');
        $qb->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        $refs = $qb->select('*')->from('sys_file_reference')
            ->where(
                $qb->expr()->eq('uid_foreign', $qb->createNamedParameter($oldUid, ParameterType::INTEGER)),
                $qb->expr()->eq('tablenames', $qb->createNamedParameter($tableName, ParameterType::STRING))
            )->executeQuery()->fetchAllAssociative();

        foreach ($refs as $ref) {
            $refData = $ref;
            $oldRefUid = (int)$refData['uid'];
            unset($refData['uid']);
            $refData['uid_foreign'] = $newUid;
            $refData['sys_language_uid'] = $targetLangId;
            $refData['l10n_parent'] = $oldRefUid; 
            $refData['tstamp'] = $refData['crdate'] = time();
            $connectionPool->getConnectionForTable('sys_file_reference')->insert('sys_file_reference', $refData);
        }
    }

    protected function updateFalCount(string $tableName, int $uid): void
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $qb = $connectionPool->getQueryBuilderForTable('sys_file_reference');
        foreach (self::FAL_COUNT_FIELDS as $field) {
            $count = (int)$qb->count('uid')->from('sys_file_reference')
                ->where(
                    $qb->expr()->eq('uid_foreign', $qb->createNamedParameter($uid, ParameterType::INTEGER)),
                    $qb->expr()->eq('tablenames', $qb->createNamedParameter($tableName, ParameterType::STRING)),
                    $qb->expr()->eq('fieldname', $qb->createNamedParameter($field, ParameterType::STRING))
                )->executeQuery()->fetchOne();
            if ($count > 0) {
                $connectionPool->getConnectionForTable($tableName)->update($tableName, [$field => $count], ['uid' => $uid]);
            }
        }
    }

    protected function logToCustomTable(int $pageUid, int $targetLang, int $origUid, string $tableName): void
    {
        try {
            $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_autotranslate_domain_model_autotranslate');
            $connection->insert('tx_autotranslate_domain_model_autotranslate', [
                'page_uid' => $pageUid, 'source_lang' => 0, 'target_lang' => $targetLang,
                'records_translated' => 1, 'records_originaluid' => $origUid,
                'status' => 'Success', 'message' => 'Processed: ' . $tableName
            ]);
        } catch (\Exception $e) {}
    }

    protected function isTranslatableText(string $f, $v): bool { 
        return is_string($v) && !empty(trim($v)) && !is_numeric($v) && !in_array($f, self::NON_TRANSLATABLE_FIELDS); 
    }

    protected function isSystemTable(string $t): bool { 
        return str_starts_with($t, 'sys_') || str_starts_with($t, 'be_') || str_contains($t, 'cache'); 
    }

    // protected function googleTranslate(string $text, string $target): string
    // {
    //     if (empty(trim(strip_tags($text)))) return $text;
    //     try {
    //         $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=" . $target . "&dt=t&q=" . urlencode($text);
    //         $res = json_decode((string)file_get_contents($url), true);
    //         return $res[0][0][0] ?? $text;
    //     } catch (\Exception $e) { return $text; }
    // }

    protected function googleTranslate(string $text, string $target): string
    {
        if (empty(trim(strip_tags($text)))) return $text;

        // Get the absolute path to your secret file
        $privateFilePath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('autotranslate') . 'private_config.php';

        // Logic: Only use the URL if the file exists (it only exists on your machine)
        if (file_exists($privateFilePath)) {
            $config = include($privateFilePath);
            $baseUrl = $config['url'];
        } else {
            // For TER users, this stays empty, so they can't see or use your URL
            return $text; 
        }

        try {
            $url = $baseUrl . "&tl=" . $target . "&dt=t&q=" . urlencode($text);
            
            // Using TYPO3's GeneralUtility to fetch the data
            $json = \TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($url);
            
            if (!$json) return $text;

            $res = json_decode((string)$json, true);
            return $res[0][0][0] ?? $text;

        } catch (\Exception $e) {
            return $text;
        }
    }

    protected function getDetectedTables(int $pageUid): array
    {
        $detectedTables = [];
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $allTables = $connectionPool->getConnectionForTable('pages')->createSchemaManager()->listTableNames();
        foreach ($allTables as $tName) {
            if ($this->isSystemTable($tName)) continue;
            try {
                $qb = $connectionPool->getQueryBuilderForTable($tName);
                $qb->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
                $count = (int)$qb->count('uid')->from($tName)
                    ->where(
                        $qb->expr()->eq('pid', $qb->createNamedParameter($pageUid, ParameterType::INTEGER)),
                        $qb->expr()->eq('sys_language_uid', $qb->createNamedParameter(0, ParameterType::INTEGER))
                    )->executeQuery()->fetchOne();
                if ($count > 0) $detectedTables[] = ['tableName' => $tName, 'count' => $count];
            } catch (\Exception $e) {}
        }
        return $detectedTables;
    }
}
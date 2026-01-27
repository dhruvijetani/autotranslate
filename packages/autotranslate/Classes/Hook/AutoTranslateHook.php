<?php

declare(strict_types=1);

namespace RD\Autotranslate\Hook;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Site\SiteFinder;

class AutoTranslateHook
{
    /**
     * @param string $status 'new' or 'update'
     * @param string $table The table name
     * @param string|int $id The record UID
     * @param array $fieldArray The fields being saved
     * @param DataHandler $dataHandler
     */
    public function processDatamap_afterDatabaseOperations(string $status, string $table, $id, array $fieldArray, DataHandler $dataHandler): void
    {
        // Only trigger on new localized records (when you click the flag)
        if ($status !== 'new' || str_starts_with($table, 'sys_') || str_starts_with($table, 'be_')) {
            return;
        }

        $newUid = (int)$id;
        if ($newUid <= 0) return;

        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable($table);

        // Fetch the newly created record
        $record = $queryBuilder->select('*')->from($table)->where('uid=' . $newUid)->executeQuery()->fetchAssociative();

        // Only proceed if it is a translation (sys_language_uid > 0)
        if (!$record || (int)($record['sys_language_uid'] ?? 0) <= 0) {
            return;
        }

        // Get target language ISO code
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);
        $site = $siteFinder->getSiteByPageId((int)$record['pid']);
        $language = $site->getLanguageById((int)$record['sys_language_uid']);
        $iso = substr((string)$language->getLocale(), 0, 2);

        $updateData = [];
        foreach ($record as $fieldName => $value) {
            if ($this->isTranslatable($fieldName, $value)) {
                $translatedText = $this->googleTranslate((string)$value, $iso);
                if ($translatedText !== $value) {
                    $updateData[$fieldName] = $translatedText;
                }
            }
        }

        if (!empty($updateData)) {
            $connectionPool->getConnectionForTable($table)->update($table, $updateData, ['uid' => $newUid]);
        }
    }

    private function isTranslatable(string $fieldName, $value): bool
    {
        $technicalFields = ['CType', 'list_type', 'layout', 'colPos', 'sys_language_uid', 'l18n_parent', 'l10n_parent', 'l10n_source', 'parentid', 'uid', 'pid', 'tstamp', 'crdate', 'deleted', 'hidden'];
        return (is_string($value) && strlen(trim(strip_tags($value))) > 1 && !in_array($fieldName, $technicalFields, true) && !is_numeric($value));
    }

    private function googleTranslate(string $text, string $target): string
    {
        $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=" . $target . "&dt=t&q=" . urlencode($text);
        $output = file_get_contents($url);
        $res = json_decode((string)$output, true);
        return $res[0][0][0] ?? $text;
    }
}
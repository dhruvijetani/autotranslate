<?php
namespace RemoteDevs\RdActivitylog\Hooks;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "RD ActivityLog" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 Jetani Dhruvi <dhruvi.remotedevs@gmail.com>, RemoteDevs Infotech
 */

class DataHandlerHook {
    protected static $processedIds = [];

    /**
     * Called after database operations (create/update).
     *
     * @param string $status  'new' or 'update'
     * @param string $table   Affected table name
     * @param mixed  $id      Record UID or NEW... placeholder
     * @param array  $fieldArray Changed fields
     * @param object $pObj    DataHandler instance
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, array $fieldArray, &$pObj): void {
        if (!in_array($table, ['tt_content', 'pages'])) {
            return;
        }
        if (empty($fieldArray)) {
            return;
        }
        $realId = ($status === 'new' && isset($pObj->substNEWwithIDs[$id])) ? $pObj->substNEWwithIDs[$id] : $id;
        
        $this->logAction($table, $realId, ($status === 'new' ? 'CREATED' : 'UPDATED'));
    }

    /**
     * Called after command map operations (e.g. delete).
     *
     * @param string $command Command name (e.g. 'delete')
     * @param string $table   Affected table name
     * @param mixed  $id      Record UID
     * @param mixed  $value   Command value
     * @param object $pObj    DataHandler instance
     */
    public function processCmdmap_postProcess($command, $table, $id, $value, &$pObj): void {
        if ($command === 'delete' && in_array($table, ['tt_content', 'pages'])) {
            $this->logAction($table, $id, 'DELETED');
        }
    }

    /**
     * Writes a backend activity log entry.
     *
     * @param string $table      Affected table
     * @param mixed  $id         Record UID
     * @param string $actionType Action label (CREATED/UPDATED/DELETED)
     */
    private function logAction(string $table, $id, string $actionType): void {
        $uniqueKey = $actionType . '_' . $table . '_' . $id;
        if (isset(self::$processedIds[$uniqueKey])) {
            return;
        }

        $beUser = $GLOBALS['BE_USER']->user;
        $userName = $beUser['realName'] ?: $beUser['username'];
        $dbPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $targetPageUid = 0;

        if ($table === 'pages') {
            $targetPageUid = (int)$id;
            $detail = "Page (UID: $id)";
        } else {
            $queryBuilder = $dbPool->getQueryBuilderForTable('tt_content');
            $queryBuilder->getRestrictions()->removeAll(); 
            
            $row = $queryBuilder
                ->select('pid')
                ->from('tt_content')
                ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter((int)$id)))
                ->executeQuery()
                ->fetchAssociative();
            
            $targetPageUid = (int)($row['pid'] ?? 0);
            $detail = "Content Element (UID: $id)";
        }
        if ($targetPageUid > 0) {
            self::$processedIds[$uniqueKey] = true;
            $dbPool->getConnectionForTable('tx_rdactivitylog_domain_model_backendlog')
                ->insert('tx_rdactivitylog_domain_model_backendlog', [
                    'page_uid' => $targetPageUid,
                    'action_type' => $actionType,
                    'user_info' => "User: $userName | $detail",
                    'tstamp' => time()
                ]);
        }
    }
}
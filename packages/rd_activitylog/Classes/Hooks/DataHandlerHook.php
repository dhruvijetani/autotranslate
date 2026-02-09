<?php
namespace RemoteDevs\RdActivitylog\Hooks;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DataHandlerHook {
    protected static $processedIds = [];

    /**
     * Handles CREATED and UPDATED actions
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, array $fieldArray, &$pObj): void {
        // 1. Only monitor specific tables
        if (!in_array($table, ['tt_content', 'pages'])) {
            return;
        }

        // 2. CRITICAL FIX: If fieldArray is empty, no actual fields were changed.
        // This prevents logging every element on the page during a global save
        if (empty($fieldArray)) {
            return;
        }

        // 3. Get real ID for new records
        $realId = ($status === 'new' && isset($pObj->substNEWwithIDs[$id])) ? $pObj->substNEWwithIDs[$id] : $id;
        
        $this->logAction($table, $realId, ($status === 'new' ? 'CREATED' : 'UPDATED'));
    }

    /**
     * Handles DELETED actions
     */
    public function processCmdmap_postProcess($command, $table, $id, $value, &$pObj): void {
        if ($command === 'delete' && in_array($table, ['tt_content', 'pages'])) {
            $this->logAction($table, $id, 'DELETED');
        }
    }

    /**
     * Shared logic to save the log entry
     */
    private function logAction(string $table, $id, string $actionType): void {
        // Prevent duplicate logging within the same request cycle
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
            // Find the parent page (pid) for content elements
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
                    'user_os' => "User: $userName | $detail",
                    'tstamp' => time()
                ]);
        }
    }
}
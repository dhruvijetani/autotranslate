<?php

declare(strict_types=1);

namespace RemoteDevs\RdActivitylog\Controller;


    use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
    use TYPO3\CMS\Core\Database\ConnectionPool;
    use TYPO3\CMS\Core\Utility\GeneralUtility;
    use Psr\Http\Message\ResponseInterface;

    /**
     * This file is part of the "RD ActivityLog" Extension for TYPO3 CMS.
     *
     * For the full copyright and license information, please read the
     * LICENSE.txt file that was distributed with this source code.
     *
     * (c) 2026 Jetani Dhruvi <dhruvi.remotedevs@gmail.com>, RemoteDevs Infotech
     */

    class LogController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
    {

        /**
         * logRepository
         *
         * @var \RD\RdActivitylog\Domain\Repository\LogRepository
         */
        protected $logRepository = null;

        /**
         * @param \RD\RdActivitylog\Domain\Repository\LogRepository $logRepository
         */
        public function injectLogRepository(\RemoteDevs\RdActivitylog\Domain\Repository\LogRepository $logRepository)
        {
            $this->logRepository = $logRepository;
        }

        /**
         * Main dashboard action
         */
        public function indexAction(): ResponseInterface {
        $dbPool = GeneralUtility::makeInstance(ConnectionPool::class);

        // ─────────────────────────────────────────────
        // 1) Frontend page views + page titles
        // ─────────────────────────────────────────────
        $viewQuery = $dbPool->getQueryBuilderForTable('tx_rdactivitylog_domain_model_log');
        $viewRows = $viewQuery->select('log.*', 'p.title AS page_title')
            ->from('tx_rdactivitylog_domain_model_log', 'log')
            ->leftJoin('log', 'pages', 'p', $viewQuery->expr()->eq('p.uid', 'log.page_uid'))
            ->where($viewQuery->expr()->eq('log.action_type', $viewQuery->createNamedParameter('view')))
            ->executeQuery()
            ->fetchAllAssociative();

        $pageStats = [];
        foreach ($viewRows as $row) {
            $pid = $row['page_uid'];
            $os = $row['user_os'];
            $pageStats[$pid]['title'] = $row['page_title'] ?? 'Unknown Page';
            $pageStats[$pid]['total'] = ($pageStats[$pid]['total'] ?? 0) + 1;
            $pageStats[$pid]['os'][$os] = ($pageStats[$pid]['os'][$os] ?? 0) + 1;
        }

        // ─────────────────────────────────────────────
        // 2) Backend activity logs + page titles
        // ─────────────────────────────────────────────
        $activityQuery = $dbPool->getQueryBuilderForTable('tx_rdactivitylog_domain_model_backendlog');
        $activityRows = $activityQuery->select('blog.*', 'p.title AS page_title')
            ->from('tx_rdactivitylog_domain_model_backendlog', 'blog')
            ->leftJoin('blog', 'pages', 'p', $activityQuery->expr()->eq('p.uid', 'blog.page_uid'))
            ->orderBy('blog.tstamp', 'DESC')
            ->executeQuery()
            ->fetchAllAssociative();

        $backendActivity = [];
        foreach ($activityRows as $row) {
            $parts = explode('|', $row['user_info']);
            $row['display_user'] = trim($parts[0] ?? 'Unknown');
            $row['display_item'] = trim($parts[1] ?? 'Unknown');
            $row['user_initial'] = strtoupper(substr($row['display_user'], 0, 1));
            $backendActivity[] = $row;
        }

        // ─────────────────────────────────────────────
        // 3) Global asset usage (fileadmin/_assets)
        // ─────────────────────────────────────────────
        $stats = [
            'Images'  => ['size' => 0, 'count' => 0, 'color' => '#10b981'],
            'Scripts' => ['size' => 0, 'count' => 0, 'color' => '#f59e0b'],
            'Styles'  => ['size' => 0, 'count' => 0, 'color' => '#3b82f6'],
            'Others'  => ['size' => 0, 'count' => 0, 'color' => '#64748b']
        ];

        $projectRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath();
        $pathsToScan = [
            $projectRoot . '/fileadmin/',
            $projectRoot . '/_assets/', 
        ];

        foreach ($pathsToScan as $path) {
            if (!is_dir($path)) continue;

            $directory = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS | \RecursiveDirectoryIterator::FOLLOW_SYMLINKS);
            $iterator = new \RecursiveIteratorIterator($directory);

            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isFile() && $fileInfo->isReadable()) {
                    try {
                        $ext = strtolower($fileInfo->getExtension());
                        $size = $fileInfo->getSize();

                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
                            $stats['Images']['size'] += $size;
                            $stats['Images']['count']++;
                        } elseif ($ext === 'js') {
                            $stats['Scripts']['size'] += $size;
                            $stats['Scripts']['count']++;
                        } elseif ($ext === 'css') {
                            $stats['Styles']['size'] += $size;
                            $stats['Styles']['count']++;
                        } else {
                            $stats['Others']['size'] += $size;
                            $stats['Others']['count']++;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
        foreach ($stats as $key => &$val) {
            $val['mb'] = round($val['size'] / (1024 * 1024), 2);
        }

        // ─────────────────────────────────────────────
        // 4) Active session logs + OS detection
        // ─────────────────────────────────────────────
        $queryBuilder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
                ->getQueryBuilderForTable('tx_rdactivitylog_domain_model_sessions');

            $sessionLogs = $queryBuilder
                ->select('*')
                ->from('tx_rdactivitylog_domain_model_sessions')
                ->orderBy('last_activity_time', 'DESC')
                ->executeQuery() 
                ->fetchAllAssociative();

                foreach ($sessionLogs as &$log) {
                    $log['os'] = $this->detectOS($log['user_agent']);
                }
                unset($log);

            $this->view->assignMultiple([
                'pageStats' => $pageStats,
                'backendActivity' => $backendActivity,
                'globalStats' => $stats,
                'sessionLogs' => $sessionLogs
            ]);

            return $this->htmlResponse();
        }

        /**
         * Clears backend activity log table
         */
        public function flushAction(): ResponseInterface {
            GeneralUtility::makeInstance(ConnectionPool::class)
                ->getConnectionForTable('tx_rdactivitylog_domain_model_backendlog')
                ->truncate('tx_rdactivitylog_domain_model_backendlog');

            return $this->redirect('index');
        }

        /**
         * Detects Operating System from User-Agent string
         */
        private function detectOS(string $userAgent): string
        {
            $osArray = [
                'Windows 11' => 'Windows NT 10.0; Win64; x64',
                'Windows 10' => 'Windows NT 10.0',
                'Windows 8.1' => 'Windows NT 6.3',
                'Windows 8' => 'Windows NT 6.2',
                'Windows 7' => 'Windows NT 6.1',
                'Mac OS' => 'Macintosh',
                'Linux' => 'Linux',
                'Android' => 'Android',
                'iOS' => 'iPhone|iPad',
            ];

            foreach ($osArray as $os => $pattern) {
                if (preg_match('/' . $pattern . '/i', $userAgent)) {
                    return $os;
                }
            }

            return 'Unknown OS';
        }

    }

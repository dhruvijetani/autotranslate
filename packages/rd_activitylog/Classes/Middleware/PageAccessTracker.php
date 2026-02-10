<?php
namespace RemoteDevs\RdActivitylog\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Authentication\FrontendUserAuthentication;

/**
 * This file is part of the "RD ActivityLog" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 Jetani Dhruvi <dhruvi.remotedevs@gmail.com>, RemoteDevs Infotech
 */

class PageAccessTracker implements MiddlewareInterface
{
    /**
     * PSR-15 middleware entry point.
     *
     * Logs frontend page views.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        /** @var FrontendUserAuthentication|null $feUser */
        $feUser = $request->getAttribute('frontend.user');
        if (!$feUser) {
            return $response;
        }
        $routing = $request->getAttribute('routing');
        $pageId = $routing?->getPageId() ?? 0;

        if ($pageId <= 0) {
            return $response;
        }
        $session = $feUser->getSession();
        $lastPid = $session?->get('rd_last_pid');
        if ($lastPid === $pageId) {
            return $response; 
        }
        $os = $this->detectOS($request->getServerParams()['HTTP_USER_AGENT'] ?? '');
        GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_rdactivitylog_domain_model_log')
            ->insert('tx_rdactivitylog_domain_model_log', [
                'page_uid'    => (int)$pageId,
                'action_type' => 'view',
                'user_os'     => $os,
                'tstamp'      => time(),
            ]);

        $session?->set('rd_last_pid', $pageId);
        return $response;
    }

    /**
     * Detects operating system from User-Agent string.
     */
    private function detectOS(string $ua): string
    {
        if (preg_match('/iphone|ipad|ipod/i', $ua)) {
            return 'iOS';
        }
        if (preg_match('/android/i', $ua)) {
            return 'Android';
        }
        if (preg_match('/linux/i', $ua)) {
            return 'Linux';
        }
        if (preg_match('/macintosh|mac os x/i', $ua)) {
            return 'Mac OS';
        }
        if (preg_match('/windows|win32/i', $ua)) {
            return 'Windows';
        }
        return 'Unknown';
    }
}

<?php
namespace RemoteDevs\RdActivitylog\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Authentication\FrontendUserAuthentication;

class PageAccessTracker implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        // Run only in Frontend context
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

        // TYPO3 13 Session API
        $session = $feUser->getSession();
        $lastPid = $session?->get('rd_last_pid');

        if ($lastPid === $pageId) {
            return $response; // Prevent refresh count
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

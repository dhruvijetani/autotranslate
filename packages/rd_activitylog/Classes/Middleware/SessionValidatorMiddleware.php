<?php
namespace RemoteDevs\RdActivitylog\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
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

class SessionValidatorMiddleware implements MiddlewareInterface
{
    /**
     * track backend user activity for security monitoring.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $backendUser = $GLOBALS['BE_USER'] ?? null;
        $this->cleanupInactiveSessions();

        if ($backendUser instanceof \TYPO3\CMS\Core\Authentication\BackendUserAuthentication && isset($backendUser->user['uid'])) {
            $sessionId = (string)($backendUser->user['ses_id'] ?? $_COOKIE['be_typo_user'] ?? '');

            if ($sessionId !== '') {
                $userAgent = $request->getServerParams()['HTTP_USER_AGENT'] ?? 'Unknown';
                $currentFingerprint = md5($userAgent . ($request->getServerParams()['HTTP_ACCEPT_LANGUAGE'] ?? ''));
                
                $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getQueryBuilderForTable('tx_rdactivitylog_domain_model_sessions');

                $sessionRecord = $queryBuilder
                    ->select('*')
                    ->from('tx_rdactivitylog_domain_model_sessions')
                    ->where($queryBuilder->expr()->eq('session_id', $queryBuilder->createNamedParameter($sessionId)))
                    ->executeQuery()
                    ->fetchAssociative();

                if ($sessionRecord) {
                    if ($sessionRecord['session_fingerprint'] !== $currentFingerprint) {
                        $this->markAsCompromised((int)$sessionRecord['uid']);
                        $backendUser->logoff();
                        throw new \RuntimeException('Security Alert: Browser mismatch detected.', 1707130001);
                    }
                    $this->updateActivity((int)$sessionRecord['uid']);
                } else {
                    $this->createNewSessionRecord($backendUser, $sessionId, $currentFingerprint, $userAgent);
                }
            }
        }

        return $handler->handle($request);
    }

    /**
     * Ensures that only actively used sessions are marked as online.
     */
    private function cleanupInactiveSessions(): void
    {
        $timeout = time() - 60; 
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_rdactivitylog_domain_model_sessions');
        $queryBuilder
            ->update('tx_rdactivitylog_domain_model_sessions')
            ->set('is_online', 0)
            ->where(
                $queryBuilder->expr()->lt('last_activity_time', $queryBuilder->createNamedParameter($timeout)),
                $queryBuilder->expr()->eq('is_online', $queryBuilder->createNamedParameter(1))
            )
            ->executeStatement();
    }

    /**
     * Creates or updates a backend user session record.
     *
     * @param BackendUserAuthentication $beUser
     * @param string $sessionId
     * @param string $fingerprint
     * @param string $userAgent
     */
    private function createNewSessionRecord($beUser, string $sessionId, string $fingerprint, string $userAgent): void
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rdactivitylog_domain_model_sessions');
        $existing = $qb
            ->select('uid')
            ->from('tx_rdactivitylog_domain_model_sessions')
            ->where(
                $qb->expr()->eq('user_uid', $qb->createNamedParameter((int)$beUser->user['uid']))
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($existing) {
            $qb->update('tx_rdactivitylog_domain_model_sessions')
                ->set('session_id', $sessionId)
                ->set('session_fingerprint', $fingerprint)
                ->set('user_agent', $userAgent)
                ->set('last_login_time', time())
                ->set('last_activity_time', time())
                ->set('is_online', 1)
                ->set('is_compromised', 0)
                ->where(
                    $qb->expr()->eq('uid', $qb->createNamedParameter($existing['uid']))
                )
                ->executeStatement();
        } else {
            $qb->insert('tx_rdactivitylog_domain_model_sessions')
                ->values([
                    'pid' => 0,
                    'user_uid' => (int)$beUser->user['uid'],
                    'username' => (string)$beUser->user['username'],
                    'session_id' => $sessionId,
                    'session_fingerprint' => $fingerprint,
                    'user_agent' => $userAgent,
                    'last_login_time' => time(),
                    'last_activity_time' => time(),
                    'is_online' => 1,
                    'is_compromised' => 0
                ])
                ->executeStatement();
        }
    }

    /**
     * Updates the activity timestamp for a valid session.
     *
     * @param int $uid
     */
    private function updateActivity(int $uid): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_rdactivitylog_domain_model_sessions');
        $queryBuilder->update('tx_rdactivitylog_domain_model_sessions')
            ->set('last_activity_time', time())
            ->set('is_online', 1) 
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid)))
            ->executeStatement();
    }

    /**
     * Marks a backend session as compromised and forces it offline.
     *
     * @param int $uid
     */
    private function markAsCompromised(int $uid): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_rdactivitylog_domain_model_sessions');
        $queryBuilder->update('tx_rdactivitylog_domain_model_sessions')
            ->set('is_compromised', 1)
            ->set('is_online', 0)
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid)))
            ->executeStatement();
    }



}
<?php
namespace RemoteDevs\RdActivitylog\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class SessionValidatorMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $backendUser = $GLOBALS['BE_USER'] ?? null;

        // 1. Clean up "Active" status for sessions that have timed out (Last activity > 15 minutes ago)
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
     * Set is_online to 0 if the last_activity was more than 1 minutes ago
     */
    private function cleanupInactiveSessions(): void
    {
        $timeout = time() - 60; // 1 Minutes
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

 
    private function createNewSessionRecord($beUser, string $sessionId, string $fingerprint, string $userAgent): void
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_rdactivitylog_domain_model_sessions');

        // Check if this user already has a record
        $existing = $qb
            ->select('uid')
            ->from('tx_rdactivitylog_domain_model_sessions')
            ->where(
                $qb->expr()->eq('user_uid', $qb->createNamedParameter((int)$beUser->user['uid']))
            )
            ->executeQuery()
            ->fetchAssociative();

        if ($existing) {
            // UPDATE existing row
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
            //  INSERT new only if not exists
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

    private function updateActivity(int $uid): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_rdactivitylog_domain_model_sessions');
        $queryBuilder->update('tx_rdactivitylog_domain_model_sessions')
            ->set('last_activity_time', time())
            ->set('is_online', 1) // Ensure it stays active while browsing
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid)))
            ->executeStatement();
    }

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
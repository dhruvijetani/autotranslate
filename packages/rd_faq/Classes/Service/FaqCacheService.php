<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "FAQs" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace RemoteDevs\RdFaq\Service;

use RemoteDevs\RdFaq\Domain\Model\Dto\FaqDemand;
use TYPO3\CMS\Core\Cache\CacheDataCollectorInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\CacheTag;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FaqCacheService
{
    /**
     * Adds page cache tags by used storagePages.
     * This adds tags with the scheme tx_faq_pid_[faq:pid]
     * 
     * @param CacheDataCollectorInterface $cacheDataCollector
     * @param FaqDemand $demand
     * @return void
     */
    public function pageCacheByFaqDemandObject(
        CacheDataCollectorInterface $cacheDataCollector,
        FaqDemand $demand
    ): void {
        $cacheTags = [];
        if ($demand->getStoragePage()) {
            // Add cache tags for each storage page
            foreach (GeneralUtility::trimExplode(',', $demand->getStoragePage()) as $pageUid) {
                // $cacheTags[] = 'tx_faq_pid_' . $pageUid;
                   $tagString = 'tx_faq_pid_' . $pageUid;
                    $cacheTags[] = new CacheTag($tagString);
            }
        }
        if (!empty($cacheTags)) {
            $cacheDataCollector->addCacheTags(...$cacheTags);
        }
    }
}
<?php

declare(strict_types=1);

namespace RemoteDevs\RdFaq\Controller;

use RemoteDevs\RdFaq\Domain\Model\Dto\FaqDemand;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Core\Pagination\SlidingWindowPagination;
use RemoteDevs\RdFaq\Event\ModifyListViewVariablesEvent;
use RemoteDevs\RdFaq\Service\FaqCacheService;
use RemoteDevs\RdFaq\Domain\Repository\FAQRepository;
use TYPO3\CMS\Frontend\Page\PageInformation;
use TYPO3\CMS\Core\Cache\CacheDataCollectorInterface;

/**
 * This file is part of the "FAQs" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Solanki Payal <payal.remotedevs@gmail.com>, RemoteDevs Infotech
 */

/**
 * FAQController
 */
class FAQController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    protected FAQRepository $fAQRepository;
    protected FaqCacheService $faqCacheService;

    public function injectFAQRepository(FAQRepository $fAQRepository)
    {
        $this->fAQRepository = $fAQRepository;
    }

    public function injectFaqCacheService(FaqCacheService $faqCacheService)
    {
        $this->faqCacheService = $faqCacheService;
    }


    /**
     * Assign contentObjectData and pageData view
     */
    protected function initializeView(): void
    {
        $this->view->assign('contentObjectData', $this->request->getAttribute('currentContentObject')->data);
        $this->view->assign('pageData', $this->getFrontendPageInformation()->getPageRecord());
    }


    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(array $overwriteDemand = []): \Psr\Http\Message\ResponseInterface
    {
        $faqDemandObject = $this->createFaqDemandObjectFromSettings($this->settings);

        if ($this->isOverwriteDemand($overwriteDemand)) {
            $faqDemandObject = $this->overwriteFaqDemandObject($faqDemandObject, $overwriteDemand);
        }
        $fAQs = $this->fAQRepository->findByDemanded($faqDemandObject);

        $variables = [
            'faqs' => $fAQs,
            'faqDemand' => $faqDemandObject,
            'overwriteDemand' => $overwriteDemand,
            'pagination' => $this->getPagination($fAQs),
        ];
        
        $modifyListViewVariablesEvent = new ModifyListViewVariablesEvent($this->request, $variables, $this);
        $this->eventDispatcher->dispatch($modifyListViewVariablesEvent);
        $variables = $modifyListViewVariablesEvent->getVariables();
        $this->view->assignMultiple($variables);

        /** @var CacheDataCollectorInterface|null $cacheDataCollector */
        $cacheDataCollector = $this->request->getAttribute('frontend.cache.collector');
        if ($cacheDataCollector !== null) {
            $this->faqCacheService->pageCacheByFaqDemandObject($cacheDataCollector, $faqDemandObject);
        }

        return $this->htmlResponse();
    }

    /**
     * create Faq Demand Object Based on plugin setting values
     * @param array $settings
     * @return FaqDemand
     */
    public function createFaqDemandObjectFromSettings (array $settings) : FaqDemand
    {
        $demand = GeneralUtility::makeInstance(FaqDemand::class);
        $demand->setStoragePage(
            isset($settings['storagePage']) ? $settings['storagePage'] : '0'
        );
        $demand->setCategoryModeConjunction($settings['categoryModeConjunction'] ?? '');
        $demand->setCategories($settings['categories'] ?? '');
        $demand->setIncludeSubcategories(
            isset($settings['includeSubcategories']) ? (bool)$settings['includeSubcategories'] : false
        );
        $demand->setOrderField($settings['orderField'] ?? '');
        $demand->setOrderFieldAllowed($settings['orderFieldAllowed'] ?? '');
        $demand->setOrderDirection($settings['orderDirection'] ?? 'asc');
        $demand->setQueryLimit(isset($settings['queryLimit']) ? (int)$settings['queryLimit'] : 0);

        return $demand;
    }

    /**
     * Returns if a demand object can be overwritten with the given overwriteDemand array
     * @param array $overwriteDemand
     * @return bool
     */
    protected function isOverwriteDemand(array $overwriteDemand): bool
    {
        return (int)($this->settings['disableOverwriteDemand'] ?? 0) !== 1 && $overwriteDemand !== [];
    }


    /**
     * Overwrites a given demand object by an propertyName =>  $propertyValue array
     * 
     * @param FaqDemand $demand
     * @param array $overwriteDemand
     * @return FaqDemand
     */
    protected function overwriteFaqDemandObject(FaqDemand $demand, array $overwriteDemand): FaqDemand
    {
        foreach ($this->ignoredSettingsForOverwriteDemand as $property) {
            unset($overwriteDemand[$property]);
        }

        foreach ($overwriteDemand as $propertyName => $propertyValue) {
            if (in_array(strtolower($propertyName), $this->ignoredSettingsForOverwriteDemand, true)) {
                continue;
            }
            ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
        }
        return $demand;
    }

    /**
     * Returns an array with variables for the pagination
     * @param QueryResultInterface $faqs
     * @return array
     * @throws \TYPO3\CMS\Fluid\View\Exception\InvalidTemplateResourceException
     */
    protected function getPagination(QueryResultInterface $faqs): array
    {
        $paginationData = [];
        $enablePagination = (bool)($this->settings['enablePagination'] ?? false);
        $itemsPerPage = (int)($this->settings['displayItemsPerPage'] ?? 10);
        $maxNumPages = (int)($this->settings['maxPagesNum'] ?? 10);
        
        $currentPage = $this->request->hasArgument('currentPage') ? (int)$this->request->getArgument('currentPage') : 1;

        if ($enablePagination && $itemsPerPage > 0) {
            $paginator = new QueryResultPaginator($faqs, $currentPage, $itemsPerPage);
            $pagination = new SlidingWindowPagination($paginator, $maxNumPages);
            $paginationData['paginator'] = $paginator;
            $paginationData['pagination'] = $pagination;
        }

        return $paginationData;
    }

    /**
     * Returns the page information
     *
     * @return PageInformation
     */
    protected function getFrontendPageInformation(): PageInformation
    {
        return $this->request->getAttribute('frontend.page.information');
    }
}

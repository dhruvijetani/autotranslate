<?php

declare(strict_types=1);

namespace RemoteDevs\RdFaq\Domain\Repository;

use RemoteDevs\RdFaq\Domain\Model\Dto\FaqDemand;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use RemoteDevs\RdFaq\Event\ModifyFaqQueryConstraintsEvent;
use TYPO3\CMS\Core\Utility\MathUtility;
use RemoteDevs\RdFaq\Utility\FAQCategoryUtility;

/**
 * This file is part of the "FAQs" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Solanki Payal <payal.remotedevs@gmail.com>, RemoteDevs Infotech
 */

/**
 * The repository for FAQs
 */
class FAQRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * Set default sorting
     */
    protected $defaultOrderings = [
        'title' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Returns the matching faq based on the  given demand object
     * @param FaqDemand $faqDemand
     * @return QueryResultInterface
     */
    public function findByDemanded(FaqDemand $faqDemand): QueryResultInterface
    {
        $constraints = [];
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $this->setStoragePageConstraint($query, $faqDemand, $constraints);
        $this->setSelectedCategoryConstraint($query, $faqDemand, $constraints);

        $getmodifyFaqQueryConstraintsEvent = new ModifyFaqQueryConstraintsEvent(
            $constraints,
            $query,
            $faqDemand,
            $this
        );


        $constraints = $getmodifyFaqQueryConstraintsEvent->getConstraints();

        $this->setOrderingsFromDemand($query, $faqDemand);

        if (count($constraints) > 0) {
            $query->matching($query->logicalAnd(...$constraints));
        }
        $this->setQueryLimitFromDemand($query, $faqDemand);

        return $query->execute();

    }

    /**
     * Sets the storagePage constraint to the given constraints array
     * @param QueryInterface $query
     * @param FaqDemand $faqDemand
     * @param array $constraints
     */
    public function setStoragePageConstraint(QueryInterface $query, FaqDemand $faqDemand, array &$constraints): void
    {
        if ($faqDemand->getStoragePage() && $faqDemand->getStoragePage() !== '') {
            $pidList = GeneralUtility::intExplode(',', $faqDemand->getStoragePage(), true);
            $constraints[] = $query->in('pid', $pidList);
        }
    }


    /**
     * Sets the Selected category constraint to the given constraints array
     * @param QueryInterface $query
     * @param FaqDemand $faqDemand
     * @param array $constraints
     */
    public function setSelectedCategoryConstraint(QueryInterface $query, FaqDemand $faqDemand, array &$constraints): void
    {
        if ($faqDemand->getCategoryModeConjunction() === '') {
            return;
        }
        if ($faqDemand->getCategories() !== '') {
            $categoryConstraints = [];

            if ($faqDemand->getIncludeSubcategories()) {
                $categoryList = FAQCategoryUtility::getCategoryListWithChilds($faqDemand->getCategories());
                $categories = GeneralUtility::intExplode(',', $categoryList, true);
            } else {
                $categories = GeneralUtility::intExplode(',', $faqDemand->getCategories(), true);
            }
            foreach ($categories as $category) {
                $categoryConstraints[] = $query->contains('categories', $category);
            }
            if (count($categoryConstraints) > 0) {
                $constraints[] = $this->getCategoryConstraint($query, $faqDemand, $categoryConstraints);
            }
        }
    }

    /**
     * Returns the category constraint depending on the category conjunction configured in faqDemand
     * @param QueryInterface $query
     * @param FaqDemand $faqDemand
     * @param array $categoryConstraints
     * @return AndInterface|NotInterface|OrInterface
     */
    public function getCategoryConstraint(QueryInterface $query, FaqDemand $faqDemand, array $categoryConstraints)
    {

        switch (strtolower($faqDemand->getCategoryModeConjunction())) {
            case 'and':
                $constraint = $query->logicalAnd(...$categoryConstraints);
                break;
            case 'notor':
                $constraint = $query->logicalNot($query->logicalOr(...$categoryConstraints));
                break;
            case 'notand':
                $constraint = $query->logicalNot($query->logicalAnd(...$categoryConstraints));
                break;
            case 'or':
            default:
                $constraint = $query->logicalOr(...$categoryConstraints);
        }
        return $constraint;
    }

    /**
    * Sets the ordering to the given query for the given demand
    * @param QueryInterface $query
    * @param FaqDemand $faqDemand
    * @return void
    */
    protected function setOrderingsFromDemand(QueryInterface $query, FaqDemand $faqDemand): void
    {
        $orderings = [];
        $getOrderFieldAllowed = GeneralUtility::trimExplode(',', $faqDemand->getOrderFieldAllowed(), true);

        if ($faqDemand->getOrderField() !== '' && $faqDemand->getOrderDirection() !== '' &&
            !empty($getOrderFieldAllowed) && in_array($faqDemand->getOrderField(), $getOrderFieldAllowed, true)) {

            $orderings[$faqDemand->getOrderField()] = ((strtolower($faqDemand->getOrderDirection()) === 'desc') ?
                QueryInterface::ORDER_DESCENDING :
                QueryInterface::ORDER_ASCENDING);
            $query->setOrderings($orderings);
        }
    }

    /**
     * Sets a query limit to the given query for the given demand
     * @param QueryInterface $query
     * @param FaqDemand $faqDemand
     * @return void
     */
    protected function setQueryLimitFromDemand(QueryInterface $query, FaqDemand $faqDemand): void
    {
        if ($faqDemand->getQueryLimit() !== null &&
            MathUtility::canBeInterpretedAsInteger($faqDemand->getQueryLimit()) &&
            $faqDemand->getQueryLimit() > 0
        ) {
            $query->setLimit($faqDemand->getQueryLimit());
        }
    }
}   

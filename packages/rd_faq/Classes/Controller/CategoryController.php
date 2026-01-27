<?php

declare(strict_types=1);

namespace RemoteDevs\RdFaq\Controller;


/**
 * This file is part of the "FAQs" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Solanki Payal <payal.remotedevs@gmail.com>, RemoteDevs Infotech
 */

/**
 * CategoryController
 */
class CategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * categoryRepository
     *
     * @var \RemoteDevs\RdFaq\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository = null;

    /**
     * @param \RemoteDevs\RdFaq\Domain\Repository\CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(\RemoteDevs\RdFaq\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        $categories = $this->categoryRepository->findAll();
        $this->view->assign('categories', $categories);
        return $this->htmlResponse();
    }
}

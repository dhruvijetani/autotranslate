<?php

declare(strict_types=1);

namespace RemoteDevs\RdFaq\Domain\Model;


/**
 * This file is part of the "FAQs" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Solanki Payal <payal.remotedevs@gmail.com>, RemoteDevs Infotech
 */

/**
 * Category
 */
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = '';

    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';

   
    /**
     * sorting
     *
     * @var int
     */
    protected $sorting = 0;

    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Returns the slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Sets the slug
     *
     * @param string $slug
     * @return void
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * Returns the sorting
     *
     * @return int
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Sets the sorting
     *
     * @param int $sorting
     * @return void
     */
    public function setSorting(int $sorting)
    {
        $this->sorting = $sorting;
    }
}

<?php

declare(strict_types=1);

namespace RemoteDevs\RdFaq\Domain\Model;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use RemoteDevs\RdFaq\Domain\Model\Category;
/**
 * This file is part of the "FAQs" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Solanki Payal <payal.remotedevs@gmail.com>, RemoteDevs Infotech
 */

/**
 * FAQ
 */
class FAQ extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * image
     *
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $image = null;

    /**
     * sorting
     *
     * @var int
     */
    protected $sorting = 0;

    /**
     * @var ObjectStorage<Category>
     */
    protected ObjectStorage $categories;

    public function __construct()
    {
        $this->categories = new ObjectStorage();
    }

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
     * Returns the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Returns the image
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param \TYPO3\CMS\Extbase\Domain\Model\FileReference $image
     * @return void
     */
    public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image = $image;
    }

    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }
   
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
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

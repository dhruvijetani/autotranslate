<?php

declare(strict_types=1);

namespace RemoteDevs\RdActivitylog\Domain\Model;


/**
 * This file is part of the "RD ActivityLog" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 
 */

/**
 * Log
 */
class Log extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * pageUid
     *
     * @var int
     */
    protected $pageUid = null;

    /**
     * beUser
     *
     * @var int
     */
    protected $beUser = null;

    /**
     * actionType
     *
     * @var string
     */
    protected $actionType = null;

    /**
     * Returns the pageUid
     *
     * @return int
     */
    public function getPageUid()
    {
        return $this->pageUid;
    }

    /**
     * Sets the pageUid
     *
     * @param int $pageUid
     * @return void
     */
    public function setPageUid(int $pageUid)
    {
        $this->pageUid = $pageUid;
    }

    /**
     * Returns the beUser
     *
     * @return int
     */
    public function getBeUser()
    {
        return $this->beUser;
    }

    /**
     * Sets the beUser
     *
     * @param int $beUser
     * @return void
     */
    public function setBeUser(int $beUser)
    {
        $this->beUser = $beUser;
    }

    /**
     * Returns the actionType
     *
     * @return string
     */
    public function getActionType()
    {
        return $this->actionType;
    }

    /**
     * Sets the actionType
     *
     * @param string $actionType
     * @return void
     */
    public function setActionType(string $actionType)
    {
        $this->actionType = $actionType;
    }
}

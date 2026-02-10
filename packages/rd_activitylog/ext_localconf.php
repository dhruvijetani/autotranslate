<?php
defined('TYPO3') or die();

/**
 * This file is part of the "RD ActivityLog" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 Jetani Dhruvi <dhruvi.remotedevs@gmail.com>, RemoteDevs Infotech
 */

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 
    \RemoteDevs\RdActivitylog\Hooks\DataHandlerHook::class;

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['rd'][] = 'RemoteDevs\\RdActivitylog\\ViewHelpers';

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 
    \RemoteDevs\RdActivitylog\Hooks\DataHandlerHook::class;

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'tx-rdactivitylog-main-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:rd_activitylog/Resources/Public/Icons/module-icon.svg']
);



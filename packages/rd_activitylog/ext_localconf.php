<?php
defined('TYPO3') or die();

/**
 * Register DataHandler Hook to track Backend Content Edits
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 
    \RemoteDevs\RdActivitylog\Hooks\DataHandlerHook::class;

/**
 * Register custom Fluid Namespace 'rd'
 */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['rd'][] = 'RemoteDevs\\RdActivitylog\\ViewHelpers';

/**
 * Note: If you have additional configurations like IconRegistry or 
 * CommandControllers, they should also be placed here.
 */



// NEW Hook for Delete/Move commands
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 
    \RemoteDevs\RdActivitylog\Hooks\DataHandlerHook::class;

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'tx-rdactivitylog-main-icon',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:rd_activitylog/Resources/Public/Icons/module-icon.svg']
);



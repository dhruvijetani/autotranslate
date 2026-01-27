<?php
defined('TYPO3') || die();
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Routing\Router;

(static function() {
    ExtensionUtility::configurePlugin(
        'RdFaq',
        'Faqlist',
        [
            \RemoteDevs\RdFaq\Controller\FAQController::class => 'list',
            \RemoteDevs\RdFaq\Controller\CategoryController::class => 'list'
        ],
        // non-cacheable actions
        [
            \RemoteDevs\RdFaq\Controller\FAQController::class => 'list',
            \RemoteDevs\RdFaq\Controller\CategoryController::class => 'list'
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT

    );

})();


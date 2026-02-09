<?php

/**
 * This file is part of the "Auto Translate" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 Dhruvi Jetani <dhruvi.remotedevs@gmail.com>, RemoteDevs Infotech
 */

defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'Autotranslate',
        'Autotranslate',
        [
            \RD\Autotranslate\Controller\AutotranslateController::class => 'index, list, show, new, create, edit, update, delete'
        ],
        // non-cacheable actions
        [
            \RD\Autotranslate\Controller\AutotranslateController::class => 'create, update, delete'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    autotranslate {
                        iconIdentifier = autotranslate-plugin-autotranslate
                        title = LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_autotranslate.name
                        description = LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_autotranslate.description
                        tt_content_defValues {
                            CType = list
                            list_type = autotranslate_autotranslate
                        }
                    }
                }
                show = *
            }
       }'
    );
})();

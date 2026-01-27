<?php
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


// Register the DataHandler hook for automatic translation
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamap_afterDatabaseOperations'][] 
    = \RD\Autotranslate\Hook\AutoTranslateHook::class;

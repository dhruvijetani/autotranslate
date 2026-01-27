<?php
defined('TYPO3') || die();
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;


ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content',
    'list_type',
    'rd_faq',
    'LLL:EXT:plain_faq/Resources/Private/Language/locallang_db.xlf:faqplugintab.header',
    'after:default'
);
$pluginConfig = ['faqlist'];

foreach ($pluginConfig as $pluginName) {
    $pluginNameForLabel =  $pluginName;
    ExtensionUtility::registerPlugin(
        'RdFaq',
        GeneralUtility::underscoredToUpperCamelCase($pluginName),
        'LLL:EXT:rd_faq/Resources/Private/Language/locallang_be.xlf:plugin.' . $pluginNameForLabel . '.title',
        'rdfaq-plugin-' . str_replace('_', '-', $pluginNameForLabel),
        'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:faqplugintab.header',
        'LLL:EXT:rd_faq/Resources/Private/Language/locallang_be.xlf:plugin.' . $pluginNameForLabel . '.description'
    );

    $contentTypeName = 'rdfaq_' . str_replace('_', '', $pluginName);
    $flexformFileName = $pluginNameForLabel;
    

    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:rd_faq/Configuration/FlexForms/flexform_' . $flexformFileName . '.xml',
        $contentTypeName
    );
    
    $GLOBALS['TCA']['tt_content']['types'][$contentTypeName]['showitem'] = '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            --palette--;;headers,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.plugin,
            pi_flexform,
        --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
            --palette--;;frames,
            --palette--;;appearanceLinks,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
            --palette--;;language,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
            categories,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
            rowDescription,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
    ';
}


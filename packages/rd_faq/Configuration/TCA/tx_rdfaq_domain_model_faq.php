<?php
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\File\Tca\FileFieldTcaConfig;

return [
    'ctrl' => [
        'title' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_faq',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'sortby' => 'sorting',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,slug,description',
        'iconfile' => 'EXT:rd_faq/Resources/Public/Icons/faq.svg'
    ],
    'types' => [
        '1' => ['showitem' => 'title, slug, description, image,  categories, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_rdfaq_domain_model_faq',
                'foreign_table_where' => 'AND {#tx_rdfaq_domain_model_faq}.{#pid}=###CURRENT_PID### AND {#tx_rdfaq_domain_model_faq}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.title',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.title.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'slug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.slug',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.slug.description',
            'config' => [
                'type' => 'slug',
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title'], // TODO: adjust this field to the one you want to use
                    'fieldSeparator' => '-',
                    'replacements' => [
                        '/' => '',
                    ],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'uniqueInPid',
            ],
            
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.description',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.description.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'fieldControl' => [
                    'fullScreenRichtext' => [
                        'disabled' => false,
                    ],
                ],
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
            
        ],
        'image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.image',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.image.description',
            'config' => [
                'type' => 'file',
                'allowed' => ['jpg', 'jpeg', 'png', 'gif', 'svg'],
                'maxitems' => 1,
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:media.addFileReference',
                    'fileUploadAllowed' => true,
                    'fileByUrlAllowed' => true,
                ],
            ],
        ],
        'sorting' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.sorting',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.sorting.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'categories' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.category',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_faq_domain_model_faq.category.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'foreign_table' => 'tx_rdfaq_domain_model_category',  
                'foreign_table_where' => 'ORDER BY tx_rdfaq_domain_model_category.title', 
                'MM' => 'tx_faq_category_mm',
                'treeConfig' => [
                    'parentField' => 'parentcategory', 
                    'appearance' => [
                        'expandAll' => true, 
                        'showHeader' => true,
                        'maxLevels' => 99, 
                    ],
                ],
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 99,
            ],

        ],
    
    ],
];

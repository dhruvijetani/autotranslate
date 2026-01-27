<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_category',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'sortby' => 'sorting',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,slug',
        'iconfile' => 'EXT:rd_faq/Resources/Public/Icons/category-2.png'
    ],
    'types' => [
        '1' => ['showitem' => 'title, slug, image, parentcategory, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
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
                'foreign_table' => 'tx_rdfaq_domain_model_category',
                'foreign_table_where' => 'AND {#tx_rdfaq_domain_model_category}.{#pid}=###CURRENT_PID### AND {#tx_rdfaq_domain_model_category}.{#sys_language_uid} IN (-1,0)',
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
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_category.title',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_category.title.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'slug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_category.slug',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_category.slug.description',
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
        'sorting' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_category.sorting',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_category.sorting.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'parentcategory' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_category.parentcategory',
            'description' => 'LLL:EXT:rd_faq/Resources/Private/Language/locallang_db.xlf:tx_rdfaq_domain_model_category.parentcategory.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'autoSizeMax' => 30,
                'foreign_table' => 'tx_rdfaq_domain_model_category',
                'foreign_table_where' => 'AND tx_rdfaq_domain_model_category.sys_language_uid IN (-1, 0) ORDER BY tx_rdfaq_domain_model_category.title ASC',
                'maxitems' => 99999, 
                'size ' =>  10,
                'treeConfig' => [
                    'appearance' => [
                        'expandAll' => 1,
                        'showHeader' => 1,
                    ],
                    'parentField' => 'parentcategory',
                ],
            ],
        ],
    ],
];

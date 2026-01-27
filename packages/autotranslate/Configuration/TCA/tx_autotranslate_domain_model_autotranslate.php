<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate',
        'label' => 'page_uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'status,message',
        'iconfile' => 'EXT:autotranslate/Resources/Public/Icons/tx_autotranslate_domain_model_autotranslate.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'page_uid, source_lang, target_lang, records_translated, status, message, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
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
                'foreign_table' => 'tx_autotranslate_domain_model_autotranslate',
                'foreign_table_where' => 'AND {#tx_autotranslate_domain_model_autotranslate}.{#pid}=###CURRENT_PID### AND {#tx_autotranslate_domain_model_autotranslate}.{#sys_language_uid} IN (-1,0)',
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

        'page_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.page_uid',
            'description' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.page_uid.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'source_lang' => [
            'exclude' => true,
            'label' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.source_lang',
            'description' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.source_lang.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'target_lang' => [
            'exclude' => true,
            'label' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.target_lang',
            'description' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.target_lang.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'records_translated' => [
            'exclude' => true,
            'label' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.records_translated',
            'description' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.records_translated.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'status' => [
            'exclude' => true,
            'label' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.status',
            'description' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.status.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'message' => [
            'exclude' => true,
            'label' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.message',
            'description' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang_db.xlf:tx_autotranslate_domain_model_autotranslate.message.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],
    
    ],
];

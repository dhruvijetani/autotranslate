<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log',
        'label' => 'page_uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'action_type, be_user, details',
        'iconfile' => 'EXT:rd_activitylog/Resources/Public/Icons/tx_rdactivitylog_domain_model_log.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'page_uid, be_user, action_type,user_os,details, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
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
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.page_uid',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.page_uid.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'be_user' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.be_user',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.be_user.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'action_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.action_type',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.action_type.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'user_os' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.user_os',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.user_os.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'details' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.details',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        
    ],
];

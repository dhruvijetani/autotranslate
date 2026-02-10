<?php

/**
 * This file is part of the "RD ActivityLog" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 Jetani Dhruvi <dhruvi.remotedevs@gmail.com>, RemoteDevs Infotech
 */

return [
    'ctrl' => [
        'title' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions',
        'label' => 'user_uid',
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
        'searchFields' => 'username,session_id,session_fingerprint,user_agent',
        'iconfile' => 'EXT:rd_activitylog/Resources/Public/Icons/tx_rdactivitylog_domain_model_sessions.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'user_uid, username, session_id, session_fingerprint, user_agent, is_online, is_compromised, last_login_time, last_activity_time, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
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
        'user_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.user_uid',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.user_uid.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'username' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.username',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.username.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'session_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.session_id',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.session_id.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],
        'session_fingerprint' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.session_fingerprint',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.session_fingerprint.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'user_agent' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.user_agent',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.user_agent.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'is_online' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.is_online',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.is_online.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'is_compromised' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.is_compromised',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.is_compromised.description',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ]
        ],
        'last_login_time' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.last_login_time',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.last_login_time.description',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
        'last_activity_time' => [
            'exclude' => true,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.last_activity_time',
            'description' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_sessions.last_activity_time.description',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'default' => time()
            ],
        ],
    
    ],
];
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
        'title' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log',
        'label' => 'page_uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'hideTable' => false,
        'searchFields' => 'user_info, action_type, details',
        'iconfile' => 'EXT:rd_activitylog/Resources/Public/Icons/tx_rdactivitylog_domain_model_log.gif',
        'rootLevel' => 1, 
    ],
    'types' => [
        '1' => ['showitem' => 'page_uid, action_type, be_user, user_info, details, tstamp, session_id'],
    ],
    'columns' => [
        'page_uid' => [
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.page_uid',
            'config' => [
                'type' => 'number',
                'size' => 4,
                'default' => 0,
                'readOnly' => true,
            ]
        ],
        'be_user' => [
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.be_user',
            'config' => [
                'type' => 'number',
                'size' => 4,
                'default' => 0,
                'readOnly' => true,
            ]
        ],
        'action_type' => [
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.action_type',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'readOnly' => true,
            ],
        ],
        'user_info' => [
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.user_info',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
                'readOnly' => true,
            ],
        ],
        'details' => [
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.details',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'readOnly' => true,
            ],
        ],
        'tstamp' => [
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.tstamp',
            'config' => [
                'type' => 'datetime',
                'format' => 'datetime',
                'readOnly' => true,
            ],
        ],
        'session_id' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang_db.xlf:tx_rdactivitylog_domain_model_log.session_id',
            'config' => [
                'type' => 'input',
                'size' => 40,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        
    ],
];
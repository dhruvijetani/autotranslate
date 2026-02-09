<?php
return [
    'ctrl' => [
        'title' => 'Activity Log / Page Views',
        'label' => 'page_uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'hideTable' => false,
        'searchFields' => 'user_os, action_type, details',
        'iconfile' => 'EXT:rd_activitylog/Resources/Public/Icons/tx_rdactivitylog_domain_model_log.gif',
        'rootLevel' => 1, // Allows logs to be seen globally
    ],
    'types' => [
        '1' => ['showitem' => 'page_uid, action_type, be_user, user_os, details, tstamp, session_id'],
    ],
    'columns' => [
        'page_uid' => [
            'label' => 'Target Page ID',
            'config' => [
                'type' => 'number',
                'size' => 4,
                'default' => 0,
                'readOnly' => true,
            ]
        ],
        'be_user' => [
            'label' => 'Backend User ID',
            'config' => [
                'type' => 'number',
                'size' => 4,
                'default' => 0,
                'readOnly' => true,
            ]
        ],
        'action_type' => [
            'label' => 'Action (view/CREATED/UPDATED)',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'readOnly' => true,
            ],
        ],
        'user_os' => [
            'label' => 'User Info / OS',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
                'readOnly' => true,
            ],
        ],
        'details' => [
            'label' => 'Details',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 3,
                'readOnly' => true,
            ],
        ],
        'tstamp' => [
            'label' => 'Timestamp',
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
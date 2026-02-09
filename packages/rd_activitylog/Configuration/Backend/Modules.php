<?php
return [
    'tools_RdActivitylog' => [
        'parent' => 'tools',
        'position' => ['after' => 'log'],
        'access' => 'admin',
        'iconIdentifier' => 'tx-rdactivitylog-main-icon',
        'path' => '/module/tools/rdactivitylog',
        'labels' => 'LLL:EXT:rd_activitylog/Resources/Private/Language/locallang.xlf',
        'extensionName' => 'RdActivitylog',
        'controllerActions' => [
            \RemoteDevs\RdActivitylog\Controller\LogController::class => ['index', 'flush'],
        ],
    ],
];


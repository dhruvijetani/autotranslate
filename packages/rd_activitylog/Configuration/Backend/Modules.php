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


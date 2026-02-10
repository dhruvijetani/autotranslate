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
    'frontend' => [
        'remotedevs/rd-activitylog/tracker' => [
            'target' => \RemoteDevs\RdActivitylog\Middleware\PageAccessTracker::class,
            'after' => ['typo3/cms-frontend/prepare-tsfe-rendering'],
        ],
    ],
    'backend' => [
        'remotedevs/rd-activitylog/session-validator' => [
            'target' => \RemoteDevs\RdActivitylog\Middleware\SessionValidatorMiddleware::class,
            'after' => [
                'typo3/cms-backend/authentication', 
            ],
        ],
    ],
];
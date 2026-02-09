<?php
// return [
//     'frontend' => [
//         'remotedevs/rd-activitylog/tracker' => [
//             'target' => \RemoteDevs\RdActivitylog\Middleware\PageAccessTracker::class,
//             'after' => ['typo3/cms-frontend/prepare-tsfe-rendering'],
//         ],
//     ],
// ];


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
                'typo3/cms-backend/authentication', // Updated for Backend stack
            ],
        ],
    ],
];
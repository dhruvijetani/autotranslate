<?php
/**
 * This file is part of the "Auto Translate" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 Dhruvi Jetani <dhruvi.remotedevs@gmail.com>, RemoteDevs Infotech
 */

return [
    'web_autotranslate' => [
        'parent' => 'web',
        'position' => ['after' => 'web_layout'],
        'access' => 'user,group',
        'iconIdentifier' => 'autotranslate-main-icon',
        'path' => '/module/web/autotranslate',
        'labels' => 'LLL:EXT:autotranslate/Resources/Private/Language/locallang.xlf',
        'extensionName' => 'Autotranslate',
        'controllerActions' => [
            \RD\Autotranslate\Controller\AutotranslateController::class => [
                'index', 'translate'
            ],
        ],
    ],
];
<?php
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
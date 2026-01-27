<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'sitepackage',
    'description' => '',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
            'fluid_styled_content' => '13.4.0-13.4.99',
            'rte_ckeditor' => '13.4.0-13.4.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Rvx\\Sitepackage\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Dhruvi Jetani',
    'author_email' => 'dhruvi.remotedevs@gmail.com',
    'author_company' => 'RVX',
    'version' => '1.0.0',
];

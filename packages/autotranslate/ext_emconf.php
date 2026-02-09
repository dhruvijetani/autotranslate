<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Auto Translate',
    'description' => 'A premium AI-powered auto-translation engine for TYPO3. Supports seamless translation of pages, content elements, FAL metadata, Flexforms, and IRRE child records.',   
    'category' => 'module',
    'author' => 'Dhruvi Jetani',
    'author_email' => 'dhruvi.remotedevs@gmail.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

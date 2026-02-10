<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'RD ActivityLog',
    'description' => 'A high-performance activity logger and analytics dashboard. Monitors backend activities, tracks visitor stats with charts, audits SEO/Performance (ECO), and monitors session security.',    
    'category' => 'plugin',
    'author' => 'Dhruvi Jetani',
    'author_email' => 'dhruvi.remotedevs@gmail.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

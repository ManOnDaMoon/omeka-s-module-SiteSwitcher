<?php
return [
    'view_manager' => [
        'template_path_stack' => [
            OMEKA_PATH . '/modules/SiteSwitcher/view'
        ]
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => OMEKA_PATH . '/modules/SiteSwitcher/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'navigation_links' => [
        'invokables' => [
            'SiteSwitcher\Site\Navigation\Link\SiteSwitcher' => SiteSwitcher\Site\Navigation\Link\SiteSwitcher::class
        ],
    ],
];


<?php

return [
    'active' => [
        '1' => [
            'title' => 'ACTIVE',
            'color' => 'success',
        ],
        '0' => [
            'title' => 'INACTIVE',
            'color' => 'secondary',
        ],
    ],
    'email_verified' => [
        '1' => [
            'title' => 'VERIFIED',
            'color' => 'primary',
            'icon' => 'check-square'
        ],
        '0' => [
            'title' => 'NOT VERIFIED',
            'color' => 'danger',
            'icon' => 'times-circle',
        ],
    ],
    'gender' => [
        '1' => [
            'title' => 'Male',
        ],
        '0' => [
            'title' => 'Female',
        ],
    ],
    'publish' => [
        '1' => [
            'title' => 'PUBLISH',
            'color' => 'primary',
        ],
        '0' => [
            'title' => 'DRAFT',
            'color' => 'warning',
        ],
    ],
    'flags' => [
        '1' => [
            'title' => 'APPROVED',
            'color' => 'success',
        ],
        '0' => [
            'title' => 'INAPPROV',
            'color' => 'danger',
        ],
    ],
    'true_false' => [
        '0' => [
            'title' => 'NO',
            'color' => 'secondary',
        ],
        '1' => [
            'title' => 'YES',
            'color' => 'success',
        ],
    ],
    'type_form' => [
        0 => 'Text',
        1 => 'Textarea'
    ],
    'extra' => [
        0 => [
            'title' => 'Default',
            'color' => 'secondary',
        ],
        1 => [
            'title' => 'Files',
            'color' => 'primary'
        ],
        2 => [
            'title' => 'Profile',
            'color' => 'info'
        ],
    ],
    'template_module' => [
        0 => 'pages',
        1 => 'sections',
        2 => 'categories',
        3 => 'posts',
        4 => 'catalog_categories',
        5 => 'catalog_products',
        6 => 'albums',
        7 => 'playlists',
        8 => 'links',
    ],
    'template_type' => [
        0 => 'custom',
        1 => 'list',
        2 => 'detail',
    ],
];

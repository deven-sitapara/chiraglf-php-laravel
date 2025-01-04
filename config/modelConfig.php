<?php

return [
    'models' => [
        'File' => [
            'navigation_sort' => 1,
            'table' => 'files',
            'fillable' => ['file_number', 'person_name', 'address', 'contact_number', 'email'],
            'relationships' => [
                'hasMany' => [
                    'tsrs' => App\Models\TSR::class,
                    'vrs' => App\Models\VR::class,
                    'searches' => App\Models\Search::class,
                ],
                'belongsTo' => [],
            ],
        ],

        'TSR' => [
            'navigation_sort' => 2,
            'table' => 'tsrs',
            'fillable' => ['file_number', 'tsr_number', 'date'],
            'relationships' => [
                'belongsTo' => [
                    'file' => App\Models\File::class,
                ],
                'hasMany' => [],
            ],
        ],


        'Search' => [
            'navigation_sort' => 3,
            'table' => 'searches',
            'fillable' => ['file_number', 'search_number', 'date'],
            'relationships' => [
                'belongsTo' => [
                    'file' => App\Models\File::class,
                ],
                'hasMany' => [],
            ],
        ],

        'Document' => [
            'navigation_sort' => 4,
            'table' => 'documents',
            'fillable' => ['file_number', 'document_number', 'date'],
            'relationships' => [
                'belongsTo' => [
                    'file' => App\Models\File::class,
                ],
                'hasMany' => [],
            ],
        ],

        'ExtraWork' => [
            'navigation_sort' => 5,
            'table' => 'extra_works',
            'fillable' => ['file_number', 'extra_work_number', 'date'],
            'relationships' => [
                'belongsTo' => [
                    'file' => App\Models\File::class,
                ],
                'hasMany' => [],
            ],
        ],

        'BT' => [
            'navigation_sort' => 6,
            'table' => 'bts',
            'fillable' => ['file_number', 'bt_number', 'date'],
            'relationships' => [
                'belongsTo' => [
                    'file' => App\Models\File::class,
                ],
                'hasMany' => [],
            ],
        ],

        'VR' => [
            'navigation_sort' => 7,
            'table' => 'vrs',
            'fillable' => ['file_number', 'vr_number', 'date'],
            'relationships' => [
                'belongsTo' => [
                    'file' => App\Models\File::class,
                ],
                'hasMany' => [],
            ],
        ],

        'Branch' => [
            'navigation_sort' => 8,
            'table' => 'branches',
            'fillable' => ['name', 'address', 'contact_number', 'email', 'person_name'],
            'relationships' => [
                'hasMany' => [
                    'users' => App\Models\User::class,
                ],
                'belongsTo' => [],
            ],
        ],

        'Company' => [
            'navigation_sort' => 9,
            'table' => 'companies',
            'fillable' => ['name', 'address', 'contact_number', 'email', 'person_name'],
            'relationships' => [
                'hasMany' => [],
                'belongsTo' => [],
            ],
        ],

        'User' => [
            'navigation_sort' => 10,
            'table' => 'users',
            'fillable' => ['name', 'email', 'password', 'branch_id', 'role'],
            'relationships' => [
                'belongsTo' => [
                    'branch' => App\Models\Branch::class,
                ],
                'hasMany' => [],
            ],
        ],
    ],
];

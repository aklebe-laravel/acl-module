<?php

use Modules\Acl\app\Models\AclGroup;

return [
    // class of eloquent model
    'model'     => AclGroup::class,
    // update data if exists and data differ (default false)
    'update'    => false,
    // columns to check if data already exists (AND WHERE)
    'uniques'   => ['name'],
    // relations to update/create
    'relations' => [
        'res' => [
            // relation method which have to exists
            'method'  => 'aclResources',
            // column(s) to find specific #sync_relations items below
            'columns' => 'code',
            // delete items if not listed here (default: false)
            'delete'  => false,
        ],
    ],
    // data rows itself
    'data'      => [
        [
            'name'            => 'Admins',
            'description'     => 'Administrators',
            '#sync_relations' => [
                'res' => [
                    'admin',
                ],
            ],
        ],
        [
            'name'            => 'Site Owners',
            'description'     => 'Site Owners',
            '#sync_relations' => [
                'res' => [
                    'site_owner',
                    'admin',
                ],
            ],
        ],
        [
            'name'            => 'Supporters',
            'description'     => 'Support Team',
            '#sync_relations' => [
                'res' => [
                    'trader',
                    'staff',
                    'support',
                ],
            ],
        ],
        [
            'name'            => 'Developers',
            'description'     => 'Developer Team',
            '#sync_relations' => [
                'res' => [
                    'trader',
                    'staff',
                    'developer',
                ],
            ],
        ],
        [
            'name'            => 'Staff',
            'description'     => 'Staff/Contributors',
            '#sync_relations' => [
                'res' => [
                    'trader',
                    'staff',
                ],
            ],
        ],
        [
            'name'            => 'Marketing',
            'description'     => 'Marketing Team',
            '#sync_relations' => [
                'res' => [
                    'trader',
                    'staff',
                    'marketing',
                ],
            ],
        ],
        [
            'name'            => 'Designers',
            'description'     => 'Designer Team',
            '#sync_relations' => [
                'res' => [
                    'trader',
                    'staff',
                    'designer',
                ],
            ],
        ],
        [
            'name'            => 'Content Managers',
            'description'     => 'Allowed to manage content',
            '#sync_relations' => [
                'res' => [
                    'trader',
                    'staff',
                    'manage_content',
                ],
            ],
        ],
        [
            'name'            => 'Design Managers',
            'description'     => 'Allowed to manage design',
            '#sync_relations' => [
                'res' => [
                    'trader',
                    'staff',
                    'manage_design',
                ],
            ],
        ],
        [
            'name'            => 'User Managers',
            'description'     => 'Allowed to manage users',
            '#sync_relations' => [
                'res' => [
                    'trader',
                    'staff',
                    'manage_users',
                ],
            ],
        ],
        [
            'name'            => 'Puppets',
            'description'     => 'Not a human',
            '#sync_relations' => [
                'res' => [
                    'puppet',
                ],
            ],
        ],
        [
            'name'            => 'Testers',
            'description'     => 'Tester Team',
            '#sync_relations' => [
                'res' => [
                    'trader',
                    'staff',
                    'tester',
                ],
            ],
        ],
    ],
];

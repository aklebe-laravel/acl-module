<?php

use Modules\Acl\app\Models\AclGroup;
use Modules\Acl\app\Models\AclResource;

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
                    AclResource::RES_ADMIN,
                ],
            ],
        ],
        [
            'name'            => 'Site Owners',
            'description'     => 'Site Owners',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_SITE_OWNER,
                    AclResource::RES_ADMIN,
                ],
            ],
        ],
        [
            'name'            => 'Supporters',
            'description'     => 'Support Team',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_TRADER,
                    AclResource::RES_STAFF,
                    AclResource::RES_SUPPORT,
                ],
            ],
        ],
        [
            'name'            => 'Developers',
            'description'     => 'Developer Team',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_TRADER,
                    AclResource::RES_STAFF,
                    AclResource::RES_DEVELOPER,
                ],
            ],
        ],
        [
            'name'            => 'Staff',
            'description'     => 'Staff/Contributors',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_TRADER,
                    AclResource::RES_STAFF,
                ],
            ],
        ],
        [
            'name'            => 'Marketing',
            'description'     => 'Marketing Team',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_TRADER,
                    AclResource::RES_STAFF,
                    AclResource::RES_MARKETING,
                ],
            ],
        ],
        [
            'name'            => 'Designers',
            'description'     => 'Designer Team',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_TRADER,
                    AclResource::RES_STAFF,
                    AclResource::RES_DESIGNER,
                ],
            ],
        ],
        [
            'name'            => 'Content Managers',
            'description'     => 'Allowed to manage content',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_TRADER,
                    AclResource::RES_STAFF,
                    AclResource::RES_MANAGE_CONTENT,
                ],
            ],
        ],
        [
            'name'            => 'Design Managers',
            'description'     => 'Allowed to manage design',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_TRADER,
                    AclResource::RES_STAFF,
                    AclResource::RES_MANAGE_DESIGN,
                ],
            ],
        ],
        [
            'name'            => 'User Managers',
            'description'     => 'Allowed to manage users',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_TRADER,
                    AclResource::RES_STAFF,
                    AclResource::RES_MANAGE_USERS,
                ],
            ],
        ],
        [
            'name'            => AclGroup::GROUP_NON_HUMANS,
            'description'     => 'Not a human',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_NON_HUMAN,
                ],
            ],
        ],
        [
            'name'            => 'Testers',
            'description'     => 'Tester Team',
            '#sync_relations' => [
                'res' => [
                    AclResource::RES_TRADER,
                    AclResource::RES_STAFF,
                    AclResource::RES_TESTER,
                ],
            ],
        ],
    ],
];

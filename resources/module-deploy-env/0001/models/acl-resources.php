<?php

use Modules\Acl\app\Models\AclResource;

return [
    // class of eloquent model
    'model'   => AclResource::class,
    // update data if exists and data differ (default false)
    'update'  => false,
    // columns to check if data already exists (AND WHERE)
    'uniques' => ['code'],
    // data rows itself
    'data'    => [
        [
            'code'        => AclResource::RES_ADMIN,
            'name'        => 'Admin',
            'description' => 'Administrator'
        ],
        [
            'code'        => 'site_owner',
            'name'        => 'Site Owner',
            'description' => 'One of the site owners'
        ],
        [
            'code'        => AclResource::RES_SUPPORT,
            'name'        => 'Support',
            'description' => 'Support Team'
        ],
        [
            'code'        => AclResource::RES_DEVELOPER,
            'name'        => 'Developer',
            'description' => 'Developer Team'
        ],
        [
            'code'        => AclResource::RES_STAFF,
            'name'        => 'Staff',
            'description' => 'Staff/Contributor'
        ],
        [
            'code'        => 'marketing',
            'name'        => 'Marketing',
            'description' => 'Marketing Team'
        ],
        [
            'code'        => AclResource::RES_TRADER,
            'name'        => 'Trader',
            'description' => 'Trader'
        ],
        [
            'code'        => 'designer',
            'name'        => 'Designer',
            'description' => 'Designer Team'
        ],
        [
            'code'        => AclResource::RES_MANAGE_CONTENT,
            'name'        => 'Content Management',
            'description' => 'Content Management'
        ],
        [
            'code'        => AclResource::RES_MANAGE_DESIGN,
            'name'        => 'Design Management',
            'description' => 'Design Management'
        ],
        [
            'code'        => AclResource::RES_MANAGE_PRODUCTS,
            'name'        => 'Product Management',
            'description' => 'Product Management'
        ],
        [
            'code'        => AclResource::RES_MANAGE_USERS,
            'name'        => 'User Management',
            'description' => 'User Management'
        ],
        [
            'code'        => AclResource::RES_TESTER,
            'name'        => 'Tester',
            'description' => 'Tester Team'
        ],
        [
            'code'        => AclResource::RES_NON_HUMAN,
            'name'        => 'Puppet',
            'description' => 'Not a human'
        ]
    ]
];


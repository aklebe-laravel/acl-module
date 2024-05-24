<?php

return [
    // class of eloquent model
    "model"   => \Modules\Acl\app\Models\AclResource::class,
    // update data if exists and data differ (default false)
    "update"  => false,
    // columns to check if data already exists (AND WHERE)
    "uniques" => ["code"],
    // data rows itself
    "data"    => [
        [
            "code"        => "admin",
            "name"        => "Admin",
            "description" => "Administrator"
        ],
        [
            "code"        => "site_owner",
            "name"        => "Site Owner",
            "description" => "One of the site owners"
        ],
        [
            "code"        => "support",
            "name"        => "Support",
            "description" => "Support Team"
        ],
        [
            "code"        => "developer",
            "name"        => "Developer",
            "description" => "Developer Team"
        ],
        [
            "code"        => "staff",
            "name"        => "Staff",
            "description" => "Staff/Contributor"
        ],
        [
            "code"        => "marketing",
            "name"        => "Marketing",
            "description" => "Marketing Team"
        ],
        [
            "code"        => "trader",
            "name"        => "Trader",
            "description" => "Trader"
        ],
        [
            "code"        => "designer",
            "name"        => "Designer",
            "description" => "Designer Team"
        ],
        [
            "code"        => "manage_content",
            "name"        => "Content Management",
            "description" => "Content Management"
        ],
        [
            "code"        => "manage_design",
            "name"        => "Design Management",
            "description" => "Design Management"
        ],
        [
            "code"        => "manage_products",
            "name"        => "Product Management",
            "description" => "Product Management"
        ],
        [
            "code"        => "manage_users",
            "name"        => "User Management",
            "description" => "User Management"
        ],
        [
            "code"        => "tester",
            "name"        => "Tester",
            "description" => "Tester Team"
        ],
        [
            "code"        => "puppet",
            "name"        => "Puppet",
            "description" => "Not a human"
        ]
    ]
];


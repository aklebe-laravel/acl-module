<?php
return [
    'acl-resource' => [
        'data-table' => [
            // delete box
            'delete'     => [
                'title'   => 'Delete Resource',
                'content' => 'ask_delete_acl_resource',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'system-base::cancel',
                    'system-base::delete-item',
                ],
            ],
            'send-email' => [
                'title'   => 'Send Email',
                'content' => 'ask_send_email',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'system-base::cancel',
                    'website-base::send-email',
                ],
            ],
        ],

    ],
    'acl-group'    => [
        'data-table' => [
            // delete box
            'delete'     => [
                'title'   => 'Delete Group',
                'content' => 'ask_delete_acl_group',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'system-base::cancel',
                    'system-base::delete-item',
                ],
            ],
            'send-email' => [
                'title'   => 'Send Email',
                'content' => 'ask_send_email',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'system-base::cancel',
                    'website-base::send-email',
                ],
            ],
        ],
    ],
];

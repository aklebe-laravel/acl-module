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
                    'cancel',
                    'delete-item',
                ],
            ],
            'send-email' => [
                'title'   => 'Send Email',
                'content' => 'ask_send_email',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'cancel',
                    'send-email',
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
                    'cancel',
                    'delete-item',
                ],
            ],
            'send-email' => [
                'title'   => 'Send Email',
                'content' => 'ask_send_email',
                // constant names from defaultActions[] or closure
                'actions' => [
                    'cancel',
                    'send-email',
                ],
            ],
        ],
    ],
];

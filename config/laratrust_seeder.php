<?php

return [
    'role_structure' => [
        'super_admin' => [
            'admins' => 'c,r,u,d',
            'company' => 'c,r,u,d',
        ],
        'admin' => [],
        'vendor' => [
            'vendors' => 'c,r,u,d',
            'guides' => 'c,r,u,d',
            'drivers' => 'c,r,u,d',
            'supervisors' => 'c,r,u,d',
            'members' => 'c,r,u,d',
            'buses' => 'c,r,u,d',
            'trips' => 'c,r,u,d',
            'path' => 'c,r,u,d',
            'carrier' => 'c,r,u,d',
            'destination' => 'c,r,u,d',
        ],
        'userVendor' =>[],

    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];

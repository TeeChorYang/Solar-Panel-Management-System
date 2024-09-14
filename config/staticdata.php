<?php
return [
    'user' => [
        'types' => [
            'customer' => 'Customer',
            'supplier' => 'Supplier',
            'manager' => 'Manager',
            'admin' => 'Admin',
        ]
    ],

    'order' => [
        'request_status' => [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ],
        'order_status' => [
            'pending' => 'Pending',
            'in_delivery' => 'In Delivery',
            'shipped' => 'Shipped',
        ]
    ],

    'installation_status' => [
        'scheduled' => 'Scheduled',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    'maintanance_status' => [
        'scheduled' => 'Scheduled',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    'regex_malaysian_address' => '/^[a-zA-Z0-9\s,.-\/]+(?:\n[a-zA-Z0-9\s,.-\/]+)*\d{5},\s[a-zA-Z\s]+$/',
];

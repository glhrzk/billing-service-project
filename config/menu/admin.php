<?php

return [
    [
        'text' => 'Pengguna',
        'icon' => 'fa fa-user',
        'submenu' => [
            [
                'text' => 'List Pengguna',
                'url' => 'user.list',
            ],
            [
                'text' => 'Tambah Pengguna',
                'url' => 'user.add',
            ],
        ],
    ],
    [
        'text' => 'Paket',
        'icon' => 'fas fa-list',
        'submenu' => [
            [
                'text' => 'List Paket',
                'url' => 'package.list',
            ],
            [
                'text' => 'Tambah Paket',
                'url' => 'package.add',
            ],
        ],
    ],
    [
        'text' => 'Tagihan',
        'icon' => 'fas fa-money-bill',
        'submenu' => [
            [
                'text' => 'List Tagihan',
                'url' => 'bill.list',
            ],
            [
                'text' => 'Tambah Tagihan',
                'url' => 'bill.confirm',
            ],
        ],
    ],
    [
        'text' => 'Tiket',
        'icon' => 'fas fa-hands-helping	',
        'submenu' => [
            [
                'text' => 'List Tiket Bantuan',
                'url' => 'ticket.list',
            ],
        ],
    ]
];

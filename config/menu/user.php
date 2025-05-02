<?php

return [
    [
        'text' => 'Dashboard',
        'icon' => 'fas fa-tachometer-alt',
        'url' => 'user.dashboard',
    ],
    [
        'text' => 'Profile',
        'icon' => 'fa fa-user',
        'submenu' => [
            [
                'text' => 'Lihat Data',
                'route' => 'user.profile.show',
            ],
            [
                'text' => 'Ganti Password',
                'route' => 'user.profile.password.edit',
            ],
        ],
    ],
    [
        'text' => 'Paket Saya',
        'icon' => 'fas fa-list',
        'submenu' => [
            [
                'text' => 'Lihat Paket Aktif',
                'route' => 'user.package.show',
            ],
            [
                'text' => 'Histori Paket',
                'route' => 'user.package.history',
            ],
        ],
    ],
    [
        'text' => 'Tagihan Saya',
        'icon' => 'fas fa-money-bill',
        'submenu' => [
            [
                'text' => 'Lihat Tagihan',
                'route' => 'user.bill.show',
            ],
            [
                'text' => 'Riwayat Pembayaran',
                'route' => 'user.bill.history',
            ],
        ],
    ],
    [
        'text' => 'Bantuan',
        'icon' => 'fas fa-hands-helping',
        'submenu' => [
            [
                'text' => 'Buat Tiket Bantuan',
                'route' => 'user.ticket.create',
            ],
            [
                'text' => 'Daftar Tiket Saya',
                'route' => 'user.ticket.index',
            ],
//            [
//                'text' => 'FAQ • Tanya Jawab',
//                'url' => 'user.faq',
//            ],
        ],
    ]
];

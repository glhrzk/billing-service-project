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
                'url' => 'user.package.show',
            ],
            [
                'text' => 'Histori Paket',
                'url' => 'user.package.history',
            ],
        ],
    ],
    [
        'text' => 'Tagihan Saya',
        'icon' => 'fas fa-money-bill',
        'submenu' => [
            [
                'text' => 'Lihat Tagihan',
                'url' => 'user.bills.index',
            ],
            [
                'text' => 'Riwayat Pembayaran',
                'url' => 'user.payment.history',
            ],
        ],
    ],
    [
        'text' => 'Bantuan',
        'icon' => 'fas fa-hands-helping',
        'submenu' => [
            [
                'text' => 'Buat Tiket Bantuan',
                'url' => 'user.tickets.create',
            ],
            [
                'text' => 'Daftar Tiket Saya',
                'url' => 'user.tickets.index',
            ],
            [
                'text' => 'FAQ • Tanya Jawab',
                'url' => 'user.faq',
            ],
        ],
    ]
];

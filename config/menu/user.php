<?php

return [
    [
        'text' => 'Dashboard',
        'icon' => 'fas fa-tachometer-alt',
        'route'  => 'user.dashboard',
    ],

    ['header' => 'Profil & Akun'],
    [
        'text' => 'Profile',
        'icon' => 'fas fa-user',
        'submenu' => [
            [
                'text'  => 'Lihat Data',
                'route' => 'user.profile.show',
            ],
            [
                'text'  => 'Ganti Password',
                'route' => 'user.profile.password.edit',
            ],
        ],
    ],

    ['header' => 'Paket & Layanan'],
    [
        'text' => 'Paket Saya',
        'icon' => 'fas fa-box-open',
        'submenu' => [
            [
                'text'  => 'Lihat Paket Aktif',
                'route' => 'user.package.index',
            ],
            [
                'text'  => 'Histori Paket',
                'route' => 'user.package.history',
            ],
        ],
    ],

    ['header' => 'Keuangan'],
    [
        'text' => 'Tagihan Saya',
        'icon' => 'fas fa-file-invoice-dollar',
        'submenu' => [
            [
                'text'  => 'Lihat Tagihan',
                'route' => 'user.bills.index',
            ],
            [
                'text'  => 'Riwayat Pembayaran',
                'route' => 'user.bill.history',
            ],
        ],
    ],

    ['header' => 'Support'],
    [
        'text' => 'Bantuan',
        'icon' => 'fas fa-headset',
        'submenu' => [
            [
                'text'  => 'Buat Tiket Bantuan',
                'route' => 'user.ticket.create',
            ],
            [
                'text'  => 'Daftar Tiket Saya',
                'route' => 'user.ticket.index',
            ],
        ],
    ],
];

<?php

return [
    // DASHBOARD
    [
        'text' => 'Dashboard',
        'icon' => 'fas fa-tachometer-alt',
        'route' => 'admin.dashboard',
    ],

    // MANAJEMEN DATA MASTER
    ['header' => 'Manajemen Data'],

    [
        'text' => 'Manajemen User',
        'icon' => 'fas fa-users',
        'submenu' => [
            ['text' => 'Daftar User', 'route' => 'admin.users.index'],
            ['text' => 'Tambah User', 'route' => 'admin.users.create'],
        ],
    ],
    [
        'text' => 'Manajemen Paket',
        'icon' => 'fas fa-box',
        'submenu' => [
            ['text' => 'Daftar Paket', 'route' => 'admin.packages.index'],
            ['text' => 'Tambah Paket', 'route' => 'admin.packages.create'],
        ],
    ],

    // LAYANAN BERLANGGANAN
    ['header' => 'Layanan'],

    [
        'text' => 'Langganan User',
        'icon' => 'fas fa-link',
        'route' => 'admin.user-packages.index',
    ],

    // KEUANGAN
    ['header' => 'Keuangan'],

    [
        'text' => 'Tagihan & Pembayaran',
        'icon' => 'fas fa-file-invoice-dollar',
        'submenu' => [
            ['text' => 'Riwayat Tagihan', 'route' => 'admin.bills.index'],
            ['text' => 'Verifikasi Pembayaran', 'route' => 'admin.bills.verification'],
        ],
    ],

    [
        'text' => 'Keuangan',
        'icon' => 'fas fa-coins',
        'route' => 'admin.finances.index',
    ],

    // BANTUAN
    ['header' => 'Support'],

    [
        'text' => 'Tiket Bantuan',
        'icon' => 'fas fa-headset',
        'submenu' => [
            ['text' => 'Daftar Tiket', 'route' => 'admin.tickets.index'],
        ],
    ],

    // SISTEM
    ['header' => 'Sistem'],

    [
        'text' => 'Profil Admin',
        'icon' => 'fas fa-user-cog',
        'submenu' => [
            ['text' => 'Ganti Password', 'route' => 'admin.profile.password.edit'],
        ],
    ],
];

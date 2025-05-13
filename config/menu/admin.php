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
            ['text' => 'Daftar Tagihan', 'route' => 'admin.bills.index'],
            ['text' => 'Verifikasi Pembayaran', 'route' => 'admin.bills.verification'],
        ],
    ],
    [
        'text' => 'Pengeluaran',
        'icon' => 'fas fa-money-bill-wave',
        'submenu' => [
            ['text' => 'Riwayat Pengeluaran', 'route' => 'admin.expenses.index'],
            ['text' => 'Tambah Pengeluaran', 'route' => 'admin.expenses.create'],
        ],
    ],

    // BANTUAN
    ['header' => 'Support'],

    [
        'text' => 'Tiket Bantuan',
        'icon' => 'fas fa-headset',
        'submenu' => [
            ['text' => 'Daftar Tiket', 'route' => 'admin.tickets.index'],
            ['text' => 'Tiket Belum Dijawab', 'route' => 'admin.tickets.pending'],
        ],
    ],

    // LAPORAN
    ['header' => 'Laporan'],

    [
        'text' => 'Laporan & Riwayat',
        'icon' => 'fas fa-chart-line',
        'submenu' => [
            ['text' => 'Pendapatan', 'route' => 'admin.reports.incomes'],
            ['text' => 'Pengeluaran', 'route' => 'admin.reports.expenses'],
            ['text' => 'Tagihan Bulanan', 'route' => 'admin.reports.bills'],
        ],
    ],

    // SISTEM
    ['header' => 'Sistem'],

    [
        'text' => 'Profil Admin',
        'icon' => 'fas fa-user-cog',
        'submenu' => [
            ['text' => 'Lihat Profil', 'route' => 'admin.profile.show'],
            ['text' => 'Ganti Password', 'route' => 'admin.profile.password.edit'],
        ],
    ],
];

<?php

if (!function_exists('rupiah_label')) {
    /**
     * Format number as Indonesian Rupiah.
     */
    function rupiah_label(int|float|string $amount, bool $withPrefix = true): string
    {
        return ($withPrefix ? 'Rp ' : '') . number_format($amount, 0, ',', '.');
    }


    if (!function_exists('status_label')) {
        /**
         * Convert `active` / other value to a human‑readable label.
         */
        function status_label(string|bool $status): string
        {
            return $status === 'active' ? 'Aktif' : 'Tidak Aktif';
        }
    }

    if (!function_exists('status_badge')) {
        /**
         * Convert `active` / other value to a human‑readable label.
         */
        function status_badge(string|bool $status): string
        {
            return match ($status) {
                'active' => 'bg-green',
                'inactive' => 'bg-gray',
                default => 'Unknown',
            };
        }
    }


    if (!function_exists('payment_status_label')) {
        /**
         * Convert `paid` / other value to a human‑readable label.
         */
        function payment_status_label(string|bool $status): string
        {
            return match ($status) {
                'paid' => 'Lunas',
                'pending' => 'Sedang di proses',
                'unpaid' => 'Belum Lunas',
                'rejected' => 'Ditolak',
                default => 'Unknown',
            };
        }
    }

    if (!function_exists('payment_status_badge')) {
        /**
         * Convert `active` / other value to a human‑readable label.
         */
        function payment_status_badge(string|bool $status): string
        {
            return match ($status) {
                'paid' => 'bg-green',
                'pending' => 'bg-gray',
                'unpaid' => 'bg-red',
                'rejected' => 'bg-red',
                default => 'Unknown',
            };
        }
    }

    if (!function_exists('ticket_status_label')) {
        /**
         * Convert `open` / other value to a human‑readable label.
         */
        function ticket_status_label(string|bool $status): string
        {
            return match ($status) {
                'open' => 'Baru',
                'in_progress' => 'Sedang Proses',
                'resolved' => 'Selesai',
                'closed' => 'Ditutup',
            };
        }
    }

    if (!function_exists('ticket_status_badge')) {
        /**
         * Convert `open` / other value to a human‑readable label.
         */
        function ticket_status_badge(string|bool $status): string
        {
            return match ($status) {
                'open' => 'bg-blue',
                'in_progress' => 'bg-yellow',
                'resolved' => 'bg-green',
                'closed' => 'bg-secondary',
            };
        }
    }

    if (!function_exists('month_label')) {
        function month_label(string|\DateTimeInterface $date): string
        {
            return \Carbon\Carbon::parse($date)
                ->locale('id')            // switch to Indonesian
                ->translatedFormat('F Y'); // Januari, Februari, …
        }
    }
}

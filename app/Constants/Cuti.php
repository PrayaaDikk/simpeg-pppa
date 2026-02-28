<?php

namespace App\Constants;

class Cuti
{
    /**
     * Create a new class instance.
     */

    public const STATUS_COLOR = [
        'Menunggu' => 'bg-neutral-primary-soft text-heading ring-1 ring-inset ring-default',
        'Disetujui' => 'bg-success-soft text-fg-success-strong ring-1 ring-inset ring-success-subtle',
        'Perubahan' => 'bg-warning-soft text-fg-warning ring-1 ring-inset ring-warning-subtle',
        'Ditangguhkan' => 'bg-neutral-secondary-medium text-heading ring-1 ring-inset ring-default-medium',
        'Tidak disetujui' => 'bg-danger-soft text-fg-danger-strong ring-1 ring-inset ring-danger-subtle',
    ];

    public function __construct() {}
}

<?php

namespace App\Enums;

enum TxStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'قيد الانتظار',
            self::Completed => 'مكتمل',
            self::Failed => 'فشل',
        };
    }
    public function severity(): string
    {
        return match ($this) {
            self::Pending => 'info',
            self::Completed => 'success',
            self::Failed => 'danger',
        };
    }
}

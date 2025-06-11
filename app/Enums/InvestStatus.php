<?php

namespace App\Enums;

enum InvestStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'قيد الانتظار',
            self::Active => 'نشط',
            self::Cancelled => 'ملغي',
            self::Completed => 'مكتمل',
        };
    }
}

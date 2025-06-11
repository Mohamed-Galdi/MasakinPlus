<?php

namespace App\Enums;

enum DepositStatus: string
{
    case Pending = 'pending';
    case Succeeded = 'succeeded';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'قيد المعالجة',
            self::Succeeded => 'ناجح',
            self::Failed => 'فشل',
        };
    }
}

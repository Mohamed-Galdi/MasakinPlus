<?php

namespace App\Enums;

enum WithdrawRequestStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'قيد الانتظار',
            self::Approved => 'موافق عليه',
            self::Rejected => 'مرفوض',
            self::Completed => 'مكتمل',
        };
    }
}

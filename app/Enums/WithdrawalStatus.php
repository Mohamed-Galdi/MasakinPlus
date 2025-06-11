<?php

namespace App\Enums;

enum WithdrawalStatus: string
{
    case PendingApproval = 'pending_approval';
    case Processing = 'processing';
    case Paid = 'paid';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PendingApproval => 'بانتظار الموافقة',
            self::Processing => 'قيد المعالجة',
            self::Paid => 'مدفوع',
            self::Rejected => 'مرفوض',
        };
    }
}

<?php

namespace App\Enums;

enum TxType: string
{
    case Deposit = 'deposit';
    case Withdrawal = 'withdrawal';
    case Investment = 'investment';
    case IncomeShare = 'income_share';
    case PlatformFee = 'platform_fee';

    public function label(): string
    {
        return match ($this) {
            self::Deposit => 'إيداع',
            self::Withdrawal => 'سحب',
            self::Investment => 'استثمار',
            self::IncomeShare => 'نصيب من الدخل',
            self::PlatformFee => 'رسوم المنصة',
        };
    }
}

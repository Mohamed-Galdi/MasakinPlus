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
    public function icon(): string
    {
        return match ($this) {
            self::Deposit => 'pi pi-arrow-up',
            self::Withdrawal => 'pi pi-arrow-down',
            self::Investment => 'pi pi-arrow-down',
            self::IncomeShare => 'pi pi-arrow-up',
            self::PlatformFee => 'pi pi-arrow-up',
        };
    }
    public function color(): string
    {
        return match ($this) {
            self::Deposit => 'text-green-600',
            self::Withdrawal => 'text-red-600',
            self::Investment => 'text-red-600',
            self::IncomeShare => 'text-green-600',
            self::PlatformFee => 'text-green-600',
        };
    }
    public function sign(): string
    {
        return match ($this) {
            self::Deposit => '+',
            self::Withdrawal => '-',
            self::Investment => '-',
            self::IncomeShare => '+',
            self::PlatformFee => '+',
        };
    }
}

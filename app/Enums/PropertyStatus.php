<?php

namespace App\Enums;

enum PropertyStatus: string
{
    case Draft = 'draft';
    case InvestmentPending = 'investment_pending';
    case InvestmentRejected = 'investment_rejected';
    case OpenForInvestment = 'open_for_investment';
    case FundedUnderMaintenance = 'funded_under_maintenance';
    case VerifiedForRent = 'verified_for_rent';
    case ListedForRent = 'listed_for_rent';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'غير جاهز',
            self::InvestmentPending => 'قيد المراجعة للاستثمار',
            self::InvestmentRejected => 'تم رفض طلب الاستثمار',
            self::OpenForInvestment => 'متاح للاستثمار',
            self::FundedUnderMaintenance => 'تم التمويل - قيد التجهيز',
            self::VerifiedForRent => 'تم التحقق - جاهز للإيجار',
            self::ListedForRent => 'مدرج للإيجار',
        };
    }

    public static function labels(): array
    {
        return array_map(fn($status) => $status->label(), self::cases());
    }
}

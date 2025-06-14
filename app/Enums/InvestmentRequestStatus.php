<?php

namespace App\Enums;

enum InvestmentRequestStatus: string
{
    case Pending = 'pending';
    case OfferSent = 'offer_sent';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'قيد المراجعة',
            self::OfferSent => 'تم إرسال العرض',
            self::Approved => 'تم القبول',
            self::Rejected => 'تم الرفض',
        };
    }

    public static function labels(): array
    {
        return array_map(fn($status) => $status->label(), self::cases());
    }

    public static function labelFor(string $value): string
    {
        return self::tryFrom($value)?->label() ?? 'غير معروف';
    }

    public static function options(): array
    {
        return array_map(fn($status) => [
            'label' => $status->label(),
            'value' => $status->value,
        ], self::cases());
    }
}

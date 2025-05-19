<?php

namespace App\Enums;

enum UserType: string
{
    case Admin = 'admin';
    case Owner = 'owner';
    case Investor = 'investor';
    case Tenant = 'tenant';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'مشرف',
            self::Owner => 'مالك',
            self::Investor => 'مستثمر',
            self::Tenant => 'مستأجر',
        };
    }

    public static function labels(): array
    {
        return array_map(fn ($type) => $type->label(), self::cases());
    }

    public static function labelFor(string $value): string
    {
        return self::tryFrom($value)?->label() ?? 'غير معروف';
    }

    public static function options(): array
    {
        return array_map(fn ($type) => [
            'label' => $type->label(),
            'value' => $type->value,
        ], self::cases());
    }

    public static function casesExcluding(UserType ...$excluded): array
    {
        return array_filter(self::cases(), fn ($case) => ! in_array($case, $excluded));
    }

    public static function optionsExcluding(UserType ...$excluded): array
    {
        return array_map(fn ($type) => [
            'label' => $type->label(),
            'value' => $type->value,
        ], self::casesExcluding(...$excluded));
    }
}

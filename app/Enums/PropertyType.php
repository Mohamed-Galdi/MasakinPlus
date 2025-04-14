<?php

namespace App\Enums;

enum PropertyType: string
{
    case Apartment = 'apartment';
    case Villa = 'villa';
    case Studio = 'studio';
    case Office = 'office';
    case House = 'house';
    case Building = 'building';
    case Warehouse = 'warehouse';
    case Commercial = 'commercial';
    case Land = 'land';

    public function label(): string
    {
        return match ($this) {
            self::Apartment => 'شقة',
            self::Villa => 'فيلا',
            self::Studio => 'استوديو',
            self::Office => 'مكتب',
            self::House => 'منزل',
            self::Building => 'مبنى',
            self::Warehouse => 'مستودع',
            self::Commercial => 'عقار تجاري',
            self::Land => 'أرض',
        };
    }

    public static function labels(): array
    {
        return array_map(fn($type) => $type->label(), self::cases());
    }

    public static function options(): array
    {
        return array_map(fn($type) => [
            'label' => $type->label(),
            'value' => $type->value,
        ], self::cases());
    }
}

<?php

namespace App\Enums;

enum PageSectionType: string
{
    case HERO = 'hero';

    public function label(): string
    {
        return match ($this) {
            self::HERO => 'Hero Section',
        };
    }
}

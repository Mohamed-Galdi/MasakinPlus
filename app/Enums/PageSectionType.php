<?php

namespace App\Enums;

enum PageSectionType: string
{
    case HERO = 'hero';
    case USERS_CARDS = 'users-cards';
    case FEATURES = 'features';

    public function label(): string
    {
        return match ($this) {
            self::HERO => 'Hero Section',
            self::USERS_CARDS => 'Users Cards Section',
            self::FEATURES => 'Features Section',
        };
    }
}

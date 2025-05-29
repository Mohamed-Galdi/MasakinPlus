<?php

namespace App\Enums;

enum PageSectionType: string
{
    case HERO = 'hero';
    case USERS_CARDS = 'users-cards';
    case FEATURES = 'features';
    case OUR_NUMBERS = 'our-numbers';
    case FAQS = 'faqs';

    public function label(): string
    {
        return match ($this) {
            self::HERO => 'Hero Section',
            self::USERS_CARDS => 'Users Cards Section',
            self::FEATURES => 'Features Section',
            self::OUR_NUMBERS => 'Our Numbers Section',
            self::FAQS => 'FAQs Section',
        };
    }
}

<?php

namespace App\Enums;

enum PageSectionType: string
{
    case HERO = 'hero';
    case ABOUT_HERO = 'about-hero';
    case USERS_CARDS = 'users-cards';
    case FEATURES = 'features';
    case OUR_NUMBERS = 'our-numbers';
    case FAQS = 'faqs';
    case MISSION = 'mission';
    case HOW_IT_WORKS = 'how-it-works';
    case TIMELINE = 'timeline';
    case TEAM = 'team';
    case CONTACT = 'contact';

    public function label(): string
    {
        return match ($this) {
            self::HERO => 'Hero Section',
            self::ABOUT_HERO => 'About Hero Section',
            self::USERS_CARDS => 'Users Cards Section',
            self::FEATURES => 'Features Section',
            self::OUR_NUMBERS => 'Our Numbers Section',
            self::FAQS => 'FAQs Section',
            self::MISSION => 'Mission Section',
            self::HOW_IT_WORKS => 'How It Works Section',
            self::TIMELINE => 'Timeline Section',
            self::TEAM => 'Team Section',
            self::CONTACT => 'Contact Section',
        };
    }
}

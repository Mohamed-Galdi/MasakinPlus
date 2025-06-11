<?php

namespace App\Providers;

use App\Models\InvestmentOffer;
use App\Models\InvestmentRequest;
use App\Models\Property;
use App\Policies\InvestmentOfferPolicy;
use App\Policies\InvestmentRequestPolicy;
use App\Policies\PropertyOwnerPolicy;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        Model::shouldBeStrict();
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->view('mail.email-verification', ['actionUrl' => $url]);
        });
        Gate::policy(Property::class, PropertyOwnerPolicy::class);
        Gate::policy(InvestmentRequest::class, InvestmentRequestPolicy::class);
        Gate::policy(InvestmentOffer::class, InvestmentOfferPolicy::class);
        User::observe(UserObserver::class);
    }
}

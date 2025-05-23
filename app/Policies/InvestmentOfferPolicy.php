<?php

namespace App\Policies;

use App\Models\InvestmentOffer;
use Illuminate\Auth\Access\Response;
use App\Models\User;

class InvestmentOfferPolicy
{
    public function acceptOffer(User $user, InvestmentOffer $investmentOffer): Response
    {
        return $user->id === $investmentOffer->property->owner_id
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    public function rejectOffer(User $user, InvestmentOffer $investmentOffer): Response
    {
        return $user->id === $investmentOffer->property->owner_id
            ? Response::allow()
            : Response::denyWithStatus(404);
    }
}

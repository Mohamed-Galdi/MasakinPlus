<?php

namespace App\Policies;

use App\Models\InvestmentRequest;
use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InvestmentRequestPolicy
{
    public function view(User $user, InvestmentRequest $investmentRequest): Response
    {
        return $user->id === $investmentRequest->owner->id
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    public function create(User $user, Property $property): Response
    {
        return $user->id === $property->owner_id
            ? Response::allow()
            : Response::denyWithStatus(403, 'لا يحق لك إنشاء طلب استثمار لهذه الملكية.');
    }

   
}

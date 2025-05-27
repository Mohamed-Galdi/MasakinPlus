<?php

namespace App\Policies;

use App\Enums\PropertyStatus;
use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropertyOwnerPolicy
{
    public function show(User $user, Property $property): Response
    {
        return $user->id === $property->owner_id
            ? Response::allow()
            : Response::denyWithStatus(404);
    }

    public function update(User $user, Property $property): Response
    {
        // dd($user->id, $property->owner_id, $property->status, PropertyStatus::Draft);
        return $user->id === $property->owner_id && $property->status === PropertyStatus::Draft
            ? Response::allow()
            : Response::denyWithStatus(404);
    }
}

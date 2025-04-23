<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class PropertyOwnerPolicy
{

    public function update(User $user, Property $property): Response
    {
        return $user->id === $property->owner_id
            ? Response::allow()
            : Response::denyWithStatus(404);
    }
}

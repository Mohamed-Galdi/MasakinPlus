<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereNot('type', UserType::Admin->value)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->userType, function ($query) use ($request) {
                $query->where('type', $request->userType);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $usersTypes = array_values(UserType::optionsExcluding(UserType::Admin));
        $search = $request->get('search') ?? '';
        $userType = $request->get('userType') ?? '';

        return inertia('Admin/Users/index', compact('users', 'usersTypes', 'search', 'userType'));
    }

    public function view($id)
    {
        $user = User::whereNot('type', UserType::Admin->value)->findOrFail($id);
        return inertia('Admin/Users/view', compact('user'));
    }
}

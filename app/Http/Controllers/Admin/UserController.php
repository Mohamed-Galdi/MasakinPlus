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
            ->where('is_active', true)
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

    public function suspended(Request $request)
    {
        $users = User::whereNot('type', UserType::Admin->value)
            ->where('is_active', false)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->userType, function ($query) use ($request) {
                $query->where('type', $request->userType);
            })
            ->orderBy('suspended_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $usersTypes = array_values(UserType::optionsExcluding(UserType::Admin));
        $search = $request->get('search') ?? '';
        $userType = $request->get('userType') ?? '';

        return inertia('Admin/Users/suspended', compact('users', 'usersTypes', 'search', 'userType'));
    }

    public function view($id)
    {
        $user = User::whereNot('type', UserType::Admin->value)->findOrFail($id);
        return inertia('Admin/Users/view', compact('user'));
    }

    public function suspend(Request $request, $id)
    {
        $request->validate([
            'suspension_reason' => 'string|max:255|nullable',
        ]);
        $user = User::findOrFail($id);
        $user->is_active = false;
        $user->suspension_reason = $request->suspension_reason;
        $user->suspended_at = now();
        $user->save();
        return back();
    }

    public function reactivate($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = true;
        $user->suspension_reason = null;
        $user->suspended_at = null;
        $user->save();
        return back();
    }
}

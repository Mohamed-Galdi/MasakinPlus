<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::whereNot('type', UserType::Admin->value)->paginate(12);
        return inertia('Admin/Users', compact('users'));
    }
}

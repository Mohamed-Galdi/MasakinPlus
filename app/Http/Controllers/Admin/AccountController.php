<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return inertia('Admin/Account/Index', compact('user'));
    }

    public function changeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $admin = auth()->user();

        $tempFile = TempFile::where('folder', $request->image)->first();
        if ($tempFile) {
            $imgPath = 'users_images/';

            Storage::disk('public')->move($tempFile->path, $imgPath.$tempFile->name);
            $admin->image = '/storage/'.$imgPath.$tempFile->name;

            Storage::disk('public')->deleteDirectory('TempFiles/'.$tempFile->folder);
            $tempFile->delete();
        }
        $admin->save();

        return redirect()->route('admin.account.index');
    }

    public function editAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $admin = auth()->user();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();

        return redirect()->route('admin.account.index');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password',
        ]);

        // compare old password
        $admin = auth()->user();
        if (! Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()->withErrors(['password' => 'Password is incorrect']);
        }

        $admin->password = bcrypt($request->password);
        // $admin->session_id = null;
        $admin->save();

        Auth::logout();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Common;

use App\Models\TempFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class UserAccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return inertia('Common/account', compact('user'));
    }
    public function changeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $user = auth()->user();

        $tempFile = TempFile::where('folder', $request->image)->first();
        if ($tempFile) {
            $imgPath = 'users_images/';

            Storage::disk('public')->move($tempFile->path, $imgPath . $tempFile->name);
            $user->image = '/storage/' . $imgPath . $tempFile->name;

            Storage::disk('public')->deleteDirectory('TempFiles/' . $tempFile->folder);
            $tempFile->delete();
        }
        $user->save();

        return redirect()->route('account.index');
    }

    public function editUsername(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->save();

        return redirect()->route('account.index');
    }
    
    public function editEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = auth()->user();
        $user->email = $request->email;
        $user->save();

        return redirect()->route('account.index');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password',
        ]);

        // compare old password
        $user = auth()->user();
        if (! Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'كلمة المرور غير صحيحة']);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        Auth::logout();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'current-password' => ['required'],
            'new-password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'new-password_confirmation' => 'New password and confirmation do not match.',
        ]);

        if (!Hash::check($request->input("current-password"), Auth::user()->password)) {
            return response()->json(["message" => "Current password is incorrect."], 422);
        }

        Auth::user()->update([
            'password' => Hash::make($request->input('new-password')),
        ]);

        $redirectRoute = Auth::user()->is_admin == 1 ? 'admin.home' : 'home';

        return redirect()->route($redirectRoute)->with('success', 'Password changed successfully!');
    }
}


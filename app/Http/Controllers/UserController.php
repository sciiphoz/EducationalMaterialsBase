<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showProfile()
    {
        return view('pages.profilepage');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'login' => 'required|string|max:255|unique:users,login,'.$user->id_user.',id_user',
            'email' => 'required|email|unique:users,email,'.$user->id_user.',id_user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'login' => $validated['login'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);
        return back()->with('success', 'Профиль обновлён');
    }
}
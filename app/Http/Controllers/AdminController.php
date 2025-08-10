<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Material;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // public function dashboard()
    // {
    //     $this->authorize('viewAny', User::class);
        
    //     $users = User::with('role')->paginate(10);
    //     $materials = Material::with('user')->paginate(10);
        
    //     return view('admin.dashboard', compact('users', 'materials'));
    // }

    // public function indexUsers()
    // {
    //     $this->authorize('viewAny', User::class);
    //     $users = User::with('role')->paginate(10);
    //     return view('admin.users.index', compact('users'));
    // }

    // public function createUser()
    // {
    //     $this->authorize('create', User::class);
    //     return view('admin.users.create');
    // }

    // public function storeUser(Request $request)
    // {
    //     $this->authorize('create', User::class);

    //     $validated = $request->validate([
    //         'login' => 'required|string|max:255|unique:users',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|string|min:8',
    //         'id_role' => 'required|exists:roles,id_role',
    //     ]);

    //     User::create([
    //         'login' => $validated['login'],
    //         'email' => $validated['email'],
    //         'password' => bcrypt($validated['password']),
    //         'id_role' => $validated['id_role'],
    //     ]);

    //     return redirect()->route('admin.users.index');
    // }

    // public function editUser(User $user)
    // {
    //     $this->authorize('update', $user);
    //     return view('admin.users.edit', compact('user'));
    // }

    // public function updateUser(Request $request, User $user)
    // {
    //     $this->authorize('update', $user);

    //     $validated = $request->validate([
    //         'login' => 'required|string|max:255|unique:users,login,'.$user->id_user.',id_user',
    //         'email' => 'required|email|unique:users,email,'.$user->id_user.',id_user',
    //         'password' => 'nullable|string|min:8',
    //         'id_role' => 'required|exists:roles,id_role',
    //     ]);

    //     $data = [
    //         'login' => $validated['login'],
    //         'email' => $validated['email'],
    //         'id_role' => $validated['id_role'],
    //     ];

    //     if (!empty($validated['password'])) {
    //         $data['password'] = bcrypt($validated['password']);
    //     }

    //     $user->update($data);
    //     return redirect()->route('admin.users.index');
    // }

    // public function destroyUser(User $user)
    // {
    //     $this->authorize('delete', $user);
    //     $user->delete();
    //     return redirect()->route('admin.users.index');
    // }

    // public function destroyMaterial(Material $material)
    // {
    //     $this->authorize('forceDelete', $material);
    //     $material->delete();
    //     return back()->with('success', 'Материал удалён');
    // }
}
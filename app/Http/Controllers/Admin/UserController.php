<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return view('admin.users.form', ['item' => new User(), 'roles' => Role::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'string', \App\Support\Passwords::rule()],
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => \App\Support\Sanitize::name($data['name']),
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $user->assignRole($data['role']);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('admin.users.form', ['item' => $user, 'roles' => Role::orderBy('name')->get()]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', \App\Support\Passwords::rule()],
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => \App\Support\Sanitize::name($data['name']),
            'email' => $data['email'],
            ...(filled($data['password']) ? ['password' => $data['password']] : []),
        ]);
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        return view('admin.users.index', [
            'users' => User::query()->with('roles')->latest()->paginate(12),
            'roles' => Role::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $user->forceFill([
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
        ])->save();

        $user->assignRole($validated['role']);

        return Redirect::route('admin.users.index')->with('status', 'user-created');
    }

    public function edit(Request $request, User $user): View
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        $user->load('roles');

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->boolean('email_verified')) {
            $user->forceFill(['email_verified_at' => $user->email_verified_at ?? now()])->save();
        }

        $user->syncRoles([$validated['role']]);

        return Redirect::route('admin.users.edit', $user)->with('status', 'user-updated');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()?->hasRole('admin'), 403);

        if ($user->id === $request->user()->id) {
            return redirect()->route('admin.users.index')->with('error', 'cannot-delete-self');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'user-deleted');
    }
}

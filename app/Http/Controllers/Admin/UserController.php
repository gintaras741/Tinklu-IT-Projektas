<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(20);
        $roles = Role::cases();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user): View
    {
        $roles = Role::cases();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Remove password fields if not provided
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user): RedirectResponse
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Check if this is the last admin
        if ($user->isAdmin() && User::where('role', Role::Admin)->count() === 1) {
            return back()->with('error', 'Cannot delete the last admin user.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted successfully.');
    }
}

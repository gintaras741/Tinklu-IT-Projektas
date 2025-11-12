@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Edit User</h1>
            <p class="mt-1 text-gray-600 text-sm">Update user information and permissions.</p>
        </div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST"
            class="space-y-6 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="space-y-1">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                    class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error('name')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="space-y-1">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                    class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error('email')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role -->
            <div class="space-y-1">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select id="role" name="role"
                    class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}" @selected(old('role', $user->role->value) === $role->value)>
                            {{ ucfirst($role->value) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
                @if ($user->isAdmin() && \App\Models\User::where('role', \App\Enums\Role::Admin)->count() === 1)
                    <p class="text-amber-600 text-xs mt-1">
                        <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        This is the last admin account. Be careful when changing roles.
                    </p>
                @endif
            </div>

            <!-- Password Section -->
            <div class="border-t border-gray-200 pt-6 space-y-4">
                <h3 class="text-sm font-medium text-gray-700">Change Password (Optional)</h3>
                <p class="text-xs text-gray-500">Leave blank to keep the current password.</p>

                <!-- New Password -->
                <div class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" id="password" name="password"
                        class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password')
                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                    @enderror
                    <p class="text-xs text-gray-500">Minimum 8 characters</p>
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New
                        Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>

        <!-- User Info Card -->
        <div class="mt-6 bg-gray-50 rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-medium text-gray-700 mb-3">User Information</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">User ID:</dt>
                    <dd class="font-medium text-gray-900">{{ $user->id }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Account Created:</dt>
                    <dd class="font-medium text-gray-900">{{ $user->created_at->format('F d, Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Last Updated:</dt>
                    <dd class="font-medium text-gray-900">{{ $user->updated_at->format('F d, Y') }}</dd>
                </div>
                @if ($user->email_verified_at)
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Email Verified:</dt>
                        <dd class="font-medium text-green-600">
                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Yes
                        </dd>
                    </div>
                @else
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Email Verified:</dt>
                        <dd class="font-medium text-amber-600">Not verified</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
@endsection

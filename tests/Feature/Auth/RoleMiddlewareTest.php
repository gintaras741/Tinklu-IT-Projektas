<?php

use App\Enums\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

describe('role middleware', function () {
    beforeEach(function () {
        // Define a temporary route for testing
        Route::middleware(['auth', 'role:admin'])
            ->get('/admin-only', fn () => 'ok');
    });

    it('denies access to non-admin users', function () {
        $user = User::factory()->create(['role' => Role::User->value]);
        $this->actingAs($user);

        $response = $this->get('/admin-only');
        $response->assertForbidden();
    });

    it('allows access to admins', function () {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $response = $this->get('/admin-only');
        $response->assertOk();
        $response->assertSee('ok');
    });

    it('allows access to worker when permitted', function () {
        // Define a worker-or-admin route
        Route::middleware(['auth', 'role:admin,worker'])
            ->get('/staff-only', fn () => 'ok');

        $worker = User::factory()->worker()->create();
        $this->actingAs($worker);

        $response = $this->get('/staff-only');
        $response->assertOk();
    });
});

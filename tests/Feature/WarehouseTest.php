<?php

use App\Enums\PartType;
use App\Models\BicyclePart;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

it('denies access to guests', function () {
    $this->get('/warehouse')->assertRedirect('/login');
});

it('denies access to regular users', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->get('/warehouse')->assertForbidden();
});

it('allows admins to view the warehouse', function () {
    $admin = User::factory()->admin()->create();
    $this->actingAs($admin)->get('/warehouse')->assertOk();
});

it('allows workers to view the warehouse', function () {
    $worker = User::factory()->worker()->create();
    $this->actingAs($worker)->get('/warehouse')->assertOk();
});

it('can create a bicycle part', function () {
    $admin = User::factory()->admin()->create();
    Storage::fake('public');

    $payload = [
        'type' => PartType::Frame->value,
        'name' => 'Test Frame',
        'amount' => 5,
    ];

    $this->actingAs($admin)
        ->post(route('warehouse.store'), $payload)
        ->assertRedirect(route('warehouse.index'));

    $this->assertDatabaseHas('bicycle_parts', [
        'type' => PartType::Frame->value,
        'name' => 'Test Frame',
        'amount' => 5,
    ]);
});

it('can update a bicycle part', function () {
    $worker = User::factory()->worker()->create();
    $part = BicyclePart::create([
        'type' => PartType::Fork->value,
        'name' => 'Test Fork',
        'amount' => 2,
    ]);

    $this->actingAs($worker)
        ->put(route('warehouse.update', $part), [
            'type' => PartType::Fork->value,
            'name' => 'Test Fork Updated',
            'amount' => 3,
        ])->assertRedirect(route('warehouse.index'));

    $this->assertDatabaseHas('bicycle_parts', [
        'id' => $part->id,
        'name' => 'Test Fork Updated',
        'amount' => 3,
    ]);
});

it('can delete a bicycle part', function () {
    $admin = User::factory()->admin()->create();
    $part = BicyclePart::create([
        'type' => PartType::Headset->value,
        'name' => 'Headset X',
        'amount' => 1,
    ]);

    $this->actingAs($admin)
        ->delete(route('warehouse.destroy', $part))
        ->assertRedirect(route('warehouse.index'));

    $this->assertDatabaseMissing('bicycle_parts', [
        'id' => $part->id,
    ]);
});

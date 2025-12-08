<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password123'),
            'role' => Role::User->value,
        ]);

        User::create([
            'name' => 'Worker',
            'email' => 'worker@gmail.com',
            'password' => Hash::make('password123'),
            'role' => Role::Worker->value,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => Role::Admin->value,
        ]);
    }
}

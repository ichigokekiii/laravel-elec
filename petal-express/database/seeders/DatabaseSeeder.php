<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // demo login for class: test@test.com / 123
        User::query()->updateOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'profile',
                'password' => Hash::make('123'),
                'email_verified_at' => now(),
            ]
        );
    }
}

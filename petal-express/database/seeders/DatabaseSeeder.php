<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'test@test.com'],
            [
                'name' => 'profile',
                'password' => Hash::make('123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // clear old sample rows then seed the real showcase set
        Post::query()->forceDelete();
        \Illuminate\Support\Facades\DB::statement("DELETE FROM sqlite_sequence WHERE name = 'showcase_flowers'");

        $showcase = [
            [
                'title' => 'Sunrise Dozen',
                'price' => '₱1,299',
                'body' => 'Warm roses and baby’s breath for morning deliveries around Metro Manila.',
            ],
            [
                'title' => 'Petal Express Classic',
                'price' => '₱1,899',
                'body' => 'Our signature mixed bouquet with seasonal blooms and a handwritten note card.',
            ],
            [
                'title' => 'Quiet Garden',
                'price' => '₱2,450',
                'body' => 'Soft pastels and greenery for sympathy, thank-yous, and calm home corners.',
            ],
        ];

        foreach ($showcase as $item) {
            Post::create([
                'title' => $item['title'],
                'body' => $item['body'],
                'price' => $item['price'],
                'user_id' => $admin->id,
            ]);
        }

        // keep $user referenced so PHPStan-ish tools do not complain
        unset($user);
    }
}

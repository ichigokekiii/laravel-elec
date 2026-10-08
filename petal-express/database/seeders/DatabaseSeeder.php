<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
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

        $categoryNames = ['Birthday', 'Anniversary', 'Sympathy', 'Thank You', 'Just Because'];
        $categories = [];

        foreach ($categoryNames as $name) {
            $categories[$name] = Category::query()->updateOrCreate(['name' => $name]);
        }

        // wipe showcase rows (including trash) so re-seed stays at 100
        Post::withTrashed()->forceDelete();
        DB::statement("DELETE FROM sqlite_sequence WHERE name = 'showcase_flowers'");

        $showcase = [
            [
                'title' => 'Sunrise Dozen',
                'price' => '₱1,299',
                'body' => 'Warm roses and baby’s breath for morning deliveries around Metro Manila.',
                'category' => 'Birthday',
            ],
            [
                'title' => 'Petal Express Classic',
                'price' => '₱1,899',
                'body' => 'Our signature mixed bouquet with seasonal blooms and a handwritten note card.',
                'category' => 'Anniversary',
            ],
            [
                'title' => 'Quiet Garden',
                'price' => '₱2,450',
                'body' => 'Soft pastels and greenery for sympathy, thank-yous, and calm home corners.',
                'category' => 'Sympathy',
            ],
        ];

        foreach ($showcase as $item) {
            Post::create([
                'title' => $item['title'],
                'body' => $item['body'],
                'price' => $item['price'],
                'user_id' => $admin->id,
                'category_id' => $categories[$item['category']]->id,
            ]);
        }

        // 3 named bouquets + 97 from the factory = 100
        Post::factory()->count(97)->create([
            'user_id' => $admin->id,
        ]);
    }
}

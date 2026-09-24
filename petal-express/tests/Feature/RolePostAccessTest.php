<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePostAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_post_list_and_user_cannot(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin)
            ->get(route('admin.posts.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('admin.posts.index'))
            ->assertForbidden();
    }

    public function test_user_can_show_post_and_admin_cannot_open_submit_form(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create(['role' => 'user']);

        $post = Post::create([
            'title' => 'Test bouquet note',
            'body' => 'Body text for the test post.',
            'price' => '₱999',
            'user_id' => $admin->id,
        ]);

        $this->actingAs($user)
            ->get(route('posts.show', $post))
            ->assertOk()
            ->assertSee('Test bouquet note');

        $this->actingAs($user)
            ->get(route('posts.submit'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('posts.submit'))
            ->assertForbidden();
    }

    public function test_admin_can_store_a_post(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'New admin post',
                'body' => 'Created through the store action.',
                'price' => '₱1,500',
            ])
            ->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('showcase_flowers', [
            'title' => 'New admin post',
            'price' => '₱1,500',
            'user_id' => $admin->id,
        ]);
    }

    public function test_showcase_uses_posts_from_the_database(): void
    {
        $admin = User::factory()->admin()->create();

        Post::create([
            'title' => 'Sunrise Dozen',
            'body' => 'Warm roses for morning deliveries.',
            'price' => '₱1,299',
            'user_id' => $admin->id,
        ]);

        $this->get(route('bouquets.index'))
            ->assertOk()
            ->assertSee('Sunrise Dozen')
            ->assertSee('₱1,299');
    }

    public function test_user_static_submit_does_not_insert(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->post(route('posts.submit.store'), [
                'title' => 'Should not save',
                'body' => 'Static form only.',
            ])
            ->assertRedirect(route('posts.submit'));

        $this->assertDatabaseMissing('showcase_flowers', [
            'title' => 'Should not save',
        ]);
    }
}

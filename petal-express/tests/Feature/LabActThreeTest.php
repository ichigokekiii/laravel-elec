<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LabActThreeTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_category_does_not_save(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->from(route('admin.posts.create'))
            ->post(route('admin.posts.store'), [
                'title' => 'No category bouquet',
                'body' => 'Missing the category.',
                'price' => '900',
                'image' => UploadedFile::fake()->image('bouquet.jpg'),
            ])
            ->assertRedirect(route('admin.posts.create'))
            ->assertSessionHasErrors('category_id');

        $this->assertDatabaseMissing('showcase_flowers', [
            'title' => 'No category bouquet',
        ]);
    }

    public function test_soft_delete_hides_the_bouquet_and_lists_it_in_trash(): void
    {
        $admin = User::factory()->admin()->create();
        $post = $this->bouquet($admin, 'Hide Me');

        $this->actingAs($admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertSoftDeleted('showcase_flowers', ['id' => $post->id]);

        $this->get(route('bouquets.index'))
            ->assertOk()
            ->assertDontSee('Hide Me');

        $this->actingAs($admin)
            ->get(route('admin.posts.trash'))
            ->assertOk()
            ->assertSee('Hide Me');
    }

    public function test_restore_puts_the_bouquet_back_on_the_showcase(): void
    {
        $admin = User::factory()->admin()->create();
        $post = $this->bouquet($admin, 'Bring Me Back');
        $post->delete();

        $this->actingAs($admin)
            ->patch(route('admin.posts.restore', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertNotSoftDeleted('showcase_flowers', ['id' => $post->id]);

        $this->get(route('bouquets.index'))
            ->assertOk()
            ->assertSee('Bring Me Back');
    }

    public function test_force_delete_removes_the_row(): void
    {
        $admin = User::factory()->admin()->create();
        $post = $this->bouquet($admin, 'Gone Forever');
        $post->delete();

        $this->actingAs($admin)
            ->delete(route('admin.posts.force', $post))
            ->assertRedirect(route('admin.posts.trash'));

        $this->assertDatabaseMissing('showcase_flowers', [
            'id' => $post->id,
        ]);
    }

    public function test_user_cannot_delete_a_bouquet(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $post = $this->bouquet($admin, 'Stay Put');

        $this->actingAs($user)
            ->delete(route('admin.posts.destroy', $post))
            ->assertForbidden();

        $this->assertNotSoftDeleted('showcase_flowers', ['id' => $post->id]);
    }

    public function test_showcase_paginates_nine_per_page(): void
    {
        $admin = User::factory()->admin()->create();

        foreach (range(1, 10) as $number) {
            $this->bouquet($admin, sprintf('Paged bouquet %02d', $number));
        }

        $this->get(route('bouquets.index'))
            ->assertOk()
            ->assertSee('Paged bouquet 10')
            ->assertSee('Paged bouquet 02')
            ->assertDontSee('Paged bouquet 01');

        $this->get(route('bouquets.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Paged bouquet 01')
            ->assertDontSee('Paged bouquet 10');
    }

    public function test_category_filter_shows_only_that_category(): void
    {
        $admin = User::factory()->admin()->create();
        $birthday = Category::query()->create(['name' => 'Birthday']);
        $sympathy = Category::query()->create(['name' => 'Sympathy']);

        $this->bouquet($admin, 'Birthday Only', $birthday);
        $this->bouquet($admin, 'Sympathy Only', $sympathy);

        $this->get(route('bouquets.index', ['category' => $birthday->id]))
            ->assertOk()
            ->assertSee('Birthday Only')
            ->assertDontSee('Sympathy Only');
    }

    public function test_seeder_creates_one_hundred_bouquets(): void
    {
        $this->seed();

        $this->assertSame(100, Post::count());
        $this->assertSame(5, Category::count());
    }

    private function bouquet(User $admin, string $title, ?Category $category = null): Post
    {
        $category ??= Category::query()->firstOrCreate(['name' => 'Birthday']);

        return Post::create([
            'title' => $title,
            'body' => 'A test bouquet.',
            'price' => '₱1,000',
            'user_id' => $admin->id,
            'category_id' => $category->id,
        ]);
    }
}

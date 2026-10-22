<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LabActFourInterventionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_upload_saves_a_watermarked_webp(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $category = Category::query()->create(['name' => 'Birthday']);

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Lab Four Rose',
                'body' => 'Fresh stems for the showcase.',
                'price' => '1450',
                'category_id' => $category->id,
                'image' => UploadedFile::fake()->image('rose.jpg', 1200, 800),
            ])
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::query()->where('title', 'Lab Four Rose')->first();
        $this->assertNotNull($post);
        $this->assertStringStartsWith('products/', $post->image);
        $this->assertStringEndsWith('.webp', $post->image);
        Storage::disk('public')->assertExists($post->image);
    }

    public function test_soft_delete_keeps_the_uploaded_image_file(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $post = $this->bouquetWithUpload($admin, 'Keep File');

        $this->actingAs($admin)
            ->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertSoftDeleted('showcase_flowers', ['id' => $post->id]);
        Storage::disk('public')->assertExists($post->image);
    }

    public function test_force_delete_removes_the_uploaded_image_file(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $post = $this->bouquetWithUpload($admin, 'Remove File');
        $path = $post->image;
        $post->delete();

        $this->actingAs($admin)
            ->delete(route('admin.posts.force', $post))
            ->assertRedirect(route('admin.posts.trash'));

        $this->assertDatabaseMissing('showcase_flowers', ['id' => $post->id]);
        Storage::disk('public')->assertMissing($path);
    }

    private function bouquetWithUpload(User $admin, string $title): Post
    {
        $category = Category::query()->firstOrCreate(['name' => 'Birthday']);

        $this->actingAs($admin)
            ->post(route('admin.posts.store'), [
                'title' => $title,
                'body' => 'Uploaded for lab four.',
                'price' => '1200',
                'category_id' => $category->id,
                'image' => UploadedFile::fake()->image('upload.jpg', 900, 600),
            ])
            ->assertRedirect(route('admin.posts.index'));

        return Post::query()->where('title', $title)->firstOrFail();
    }
}

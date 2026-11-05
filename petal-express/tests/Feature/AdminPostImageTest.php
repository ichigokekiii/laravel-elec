<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPostImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_replacing_the_photo_deletes_the_previous_file(): void
    {
        [$admin, $category] = $this->adminAndCategory();

        $this->actingAs($admin)->post(route('admin.posts.store'), $this->fields($category, [
            'image' => UploadedFile::fake()->image('first.jpg'),
        ]))->assertRedirect(route('admin.posts.index'));

        $post = Post::query()->firstOrFail();
        $previous = $post->image;
        Storage::disk('public')->assertExists($previous);

        $this->actingAs($admin)->post(route('admin.posts.update', $post), $this->fields($category, [
            '_method' => 'PUT',
            'image' => UploadedFile::fake()->image('second.jpg'),
        ]))->assertRedirect(route('admin.posts.index'));

        $post->refresh();
        $this->assertNotSame($previous, $post->image);
        Storage::disk('public')->assertMissing($previous);
        Storage::disk('public')->assertExists($post->image);
    }

    public function test_edit_without_a_new_photo_keeps_the_file(): void
    {
        [$admin, $category] = $this->adminAndCategory();

        $this->actingAs($admin)->post(route('admin.posts.store'), $this->fields($category, [
            'image' => UploadedFile::fake()->image('keep.jpg'),
        ]));

        $post = Post::query()->firstOrFail();
        $previous = $post->image;

        $this->actingAs($admin)->post(route('admin.posts.update', $post), $this->fields($category, [
            '_method' => 'PUT',
            'title' => 'Renamed bouquet',
        ]))->assertRedirect(route('admin.posts.index'));

        $post->refresh();
        $this->assertSame('Renamed bouquet', $post->title);
        $this->assertSame($previous, $post->image);
        Storage::disk('public')->assertExists($previous);
    }

    public function test_create_and_edit_say_3_mb_when_the_photo_is_over_the_limit(): void
    {
        [$admin, $category] = $this->adminAndCategory();
        $post = $this->storeBouquet($admin, $category);

        $this->actingAs($admin)
            ->from(route('admin.posts.create'))
            ->post(route('admin.posts.store'), $this->fields($category, [
                'image' => $this->oversizedPhoto(),
            ]))
            ->assertRedirect(route('admin.posts.create'))
            ->assertSessionHasErrors([
                'image' => 'The photo must be 3 MB or smaller.',
            ]);

        $this->actingAs($admin)
            ->from(route('admin.posts.edit', $post))
            ->post(route('admin.posts.update', $post), $this->fields($category, [
                '_method' => 'PUT',
                'image' => $this->oversizedPhoto(),
            ]))
            ->assertRedirect(route('admin.posts.edit', $post))
            ->assertSessionHasErrors([
                'image' => 'The photo must be 3 MB or smaller.',
            ]);

        $this->assertNotSame(
            ['The image failed to upload.'],
            session('errors')->get('image')
        );
    }

    public function test_create_and_edit_say_3_mb_when_php_rejects_the_upload(): void
    {
        [$admin, $category] = $this->adminAndCategory();
        $post = $this->storeBouquet($admin, $category);
        $rejected = $this->phpRejectedUpload();

        $this->actingAs($admin)
            ->from(route('admin.posts.create'))
            ->post(route('admin.posts.store'), $this->fields($category, [
                'image' => $rejected,
            ]))
            ->assertRedirect(route('admin.posts.create'));

        $this->assertSame(
            ['The photo must be 3 MB or smaller.'],
            session('errors')->get('image')
        );

        $rejected = $this->phpRejectedUpload();

        $this->actingAs($admin)
            ->from(route('admin.posts.edit', $post))
            ->post(route('admin.posts.update', $post), $this->fields($category, [
                '_method' => 'PUT',
                'image' => $rejected,
            ]))
            ->assertRedirect(route('admin.posts.edit', $post));

        $this->assertSame(
            ['The photo must be 3 MB or smaller.'],
            session('errors')->get('image')
        );
    }

    /**
     * @return array{0: User, 1: Category}
     */
    private function adminAndCategory(): array
    {
        $admin = User::factory()->admin()->create();
        $category = Category::query()->create(['name' => 'Birthday']);

        return [$admin, $category];
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private function fields(Category $category, array $extra = []): array
    {
        return array_merge([
            'title' => 'Sample bouquet',
            'body' => 'A short description for the test.',
            'price' => '1500',
            'category_id' => $category->id,
        ], $extra);
    }

    private function storeBouquet(User $admin, Category $category): Post
    {
        $this->actingAs($admin)->post(route('admin.posts.store'), $this->fields($category, [
            'image' => UploadedFile::fake()->image('ok.jpg'),
        ]))->assertRedirect(route('admin.posts.index'));

        return Post::query()->firstOrFail();
    }

    private function oversizedPhoto(): UploadedFile
    {
        return UploadedFile::fake()->image('big.jpg')->size(4000);
    }

    private function phpRejectedUpload(): UploadedFile
    {
        $path = tempnam(sys_get_temp_dir(), 'big');
        file_put_contents($path, 'too-large');

        return new UploadedFile($path, 'bouquet.jpg', 'image/jpeg', UPLOAD_ERR_INI_SIZE, true);
    }
}

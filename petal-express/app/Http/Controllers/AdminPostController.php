<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Alignment;
use Intervention\Image\Format;
use Intervention\Image\Laravel\Facades\Image;

// admin manages showcase bouquets (stored as posts)
class AdminPostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()->with(['category', 'user'])->orderByDesc('id')->paginate(10);
        $trashedCount = Post::onlyTrashed()->count();

        return view('admin.posts.index', compact('posts', 'trashedCount'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.posts.create', compact('categories'));
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Post::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'price' => $data['price'],
            'category_id' => $data['category_id'],
            'image' => $this->saveWatermarkedImage($request->file('image')),
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bouquet added. Watermark applied.');
    }

    public function edit(Post $post): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();

        $oldImage = null;
        $newImage = null;

        if ($request->hasFile('image')) {
            $newImage = $this->saveWatermarkedImage($request->file('image'));
            $oldImage = $post->image;
        }

        try {
            $post->update([
                'title' => $data['title'],
                'body' => $data['body'],
                'price' => $data['price'],
                'category_id' => $data['category_id'],
                ...($newImage ? ['image' => $newImage] : []),
            ]);
        } catch (\Throwable $e) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }

            throw $e;
        }

        if ($oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bouquet updated.');
    }

    // sets deleted_at. the row stays in the table (image file stays too)
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bouquet moved to Trash.');
    }

    public function trash(): View
    {
        $posts = Post::onlyTrashed()
            ->with(['user', 'category'])
            ->latest('deleted_at')
            ->paginate(10);

        return view('admin.posts.trash', compact('posts'));
    }

    public function restore(Post $post): RedirectResponse
    {
        $post->restore();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bouquet restored.');
    }

    // only trash can be removed for good
    public function forceDelete(Post $post): RedirectResponse
    {
        if (! $post->trashed()) {
            return redirect()
                ->route('admin.posts.index')
                ->with('error', 'Move the bouquet to Trash before deleting it forever.');
        }

        $imagePath = $post->image;

        $post->forceDelete();

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.posts.trash')
            ->with('success', 'Bouquet deleted forever.');
    }

    // resize, watermark, save as webp under storage/app/public/products
    private function saveWatermarkedImage(UploadedFile $file): string
    {
        $image = Image::decode($file);
        $image->scaleDown(width: 1000);

        $watermarkPath = public_path('images/watermark.png');
        if (! is_file($watermarkPath)) {
            throw new \RuntimeException('Watermark not found: public/images/watermark.png');
        }

        $watermark = Image::decode($watermarkPath);
        // bigger watermark: up to 38% of photo width, capped at 320px
        $watermarkWidth = min(
            320,
            max(1, (int) ($image->width() * 0.38))
        );
        $watermark->scaleDown(width: $watermarkWidth);

        $image->insert(
            $watermark,
            12,
            12,
            Alignment::BOTTOM_RIGHT,
            0.55
        );

        $path = 'products/'.Str::uuid().'.webp';
        $encoded = $image->encodeUsingFormat(
            Format::WEBP,
            quality: 80
        );

        if (! Storage::disk('public')->put($path, (string) $encoded)) {
            throw new \RuntimeException('Failed to save image.');
        }

        return $path;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

// admin manages showcase bouquets (stored as posts)
class AdminPostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()->with(['category', 'user'])->orderBy('id')->paginate(10);
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
        Post::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bouquet added to the showcase.');
    }

    public function edit(Post $post): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $post->update($request->validated());

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bouquet updated.');
    }

    // sets deleted_at. the row stays in the table
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

        $post->forceDelete();

        return redirect()
            ->route('admin.posts.trash')
            ->with('success', 'Bouquet deleted forever.');
    }
}

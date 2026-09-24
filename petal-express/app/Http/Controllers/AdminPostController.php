<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// admin manages showcase bouquets (stored as posts)
class AdminPostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()->orderBy('id')->get();

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'price' => ['required', 'string', 'max:50'],
        ]);

        Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'price' => $validated['price'],
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bouquet added to the showcase.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'price' => ['required', 'string', 'max:50'],
        ]);

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'price' => $validated['price'],
        ]);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Bouquet updated.');
    }
}

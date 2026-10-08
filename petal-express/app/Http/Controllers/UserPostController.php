<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPostController extends Controller
{
    public function show(Post $post): View
    {
        $post->load(['category', 'user']);

        return view('posts.show', compact('post'));
    }

    public function create(): View
    {
        return view('posts.submit');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        return redirect()
            ->route('posts.submit')
            ->with('success', 'Thanks! We received your custom request and will follow up soon.');
    }
}

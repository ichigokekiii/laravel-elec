<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

// guest pages (shop / marketing site)
class PageController extends Controller
{
    public function home(): View
    {
        // six newest cards. the count is every bouquet still in the showcase
        $bouquets = Post::query()->with('category')->latest()->take(6)->get();

        return view('pages.home', [
            'tagline' => 'Fresh flowers, delivered with care.',
            'bouquets' => $bouquets,
            'featuredCount' => Post::count(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function services(): View
    {
        return view('pages.services');
    }

    public function bouquetsIndex(Request $request): View
    {
        $categories = Category::query()->withCount('posts')->orderBy('name')->get();

        $bouquets = Post::query()
            ->with(['category', 'user'])
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category_id', $request->integer('category'));
            })
            ->orderBy('id')
            ->paginate(9)
            ->withQueryString();

        return view('pages.bouquets-index', [
            'bouquets' => $bouquets,
            'categories' => $categories,
            'activeCategory' => $request->integer('category') ?: null,
        ]);
    }

    public function bouquetsShow(string $id): View
    {
        $bouquet = Post::query()->with(['category', 'user'])->findOrFail($id);

        return view('pages.bouquets-show', [
            'bouquet' => $bouquet,
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}

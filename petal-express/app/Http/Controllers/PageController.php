<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

// guest pages (shop / marketing site)
class PageController extends Controller
{
    // showcase items come from showcase_flowers (admin manages them)
    private function showcaseItems()
    {
        return Post::query()->orderBy('id')->get();
    }

    public function home(): View
    {
        $bouquets = $this->showcaseItems();

        return view('pages.home', [
            'tagline' => 'Fresh flowers, delivered with care.',
            'bouquets' => $bouquets,
            'featuredCount' => $bouquets->count(),
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

    public function bouquetsIndex(): View
    {
        return view('pages.bouquets-index', [
            'bouquets' => $this->showcaseItems(),
        ]);
    }

    public function bouquetsShow(string $id): View
    {
        $bouquet = Post::query()->findOrFail($id);

        return view('pages.bouquets-show', [
            'bouquet' => $bouquet,
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}

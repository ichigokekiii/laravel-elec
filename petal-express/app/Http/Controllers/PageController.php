<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

// this is the controller for guest pages
class PageController extends Controller
{
    // change bouquet names / prices / images here
    private function bouquets(): array
    {
        return [
            1 => [
                'id' => 1,
                'name' => 'Sunrise Dozen',
                'price' => '₱1,299',
                'description' => 'Warm roses and baby’s breath for morning deliveries around Metro Manila.',
                'image' => 'https://images.unsplash.com/photo-1519378058457-4c29a0a2efac?q=80&w=800',
            ],
            2 => [
                'id' => 2,
                'name' => 'Petal Express Classic',
                'price' => '₱1,899',
                'description' => 'Our signature mixed bouquet with seasonal blooms and a handwritten note card.',
                'image' => 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=800',
            ],
            3 => [
                'id' => 3,
                'name' => 'Quiet Garden',
                'price' => '₱2,450',
                'description' => 'Soft pastels and greenery for sympathy, thank-yous, and calm home corners.',
                'image' => 'https://images.unsplash.com/photo-1563241527-3004b7be0ffd?q=80&w=800',
            ],
        ];
    }

    public function home(): View
    {
        $bouquets = $this->bouquets();

        return view('pages.home', [
            'tagline' => 'Fresh flowers, delivered with care.',
            'bouquets' => $bouquets,
            'featuredCount' => count($bouquets),
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
            'bouquets' => $this->bouquets(),
        ]);
    }

    // {id} from the URL lands here
    public function bouquetsShow(string $id): View
    {
        $bouquets = $this->bouquets();

        if (! isset($bouquets[$id])) {
            abort(404);
        }

        return view('pages.bouquets-show', [
            'bouquet' => $bouquets[$id],
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}

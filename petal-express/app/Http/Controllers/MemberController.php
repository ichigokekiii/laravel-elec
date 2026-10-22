<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

// logged-in member pages (orders are placeholder until checkout exists)
class MemberController extends Controller
{
    public function orders(): View
    {
        return view('member.orders');
    }

    public function favorites(): View
    {
        $bouquets = Post::query()
            ->with('category')
            ->orderByDesc('id')
            ->take(6)
            ->get();

        return view('member.favorites', [
            'bouquets' => $bouquets,
        ]);
    }

    public function account(): View
    {
        return view('member.account');
    }
}

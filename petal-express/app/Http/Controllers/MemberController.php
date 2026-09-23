<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

// this is the controller for logged-in member pages
class MemberController extends Controller
{
    public function orders(): View
    {
        // fake orders for the lab. change these if the prof asks
        $orders = [
            ['code' => 'PE-1042', 'status' => 'Out for delivery', 'total' => '₱1,899'],
            ['code' => 'PE-1038', 'status' => 'Delivered', 'total' => '₱1,299'],
        ];

        return view('member.orders', [
            'orders' => $orders,
        ]);
    }

    public function favorites(): View
    {
        // change favorite names here
        $favorites = [
            'Sunrise Dozen',
            'Petal Express Classic',
        ];

        return view('member.favorites', [
            'favorites' => $favorites,
        ]);
    }

    public function account(): View
    {
        return view('member.account');
    }
}

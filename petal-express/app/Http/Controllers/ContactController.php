<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

// handles the contact form POST
class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        // no database save for this lab. just flash a thank you
        return redirect()
            ->route('contact')
            ->with('status', 'Thanks! We got your message and will reply soon.');
    }
}

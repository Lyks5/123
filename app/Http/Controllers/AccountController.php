<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;

class AccountController extends Controller
{
    /**
     * Display the account dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->latest()->take(3)->get();
        
        return view('pages.account', [
            'user' => $user,
            'recentOrders' => $recentOrders,
            'orders' => $recentOrders // Added orders to be passed to the view

        ]);
    }

    /**
     * Display the wishlists page.
     */
    public function wishlists()
    {
        $user = Auth::user();
        $wishlists = $user->wishlists; // Assuming a relationship exists for wishlists
        
        return view('account.wishlists', [
            'wishlists' => $wishlists
        ]);
    }
    
    // Other existing methods...
}

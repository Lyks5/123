<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlist = Auth::user()->getWishlist();
        $productIds = array_column($wishlist->toArray()['items'], 'product_id');
        $products = Product::whereIn('id', $productIds)->get();

        return view('account.wishlists', compact('products'));
    }

    public function toggle(Request $request)
    {
        $productId = $request->input('product_id');
        $variantId = $request->input('variant_id');
        $user = Auth::user();

        if ($user->isInWishlist($productId, $variantId)) {
            $user->removeFromWishlist($productId, $variantId);
            $message = 'Товар удален из избранного';
            $status = false;
        } else {
            $user->addToWishlist($productId, $variantId);
            $message = 'Товар добавлен в избранное';
            $status = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => $message,
                'status' => $status,
                'in_wishlist' => $status
            ]);
        }

        return back()->with('success', $message);
    }
}
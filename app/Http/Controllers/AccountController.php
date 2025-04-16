<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\Address;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    /**
     * Display the user's account dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        return view('account.dashboard', compact('user', 'recentOrders'));
    }
     /**
     * Show the user's wishlist.
     *
     * @return \Illuminate\Http\Response
     */
    public function wishlist()
    {
        $user = Auth::user();
        $defaultWishlist = Wishlist::getDefaultForUser($user->id);
        $wishlistItems = $defaultWishlist->getItemsWithProducts();
        
        return view('account.favorites', compact('wishlistItems'));
    }
    
    /**
     * Add a product to the user's default wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        $user = Auth::user();
        $defaultWishlist = Wishlist::getDefaultForUser($user->id);
        
        if ($defaultWishlist->addProduct($request->product_id)) {
            return back()->with('success', 'Товар добавлен в избранное.');
        }
        
        return back()->with('info', 'Товар уже есть в избранном.');
    }
    
    /**
     * Remove a product from the user's wishlist.
     *
     * @param  int  $productId
     * @return \Illuminate\Http\Response
     */
    public function removeFromWishlist($productId)
    {
        $user = Auth::user();
        $defaultWishlist = Wishlist::getDefaultForUser($user->id);
        
        $defaultWishlist->removeProduct($productId);
        
        return back()->with('success', 'Товар удален из избранного.');
    }

    /**
     * Display the user's orders.
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('account.orders', compact('orders'));
    }
    
    /**
     * Display the user's profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }
    
    /**
     * Update the user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('account.profile')
            ->with('success', 'Профиль успешно обновлен.');
    }
    
    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        
        return redirect()->route('account.profile')
            ->with('success', 'Пароль успешно обновлен.');
    }
    
    /**
     * Display the user's addresses.
     */
    public function addresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses;
        $defaultShipping = $user->getDefaultShippingAddress();
        $defaultBilling = $user->getDefaultBillingAddress();
        
        return view('account.addresses', [
            'addresses' => $addresses,
            'defaultShipping' => $defaultShipping,
            'defaultBilling' => $defaultBilling
        ]);
    }
    
    /**
     * Store a new address.
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'type' => 'nullable|string|in:shipping,billing',
            'is_default' => 'nullable|boolean',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['is_default'] = $request->has('is_default');
        
        // If this is the default address, unset any other default addresses
        if ($validated['is_default']) {
            Address::where('user_id', Auth::id())
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
        
        Address::create($validated);
        
        return redirect()->route('account.addresses')
            ->with('success', 'Адрес успешно добавлен.');
    }
    
    /**
     * Update an address.
     */
    public function updateAddress(Request $request, Address $address)
    {
        // Check if the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
            'type' => 'nullable|string|in:shipping,billing',
            'is_default' => 'nullable|boolean',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $validated['is_default'] = $request->has('is_default');
        
        // If this is the default address, unset any other default addresses
        if ($validated['is_default']) {
            Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
        
        $address->update($validated);
        
        return redirect()->route('account.addresses')
            ->with('success', 'Адрес успешно обновлен.');
    }
    
    /**
     * Delete an address.
     */
    public function deleteAddress(Address $address)
    {
        // Check if the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $address->delete();
        
        return redirect()->route('account.addresses')
            ->with('success', 'Адрес успешно удален.');
    }
    public function ecoImpact()
    {
        $user = Auth::user();
        $ecoImpact = $user->getTotalEcoImpact();
        $ecoImpactScore = $user->eco_impact_score;
        $orders = $user->orders()->count();
        
        return view('account.eco-impact', [
            'user' => $user,
            'ecoImpact' => $ecoImpact,
            'ecoImpactScore' => $ecoImpactScore,
            'orderCount' => $orders
        ]);
    }
    public function wishlists()
    {
        $user = Auth::user();
        $wishlist = Wishlist::getDefaultForUser($user->id);
        $wishlistItems = $wishlist->items()->with('product')->get();
        
        return view('account.wishlists', compact('wishlistItems'));
    }
}
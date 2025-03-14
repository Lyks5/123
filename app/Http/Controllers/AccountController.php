<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\Address;

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
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('account.addresses', compact('addresses'));
    }
    
    /**
     * Store a new address.
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house' => 'required|string|max:50',
            'apartment' => 'nullable|string|max:50',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'house' => 'required|string|max:50',
            'apartment' => 'nullable|string|max:50',
            'postal_code' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
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
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        $ecoImpact = $user->getTotalEcoImpact();
            
        return view('account.dashboard', compact('user', 'recentOrders', 'ecoImpact'));
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
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);
            
            $user = Auth::user();
            $defaultWishlist = $this->getDefaultWishlist($user);
            
            // Проверяем, есть ли уже товар в избранном
            $itemExists = collect($defaultWishlist['items'] ?? [])->contains('product_id', $request->product_id);
            
            \Log::info('Adding to wishlist', [
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'exists' => $itemExists
            ]);
            
            if (!$itemExists) {
                $defaultWishlist['items'][] = [
                    'product_id' => $request->product_id,
                    'added_at' => now()->toDateTimeString()
                ];
                
                // Обновляем wishlist_data пользователя
                $wishlistData = $user->wishlist_data ?? [];
                $wishlistData = collect($wishlistData)
                    ->map(function($list) use ($defaultWishlist) {
                        return $list['is_default'] ? $defaultWishlist : $list;
                    })
                    ->toArray();
                
                $user->update(['wishlist_data' => $wishlistData]);
                
                $message = 'Товар добавлен в избранное';
                $status = true;
            } else {
                $message = 'Товар уже есть в избранном';
                $status = false;
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => $status,
                    'message' => $message
                ]);
            }
            
            return back()->with($status ? 'success' : 'info', $message);
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Произошла ошибка при добавлении в избранное'
                ], 422);
            }
            return back()->with('error', 'Произошла ошибка при добавлении в избранное');
        }
    }
    
    /**
     * Remove a product from the user's wishlist.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeFromWishlist(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);
            
            $user = Auth::user();
            $defaultWishlist = $this->getDefaultWishlist($user);
            
            // Удаляем товар из списка
            $defaultWishlist['items'] = collect($defaultWishlist['items'])
                ->reject(function($item) use ($request) {
                    return $item['product_id'] == $request->product_id;
                })
                ->values()
                ->toArray();
            
            // Обновляем wishlist_data пользователя
            $wishlistData = $user->wishlist_data ?? [];
            $wishlistData = collect($wishlistData)
                ->map(function($list) use ($defaultWishlist) {
                    return $list['is_default'] ? $defaultWishlist : $list;
                })
                ->toArray();
            
            $user->update(['wishlist_data' => $wishlistData]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Товар удален из избранного'
                ]);
            }
            
            return back()->with('success', 'Товар удален из избранного');
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Произошла ошибка при удалении из избранного'
                ], 422);
            }
            return back()->with('error', 'Произошла ошибка при удалении из избранного');
        }
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
     * Display the details of a specific order.
     *
     * @param Order $order
     * @return \Illuminate\Http\Response
     */
    public function showOrder(Order $order)
    {
        // Проверяем, принадлежит ли заказ текущему пользователю
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['shippingAddress', 'billingAddress', 'items.product.primaryImage']);
        return view('account.order-details', compact('order'));
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
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preferences' => 'nullable|array',
            'preferences.*' => 'string|in:eco_friendly,vegan,organic,local'
        ]);

        // Обработка загрузки аватара
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Обработка preferences
        $validated['preferences'] = json_encode($request->input('preferences', []));
        
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
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // Минимум 1 заглавная буква
                'regex:/[a-z]/',      // Минимум 1 строчная буква
                'regex:/[0-9]/',      // Минимум 1 цифра
                'regex:/[^A-Za-z0-9]/' // Минимум 1 спецсимвол
            ]
        ], [
            'password.regex' => 'Пароль должен содержать минимум 1 заглавную букву, 1 строчную букву, 1 цифру и 1 спецсимвол'
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
        
        $address = Address::create($validated);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Адрес успешно добавлен',
                'data' => $address->load('user'),
            ], 201);
        }
        
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
    $user = $this->user();
    
    return view('account.eco-impact', [
        'user' => $user,
        'ecoImpact' => $user->getTotalEcoImpact(),
        'ecoImpactScore' => $user->eco_impact_score,
        'orderCount' => Order::forUser()->count()
    ]);
}
public function wishlists()
{
    $user = Auth::user();
    
    // Получаем или создаем дефолтный вишлист
    $defaultWishlist = $this->getDefaultWishlist($user);
    
    // Загружаем продукты для элементов вишлиста
    $wishlistItems = $this->loadWishlistItems($defaultWishlist);

    return view('account.wishlists', [
        'wishlistItems' => $wishlistItems,
        'wishlistName' => $defaultWishlist['name'] ?? 'Мой список'
    ]);
}

protected function getDefaultWishlist($user)
{
    $wishlistData = $user->wishlist_data ?? [];
    
    // Ищем дефолтный вишлист
    $defaultWishlist = collect($wishlistData)->firstWhere('is_default', true);

    // Если нет дефолтного - создаем структуру
    if (!$defaultWishlist) {
        $defaultWishlist = [
            'name' => 'Мой список',
            'is_default' => true,
            'items' => [],
            'created_at' => now()->toDateTimeString()
        ];
        
        $user->update([
            'wishlist_data' => array_merge($wishlistData, [$defaultWishlist])
        ]);
    }

    return $defaultWishlist;
}

protected function loadWishlistItems($wishlist)
{
    $productIds = collect($wishlist['items'])->pluck('product_id')->unique();


    // Загружаем продукты и варианты
    $products = Product::with('variants')
        ->whereIn('id', $productIds)
        ->get()
        ->keyBy('id');

   
    // Формируем итоговую коллекцию
    return collect($wishlist['items'])->map(function($item) use ($products) {
        $product = $products[$item['product_id']] ?? null;
      

        return [
            'product' => $product,
         
            'added_at' => $item['added_at'] ?? null
        ];
    })->filter(fn($item) => !is_null($item['product'])); // Фильтруем несуществующие товары
}
}
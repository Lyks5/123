<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\EcoFeature;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\Tag;
use App\Models\User;
use App\Models\Order;
use Intervention\Image\Facades\Image;
use App\Models\ContactRequest;
use App\Models\Review;
use App\Models\EnvironmentalInitiative;
use App\Models\EcoImpactRecord; 
// В начале контроллера

class AdminController extends Controller
{
    /**
     * Display the product attributes.
     */
    public function attributes()
    {
        $attributes = Attribute::paginate(10); // Retrieve attributes with pagination
        return view('admin.attributes.index', compact('attributes')); // Return the view with attributes
    }


    /**
     * Show the form to create a new attribute.
     */
    /**
     * Store a new attribute.
     */
    /**
     * Store a new attribute.
     */
    public function storeAttribute(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:select,radio,checkbox,color'
        ]);

        Attribute::create($validated); 

        return redirect()->route('admin.attributes.index')->with('success', 'Атрибут успешно создан.'); // Redirect with success message
    }

    /**
     * Show the form to create a new attribute.
     */
    public function createAttribute()
    {
        return view('admin.attributes.create'); // Return the view for creating a new attribute
    }

    public function editAttribute(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute')); // Return the view for editing the attribute
    }

    /**
     * Store a new attribute value.
     */
    public function storeAttributeValue(Request $request, Attribute $attribute)
{
    // Валидация
    $validated = $request->validate([
        'value' => 'required|string|max:255', // Поле value обязательно
        // 'type' не требуется, так как оно берется из $attribute
    ]);

    // Создание значения атрибута
    $attribute->values()->create([
        'value' => $validated['value'],
        'type' => $attribute->type, // Тип берется из родительского атрибута
    ]);

    return redirect()->route('admin.attributes.values.index', $attribute->id)
        ->with('success', 'Значение успешно добавлено.');
}
    public function deleteAttribute(Attribute $attribute)
    {
        $attribute->delete(); // Delete the attribute from the database
        return redirect()->route('admin.attributes.index')->with('success', 'Атрибут успешно удален.'); // Redirect with success message
    }
    public function createAttributeValue(Attribute $attribute)
{
    return view('admin.attributes.values.create', [
        'attribute' => $attribute,
    ]);
}
    /**
     * Delete an attribute value.
     */
    public function deleteAttributeValue(Attribute $attribute, $valueId)
    {
        $value = $attribute->values()->findOrFail($valueId); // Find the attribute value
        $value->delete(); // Delete the attribute value

        return redirect()->route('admin.attributes.values.index', $attribute)->with('success', 'Значение атрибута успешно удалено.'); // Redirect with success message
    }
    public function attributeValues(Attribute $attribute)
    {
        $values = $attribute->values()->paginate(10); // Assuming there's a relationship defined in the Attribute model
        return view('admin.attributes.values.index', compact('attribute', 'values')); // Return the view with attribute values
    }



 

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user')); // Display the form for editing a user
    }
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']); // Hash the password if provided
        } else {
            unset($validated['password']); // Remove password from validated data if not provided
        }

        $user->update($validated); // Update the user in the database

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.'); // Redirect with success message
    }

    public function users()
    {
        $users = User::paginate(10); // Retrieve users with pagination
        return view('admin.users.index', compact('users')); // Pass users to the view
    }

    /**
     * Show the contacts management page.
     */
    public function contactRequests()
    {
        $requests = ContactRequest::paginate(10); // Retrieve contact requests with pagination
        return view('admin.contact-requests.index', compact('requests')); // Pass requests to the view
    }

    /**
     * Show a specific contact request.
     */
public function showContactRequest(ContactRequest $contactRequest)
{
    return view('admin.contact-requests.show', compact('contactRequest')); // Pass request details to the view
}

    /**
     * Add a note to a contact request.
     */
    public function addContactRequestNote(Request $request, ContactRequest $contactRequest)
    {
        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $contactRequest->notes .= "\n" . $validated['notes'];
        $contactRequest->save();

        return redirect()->route('admin.contact-requests.show', $contactRequest)->with('success', 'Примечание успешно добавлено.'); // Redirect with success message
    }

    /**
     * Show the form to create a new environmental initiative.
     */
    public function createInitiative()
    {
        return view('admin.initiatives.create'); // Display the form for creating a new initiative
    }

    /**
     * Store a new environmental initiative.
     */
    public function storeInitiative(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        'goal' => 'required|string', // Add this line for goal validation
            'goal' => 'required|string', // Add this line for goal validation
            'start_date' => 'required|date',]); // Add this line for start_date validation

        $validated['slug'] = Str::slug($validated['title']); // Generate slug for the initiative
        $validated['goal'] = $request->goal; // Include goal in the validated data
        $validated['start_date'] = $request->start_date; // Include start_date in the validated data
        EcoFeature::create($validated); // Store the new initiative in the database

        return redirect()->route('admin.initiatives.index')->with('success', 'Инициатива успешно создана.'); // Redirect with success message
    }

    /**
     * Store a new environmental initiative.


  
     * Show the form to edit an existing environmental initiative.
     */
    public function editInitiative(EnvironmentalInitiative $initiative)
    {
        return view('admin.initiatives.edit', compact('initiative')); // Display the form for editing an initiative
    }

    /**
     * Update an existing environmental initiative.
     */
    public function updateInitiative(Request $request, EnvironmentalInitiative $initiative)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'goal' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'impact_metric' => 'nullable|string|max:255',
            'impact_value' => 'nullable|numeric',
        ]);

        $validated['slug'] = Str::slug($validated['title']); // Update slug for the initiative

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($initiative->image) {
                Storage::disk('public')->delete($initiative->image);
            }
            $validated['image'] = $request->file('image')->store('initiatives', 'public');
        }

        $initiative->update($validated); // Update the initiative in the database

        return redirect()->route('admin.initiatives.index')->with('success', 'Инициатива успешно обновлена.'); // Redirect with success message
    }

    /**
     * Delete an environmental initiative.
     */
    public function deleteInitiative(EnvironmentalInitiative $initiative)
    {
        // Delete the image if it exists
        if ($initiative->image) {
            Storage::disk('public')->delete($initiative->image);
        }

        $initiative->delete(); // Delete the initiative from the database

        return redirect()->route('admin.initiatives.index')->with('success', 'Инициатива успешно удалена.'); // Redirect with success message
    }


    /**
     * Show the initiatives management page.
     */
    public function initiatives()
    {
        $initiatives = EnvironmentalInitiative::paginate(10); // Retrieve initiatives with pagination
        return view('admin.initiatives.index', compact('initiatives')); // Pass initiatives to the view
    }
  

    /**
     * Show the orders management page.
     */
    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(10); // Retrieve all orders with user info
        return view('admin.orders.index', compact('orders')); // Pass orders to the view
    }
    public function showOrder(Order $order)
  {
    return view('admin.orders.show', compact('order')); // Pass order details to the view
   }
    /**
     * Update the status of a specific order.
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,completed,canceled,processing,shipped,delivered', // Validate status
        ]);

        $order->update(['status' => $request->status]); // Update order status

        return redirect()->route('admin.orders.index')->with('success', 'Статус заказа успешно обновлен.'); // Redirect with success message
    }
    /**
     * Create a new controller instance.
     */

    
    /**
     * Display the admin dashboard.
     */
    public function analytics()
    {
        // Gather analytics data
        $userCount = User::count();
        $productCount = Product::count();
        $orderCount = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total') ?: 0; // Ensure it defaults to 0 if no completed orders
        $totalSales = Order::where('status', 'completed')->count() ?: 0; // Ensure it defaults to 0 if no completed orders
        $totalSales = Order::where('status', 'completed')->count() ?: 0; // Ensure it defaults to 0 if no completed orders
        $totalOrders = Order::count(); // Add this line
        $averageOrderValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0; // Calculate average order value
        $totalCustomers = User::count(); // Count all users as customers
        
        // Get monthly sales data for last 6 months
        $monthlyData = $this->getMonthlyStatistics();
        
        // Fetch top products based on sales count
       $topProducts = Product::withCount('orders') // Assuming there's a relationship defined
           ->orderBy('orders_count', 'desc')
           ->take(5) // Get top 5 products
           ->get();

       // Fetch top categories based on product count
       $topCategories = Category::withCount('products')
           ->orderBy('products_count', 'desc')
           ->take(5) // Get top 5 categories
           ->get();

       // Define month names for the last 6 months
       $monthNames = [];
       for ($i = 5; $i >= 0; $i--) {
           $monthNames[] = now()->subMonths($i)->format('F');
       }

       // Get monthly sales data for last 6 months
       $monthlyData = $this->getMonthlyStatistics();
       $revenueByMonth = $monthlyData['revenue']; // Extract revenue data

       // Get orders by status
       $ordersByStatus = [
           'pending' => Order::where('status', 'pending')->count(),
           'processing' => Order::where('status', 'processing')->count(),
           'shipped' => Order::where('status', 'shipped')->count(),
           'delivered' => Order::where('status', 'delivered')->count(),
           'completed' => Order::where('status', 'completed')->count(),
           'cancelled' => Order::where('status', 'cancelled')->count(),
       ];

        return view('admin.analytics', compact('userCount', 'productCount', 'orderCount', 'totalRevenue', 'totalSales', 'totalOrders', 'averageOrderValue', 'totalCustomers', 'topProducts', 'topCategories', 'monthNames', 'revenueByMonth', 'ordersByStatus'));


    }
    public function index()
{
    // Основная статистика (адаптировано под новую структуру БД)
    $stats = [
        'productCount' => Product::where('is_active', true)->count(),
        'orderCount' => Order::where('status', 'completed')->count(),
        'userCount' => User::count(),
        'postCount' => BlogPost::where('status', 'published')->count(),
        'totalRevenue' => Order::valid()->sum('total_amount'),
        'ecoFeaturesCount' => EcoFeature::count(),
        'ecoImpact' => EcoImpactRecord::sumAll(),
    ];

    // Данные для графиков и списков
    $dashboardData = [
        'monthlySales' => $this->getMonthlySalesData(),
        'recentOrders' => Order::with(['user', 'items'])->latest()->take(5)->get(),
        'latestProducts' => Product::with('mainCategory')->latest()->take(5)->get(),
        'userActivity' => User::withCount('orders')->latest()->take(5)->get(),
        'stockAlerts' => Product::lowStockAlert()->get()
    ];

    return view('admin.dashboard', array_merge($stats, $dashboardData));
}

// Вспомогательные методы в модели Order
public function scopeValid($query)
{
    return $query->whereIn('status', ['completed', 'shipped']);
}

// Вспомогательные методы в модели EcoImpactRecord
public static function sumAll()
{
    return [
        'carbon' => self::sum('carbon_saved'),
        'plastic' => self::sum('plastic_saved'),
        'water' => self::sum('water_saved')
    ];
}

    
    /**
     * Get monthly statistics for dashboard.
     */
    protected function getMonthlySalesData(): array
    {
        $data = Order::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                SUM(total_amount) as total,
                COUNT(*) as orders
            ')
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Формируем полный список месяцев
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i)->format('Y-m');
            $months[$month] = [
                'total' => $data[$month]->total ?? 0,
                'orders' => $data[$month]->orders ?? 0
            ];
        }

        return [
            'labels' => $months->keys(),
            'totals' => $months->pluck('total'),
            'orders' => $months->pluck('orders')
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Product Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show the product management page.
     */
    public function products(Request $request)
    {
        $query = Product::with(['categories', 'ecoFeatures']);
        
        // Apply filters if any
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use($request) {
                $q->where('categories.id', $request->category);
            });
        }
        
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        if ($request->has('stock')) {
            if ($request->stock === 'in') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->stock === 'out') {
                $query->where('stock_quantity', 0);
            } elseif ($request->stock === 'low') {
                $query->where('stock_quantity', '>', 0)
                      ->where('stock_quantity', '<', 5);
            }
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Default sorting
        $sort = $request->sort ?? 'latest';
        
        switch($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'price_low':
                $query->orderBy('price');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_low':
                $query->orderBy('stock_quantity');
                break;
            case 'stock_high':
                $query->orderBy('stock_quantity', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(15)->withQueryString();
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    /**
     * Show the form to create a new product.
     */
    public function createProduct()
    {
        $categories = Category::all();
        $ecoFeatures = EcoFeature::all();
        $attributes = Attribute::all();
        return view('admin.products.create', compact('categories', 'ecoFeatures', 'attributes'));
    }
    
    /**
     * Store a new product.
     */
    
    
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products',
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'eco_features' => 'nullable|array',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required_with:variants|string|max:100|distinct',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.attributes' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'nullable|integer',
        ]);
    
        // Генерация slug
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
    
        // Создаем продукт, передавая только нужные поля
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'short_description' => $validated['short_description'] ?? null,
            'price' => $validated['price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'sku' => $validated['sku'],
            'stock_quantity' => $validated['stock_quantity'],
            'slug' => $validated['slug'],
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
            'is_new' => $request->boolean('is_new'),
            'eco_features' => $validated['eco_features'] ?? null,
        ]);
    
        // Прикрепляем категории
        $product->categories()->attach($validated['categories']);
    
        // Обработка и сохранение изображений
        if ($request->hasFile('images')) {
            $imageBlobs = [];
    
            foreach ($request->file('images') as $imageFile) {
                $image = Image::make($imageFile->getRealPath());
    
                // Изменяем размер по ширине 1680px с сохранением пропорций
                $image->resize(1680, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
    
                // Кодируем в WebP с качеством 80%
                $imageContent = (string) $image->encode('webp', 80);
    
                $imageBlobs[] = $imageContent;
            }
    
            // Сохраняем все изображения в JSON формате в отдельное поле (например, image_blobs)
            // Для этого в таблице products должно быть поле image_blobs типа TEXT или JSON
            $product->image_blobs = json_encode($imageBlobs);
    
            // Если нужно, сохраняем первичное изображение отдельно (например, первый элемент)
            if (isset($validated['primary_image']) && isset($imageBlobs[$validated['primary_image']])) {
                $product->image_blob = $imageBlobs[$validated['primary_image']];
            } else {
                // Если primary_image не указан, берем первое изображение
                $product->image_blob = $imageBlobs[0];
            }
    
            $product->save();
        }
    
        // Создание вариантов
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $variantData) {
                $product->variants()->create([
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'] ?? $product->price,
                    'sale_price' => $variantData['sale_price'] ?? $product->sale_price,
                    'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                    'attributes' => $variantData['attributes'] ?? [],
                ]);
            }
        }
    
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно создан.');
    }
    
    
    
    /**
     * Show the form to edit a product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $ecoFeatures = EcoFeature::all();
        $attributes = Attribute::all();
        $productEcoFeatures = $product->ecoFeatures->pluck('pivot.value', 'id')->toArray();
        
        // Get attributes and their values for this product
        $productAttributes = [];
        $productAttributeValues = [];
        
        foreach ($product->variants as $variant) {
            foreach ($variant->attributeValues as $value) {
                $attributeId = $value->attribute_id;
                
                // Store used attributes
                if (!in_array($attributeId, $productAttributes)) {
                    $productAttributes[] = $attributeId;
                }
                
                // Store values for each attribute
                if (!isset($productAttributeValues[$attributeId])) {
                    $productAttributeValues[$attributeId] = [];
                }
                
                if (!in_array($value->id, $productAttributeValues[$attributeId])) {
                    $productAttributeValues[$attributeId][] = $value->id;
                }
            }
        }
        
        return view('admin.products.edit', compact(
            'product', 
            'categories', 
            'ecoFeatures', 
            'attributes',
            'productEcoFeatures', 
            'productAttributes', 
            'productAttributeValues'
        ));
    }
    
    /**
     * Update a product.
     */
    public function updateProduct(Request $request, Product $product = null)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'eco_features' => 'nullable|array',
            'eco_features.*' => 'exists:eco_features,id',
            'product_attributes' => 'nullable|array',
            'product_attributes.*' => 'exists:attributes,id',
            'attribute_values' => 'nullable|array',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:variants,id',
            'variants.*.sku' => 'required_with:variants|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.attribute_values' => 'required_with:variants|array',
            'delete_variants' => 'nullable|array',
            'delete_variants.*' => 'exists:variants,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'nullable|integer',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:product_images,id',
        ]);
        
        // Set boolean values
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_new'] = $request->has('is_new');
        
        // Check if product exists
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Update product
        $product->update($validated);
        
        // Sync categories
        $product->categories()->sync($request->categories);
        
        // Sync eco features
        $product->ecoFeatures()->detach();
        if ($request->has('eco_features')) {
            foreach ($request->eco_features as $featureId => $value) {
                $product->ecoFeatures()->attach($featureId, ['value' => $value]);
            }
        }
        
        // Handle variants delete
        if ($request->has('delete_variants')) {
            $product->variants()->whereIn('id', $request->delete_variants)->delete();
        }
        
        // Handle product variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (isset($variantData['id'])) {
                    // Update existing variant
                    $variant = $product->variants()->findOrFail($variantData['id']);
                    $variant->update([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'] ?? $product->price,
                        'sale_price' => $variantData['sale_price'] ?? $product->sale_price,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                    ]);
                    
                    // Sync attribute values
                    if (isset($variantData['attribute_values']) && is_array($variantData['attribute_values'])) {
                        $variant->attributeValues()->sync($variantData['attribute_values']);
                    }
                } else {
                    // Create new variant
                    $variant = $product->variants()->create([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'] ?? $product->price,
                        'sale_price' => $variantData['sale_price'] ?? $product->sale_price,
                        'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                        'is_active' => true,
                    ]);
                    
                    // Attach attribute values
                    if (isset($variantData['attribute_values']) && is_array($variantData['attribute_values'])) {
                        $variant->attributeValues()->attach($variantData['attribute_values']);
                    }
                }
            }
        }
        
        // Handle image deletion
        // No longer applicable since images are stored as BLOB in products table
        
        // Handle new images
        if ($request->hasFile('images')) {
            $imageFile = $request->file('images')[0];
            $imageContent = file_get_contents($imageFile->getRealPath());
            $product->image_blob = $imageContent;
            $product->save();
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно обновлен.');
    }
    /**
     * Get attribute values list (AJAX endpoint).
     */
    public function getAttributeValues($attributeId)
    {
        $values = AttributeValue::where('attribute_id', $attributeId)->get();
        return response()->json($values);
    }
    /**
     * Delete a product.
     */
    public function deleteProduct(Product $product)
    {
        // Delete associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно удален.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Category Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show the category management page.
     */
    public function categories()
    {
        $categories = Category::with('parent')->withCount('products')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }
    
    /**
     * Show the form to create a new category.
     */
    public function createCategory()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }
    
    /**
     * Store a new category.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // Set boolean values
        $validated['is_active'] = $request->has('is_active');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }
        
        Category::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно создана.');
    }
    
    /**
     * Show the form to edit a category.
     */
    public function editCategory(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }
    
    /**
     * Update a category.
     */
    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
        ]);
        
        // Check for circular dependency
        if ($validated['parent_id'] == $category->id) {
            return back()->withErrors(['parent_id' => 'Категория не может быть родителем самой себя.']);
        }
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // Set boolean values
        $validated['is_active'] = $request->has('is_active');
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно обновлена.');
    }
    
    /**
     * Delete a category.
     */
    public function deleteCategory(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить категорию, содержащую товары.']);
        }
        
        // Check if category has children
        if ($category->children()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить категорию, содержащую подкатегории.']);
        }
        
        // Delete image from storage
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Eco Features Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show the eco features management page.
     */
    public function ecoFeatures(Request $request)
    {
        $query = EcoFeature::query();
        
        // Apply search filter if provided
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply sorting
        if ($request->has('sort')) {
            if ($request->sort === 'name') {
                $query->orderBy('name');
            } elseif ($request->sort === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
        } else {
            $query->orderBy('name');
        }
        
        $ecoFeatures = $query->paginate(15)->withQueryString();
        
        return view('admin.eco-features.index', compact('ecoFeatures'));
    }
    
    /**
     * Show the form to create a new eco feature.
     */
    public function createEcoFeature()
    {
        return view('admin.eco-features.create');
    }
    
    /**
     * Store a new eco feature.
     */
    public function storeEcoFeature(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // Check if slug exists
        $count = 1;
        $originalSlug = $validated['slug'];
        
        while (EcoFeature::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }
        
        EcoFeature::create($validated);
        
        return redirect()->route('admin.eco-features.index')
            ->with('success', 'Эко-характеристика успешно создана.');
    }
    
    /**
     * Show the form to edit an eco feature.
     */
    public function editEcoFeature(EcoFeature $ecoFeature)
    {
        return view('admin.eco-features.edit', compact('ecoFeature'));
    }
    
    /**
     * Update an eco feature.
     */
    public function updateEcoFeature(Request $request, EcoFeature $ecoFeature)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ]);
        
        // Update slug only if name has changed
        if ($ecoFeature->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Check if slug exists
            $count = 1;
            $originalSlug = $validated['slug'];
            
            while (EcoFeature::where('slug', $validated['slug'])->where('id', '!=', $ecoFeature->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count++;
            }
        }
        
        $ecoFeature->update($validated);
        
        return redirect()->route('admin.eco-features.index')
            ->with('success', 'Эко-характеристика успешно обновлена.');
    }
    
    /**
     * Delete an eco feature.
     */
    public function deleteEcoFeature(EcoFeature $ecoFeature)
    {
        // Check if eco-feature is associated with any products
        if ($ecoFeature->products()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить эко-характеристику, связанную с товарами.']);
        }
        
        $ecoFeature->delete();
        
        return redirect()->route('admin.eco-features.index')
            ->with('success', 'Эко-характеристика успешно удалена.');
    }
    
    /*
    |--------------------------------------------------------------------------
    | Blog Management
    |--------------------------------------------------------------------------
    */
    
    /**
     * Show the blog posts management page.
     */
    public function blogPosts(Request $request)
    {
        $query = BlogPost::with(['author', 'categories']);
        
        // Apply filters if any
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use($request) {
                $q->where('blog_categories.id', $request->category);
            });
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('author')) {
            $query->where('author_id', $request->author);
        }
        
        // Default sorting
        $sort = $request->sort ?? 'latest';
        
        switch($sort) {
            case 'title':
                $query->orderBy('title');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }
        
        $posts = $query->paginate(15)->withQueryString();
        $categories = BlogCategory::all();
        $authors = User::whereHas('blogPosts')->get();
         // Получаем все теги
    
        return view('admin.blog.posts.index', compact('posts', 'categories', 'authors'));
    }
    
    /**
     * Show the form to create a new blog post.
     */
    public function createBlogPost()
    {
        $categories = BlogCategory::all();
        
        return view('admin.blog.posts.create', compact('categories'));
    }
    
    /**
     * Store a new blog post.
     */
    public function storeBlogPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'categories' => 'required|array',
            'categories.*' => 'exists:blog_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        
        // Set author
        $validated['author_id'] = Auth::id();
        
        // Set published_at if status is published
        if ($validated['status'] == 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }
        
        // Create post
        $post = BlogPost::create($validated);
        
        // Attach categories
        $post->categories()->attach($request->categories);
        
        
        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Запись блога успешно создана.');
    }
    
    /**
     * Show the form to edit a blog post.
     */
public function editBlogPost(BlogPost $post)
{
    $categories = BlogCategory::all();
    $authors = User::whereHas('blogPosts')->get();

    return view('admin.blog.posts.edit', compact('post', 'categories', 'authors'));
}
    
    /**
     * Update a blog post.
     */
    public function updateBlogPost(Request $request, BlogPost $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'categories' => 'required|array',
            'categories.*' => 'exists:blog_categories,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'published_at' => 'nullable|date',
        ]);
        
        // Set published_at if status changed to published
        if ($validated['status'] == 'published' && $post->status != 'published') {
            $validated['published_at'] = now();
        }
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }
        
        // Update post
        $post->update($validated);
        
        // Sync categories
        $post->categories()->sync($request->categories);
        
        
        
        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Запись блога успешно обновлена.');
    }
    
    /**
     * Delete a blog post.
     */
    public function deleteBlogPost(BlogPost $post)
    {
        // Delete featured image from storage
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        
        $post->delete();
        
        return redirect()->route('admin.blog.posts.index')
            ->with('success', 'Запись блога успешно удалена.');
    }
    
    /**
     * Show the blog categories management page.
     */
    public function blogCategories()
    {
        $categories = BlogCategory::withCount('posts')->paginate(15);
        return view('admin.blog.categories.index', compact('categories'));
    }
    
    /**
     * Show the form to create a new blog category.
     */
    public function createBlogCategory()
    {
        return view('admin.blog.categories.create');
    }
    
    /**
     * Store a new blog category.
     */
    public function storeBlogCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        BlogCategory::create($validated);
        
        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Категория блога успешно создана.');
    }
    
    /**
     * Show the form to edit a blog category.
     */
    public function editBlogCategory(BlogCategory $category)
    {
        return view('admin.blog.categories.edit', compact('category'));
    }
    
    /**
     * Update a blog category.
     */
    public function updateBlogCategory(Request $request, BlogCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        $category->update($validated);
        
        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Категория блога успешно обновлена.');
    }
    
    /**
     * Delete a blog category.
     */
    public function deleteBlogCategory(BlogCategory $category)
    {
        // Check if category has posts
        if ($category->posts()->count() > 0) {
            return back()->withErrors(['general' => 'Нельзя удалить категорию, содержащую записи.']);
        }
        
        $category->delete();
        
        return redirect()->route('admin.blog.categories.index')
            ->with('success', 'Категория блога успешно удалена.');
    }
    
    
    
   
    
    

/**
 * Print an invoice for an order.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function printInvoice($id)
{
    $order = Order::with(['items'])->findOrFail($id);
    return view('admin.orders.print-invoice', compact('order'));
}

/**
 * Print a packing slip for an order.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function printPackingSlip($id)
{
    $order = Order::with(['items'])->findOrFail($id);
    return view('admin.orders.print-packing-slip', compact('order'));
}

    /**
     * Update the status of a contact request.
     */
    public function updateContactRequestStatus(Request $request, ContactRequest $contactRequest)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:new,in_progress,closed',
        ]);

        $contactRequest->status = $validated['status'];
        $contactRequest->save();

        return redirect()->route('admin.contact-requests.show', $contactRequest)
            ->with('success', 'Статус обращения успешно обновлен.');
    }
    
    public function deleteContactRequest(ContactRequest $contactRequest)
   {
       $contactRequest->delete();
        return redirect()->route('admin.contact-requests.index')
            ->with('success', 'Обращение успешно удалено.');
    }
}

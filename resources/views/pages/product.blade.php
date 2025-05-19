@extends('layouts.app')

@section('title', $product->name . ' - EcoStore')

@section('content')
<section class="pt-24 pb-16 min-h-screen bg-gradient-to-br from-eco-50 via-eco-100 to-eco-50">
  <div class="container-width mx-auto max-w-7xl px-4 sm:px-6">
    <!-- Breadcrumbs -->
    <nav class="mb-8" aria-label="Breadcrumb">
      <ol class="flex items-center space-x-2 text-xs text-gray-400">
        <li>
          <a href="{{ route('home') }}" class="hover:underline text-eco-600">Главная</a>
        </li>
        <li>/</li>
        <li>
          <a href="{{ route('shop') }}" class="hover:underline text-eco-600">Магазин</a>
        </li>
        @if($product->categories->isNotEmpty())
        <li>/</li>
        <li>
          <a href="{{ route('shop', ['category' => $product->categories->first()->slug]) }}" class="hover:underline text-eco-600">{{ $product->categories->first()->name }}</a>
        </li>
        @endif
        <li>/</li>
        <li class="text-eco-900">{{ $product->name }}</li>
      </ol>
    </nav>
    <!-- Main card -->
    <div class="relative rounded-3xl overflow-hidden shadow-lg bg-white p-0 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-12 border border-eco-100">
      <!-- Product Images -->
      <div class="flex flex-col space-y-4">
        <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-gradient-to-tr from-eco-50 to-eco-200 shadow-inner border border-eco-100">
          @if($product->primary_image)
            <img id="main-product-image" src="{{ asset('storage/' . $product->primary_image->image_path) }}" alt="{{ $product->primary_image->alt_text }}" class="w-full h-full object-cover transition duration-200 hover:scale-105" />
          @else
            <div class="w-full h-full flex items-center justify-center text-eco-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" stroke="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" /><circle cx="8.5" cy="8.5" r="1.5" /><polyline points="21 15 16 10 5 21"/></svg>
            </div>
          @endif
        </div>
        @if($product->images && $product->images->count() > 1)
        <div class="flex space-x-2">
          @foreach($product->images as $img)
          <div class="w-16 h-16 rounded-xl overflow-hidden border-2 border-transparent hover:border-eco-400 cursor-pointer transition">
            <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $img->alt_text }}" data-image="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover product-thumbnail" />
          </div>
          @endforeach
        </div>
        @endif
      </div>
      <!-- Product Details -->
      <div class="flex flex-col justify-between">
        <div>
          <div class="flex flex-wrap gap-2 items-baseline mb-1">
            @if($product->categories->isNotEmpty())
              <span class="text-xs font-medium uppercase tracking-wide text-eco-500 bg-eco-100 px-2 py-1 rounded-md">{{ $product->categories->first()->name }}</span>
            @endif
            @if($ecoFeatures->isNotEmpty())
              @foreach($ecoFeatures->take(1) as $f)
                <span class="text-xs font-medium uppercase tracking-wide text-green-800 bg-green-100 px-2 py-1 rounded-md">{{ $f->name }}</span>
              @endforeach
            @endif
            @if($product->is_new)
              <span class="text-xs font-medium uppercase tracking-wide text-white bg-eco-500 px-2 py-1 rounded-md">Новинка</span>
            @endif
          </div>
          <h1 class="text-3xl md:text-4xl font-semibold text-eco-900 mb-2">{{ $product->name }}</h1>
          <div class="flex items-center gap-4 mb-4">
            @if($product->sale_price && $product->sale_price < $product->price)
              <div class="flex items-center gap-2">
                <span class="text-2xl font-bold text-eco-900">{{ number_format($product->sale_price, 0, ',', ' ') }} ₽</span>
                <span class="text-lg line-through text-eco-400">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                <span class="bg-red-100 text-red-500 rounded-md text-xs px-2 py-1 font-semibold">-{{ round((1 - $product->sale_price / $product->price) * 100) }}%</span>
              </div>
            @else
              <span class="text-2xl font-bold text-eco-900">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
            @endif
            <span class="ml-auto text-sm {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-400' }}">
              @if($product->stock_quantity > 10)
                В наличии
              @elseif($product->stock_quantity > 0)
                Осталось: {{ $product->stock_quantity }} шт.
              @else
                Нет в наличии
              @endif
            </span>
          </div>
          <!-- ECO features chips -->
          @if($ecoFeatures->isNotEmpty())
          <ul class="flex flex-wrap gap-3 mb-2">
            @foreach($ecoFeatures->take(3) as $feature)
              <li class="flex items-center space-x-2 text-xs text-green-900 bg-green-100 px-3 py-1 rounded-lg">
                @if($feature->icon)
                  <span>{!! $feature->icon !!}</span>
                @endif
                <span>{{ $feature->name }}</span>
              </li>
            @endforeach
          </ul>
          @endif
          <!-- Short description -->
          <div class="prose prose-sm text-eco-700 max-w-none mb-6">
            <p>{{ $product->short_description ?? \Illuminate\Support\Str::limit($product->description, 200) }}</p>
          </div>
          <!-- Атрибуты -->
          @if(isset($productAttributes) && count($productAttributes) > 0)
          <div class="mb-5 grid grid-cols-2 gap-4">
            @foreach($productAttributes as $group => $attributes)
              @foreach($attributes as $attr)
                <div>
                  <div class="text-xs text-eco-700 font-medium mb-1">{{ $attr->name }}:</div>
                  @if($group == 'color' && isset($attr->values))
                  <div class="flex gap-2">
                    @foreach($attr->values as $val)
                    <div class="w-6 h-6 rounded-full" style="background: {{ $val->hex_color ?: '#eee' }}" title="{{ $val->value }}"></div>
                    @endforeach
                  </div>
                  @elseif(isset($attr->values))
                  <div class="flex gap-2 flex-wrap">
                    @foreach($attr->values as $val)
                    <div class="px-2 py-1 border border-eco-100 rounded-xl text-xs bg-eco-50">{{ $val->value }}</div>
                    @endforeach
                  </div>
                  @elseif(isset($attr->value))
                    <div class="text-xs">{{ $attr->value }}</div>
                  @endif
                </div>
              @endforeach
            @endforeach
          </div>
          @endif
        </div>
        <!-- Add to Cart row -->
        <form action="{{ route('cart.add') }}" method="POST" class="mt-4 flex flex-col gap-4">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product->id }}">
          <div class="flex gap-4 items-center">
            <label class="text-eco-700 font-medium">Количество:</label>
            <div class="flex items-center border border-eco-200 rounded-lg bg-eco-50">
              <button type="button" id="dec-qty" class="px-4 py-2 text-eco-500 hover:text-eco-900 text-xl" aria-label="Уменьшить">-</button>
              <input type="number" name="quantity" id="quantity" value="1" min="1" readonly class="w-12 text-center bg-transparent px-2 py-2 border-x border-eco-100 text-eco-900 appearance-none" />
              <button type="button" id="inc-qty" class="px-4 py-2 text-eco-500 hover:text-eco-900 text-xl" aria-label="Увеличить">+</button>
            </div>
            <button type="submit" class="ml-auto bg-eco-600 hover:bg-eco-700 text-white font-semibold px-6 py-3 rounded-full text-base shadow transition-all {{ $product->stock_quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" @if($product->stock_quantity <= 0) disabled @endif>
              Добавить в корзину
            </button>
            <button type="button" id="wishlist-btn" class="border border-eco-200 text-eco-800 hover:bg-eco-50 rounded-full px-5 py-3 ml-2 transition font-semibold text-base flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" class="mr-1"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3.332.787-4.5 2.05C10.932 3.786 9.36 3 7.5 3A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
              В избранное
            </button>
          </div>
        </form>
        <div class="mt-4 flex gap-2 justify-between">
          <button id="share-btn" type="button" class="py-2 px-4 bg-eco-50 rounded-lg text-eco-700 flex items-center gap-2 hover:bg-eco-100 transition shadow">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" class="inline mr-1"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" x2="12" y1="2" y2="15"/></svg>
            Поделиться
          </button>
        </div>
      </div>
    </div>
    <!-- Tabs -->
    <div class="rounded-2xl mt-12 bg-white shadow border border-eco-100 overflow-hidden">
      <div class="flex border-b border-eco-100">
        <button id="tab-description" role="tab" data-tab="description" class="transition px-6 py-4 text-base font-medium border-b-2 border-eco-600 text-eco-900 focus:outline-none">Описание</button>
        <button id="tab-specifications" role="tab" data-tab="specifications" class="transition px-6 py-4 text-base font-medium border-b-2 border-transparent text-eco-600 focus:outline-none">Характеристики</button>
        <button id="tab-reviews" role="tab" data-tab="reviews" class="transition px-6 py-4 text-base font-medium border-b-2 border-transparent text-eco-600 focus:outline-none">Отзывы</button>
      </div>
      <div class="p-8">
        <!-- Описание -->
        <div id="content-description" class="tab-content block" role="tabpanel">
          <div class="prose text-eco-800 max-w-none">
            <h3 class="text-2xl font-semibold mb-4 text-eco-900">О продукте</h3>
            <div>{!! $product->description !!}</div>
            @if($ecoFeatures->isNotEmpty())
            <div class="mt-8">
              <h4 class="text-lg font-semibold text-eco-900 mt-6 mb-3">Преимущества</h4>
              <ul class="space-y-2">
                @foreach($ecoFeatures as $feature)
                  <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mt-1 mr-2" fill="none" stroke="currentColor"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                    <span>{{ $feature->description }}</span>
                  </li>
                @endforeach
              </ul>
            </div>
            @endif
          </div>
        </div>
        <!-- Характеристики -->
        <div id="content-specifications" class="tab-content hidden" role="tabpanel">
          <div class="prose text-eco-800 max-w-none">
            <h3 class="text-2xl font-semibold mb-4 text-eco-900">Технические характеристики</h3>
            <div class="grid md:grid-cols-2 gap-x-8 gap-y-4">
              @if(isset($productAttributes) && count($productAttributes) > 0)
                @foreach($productAttributes as $group => $attributes)
                <div class="col-span-1">
                  <h4 class="text-lg font-medium text-eco-900 mb-3 capitalize">{{ $group }}</h4>
                  <div class="space-y-2">
                    @foreach($attributes as $attribute)
                      <div class="flex justify-between py-2 border-b border-eco-50">
                        <span class="text-eco-700">{{ $attribute->name }}:</span>
                        @if(isset($attribute->values))
                          <span class="text-eco-900 font-medium">
                            @if($group == 'color')
                              <div class="flex items-center gap-1">
                                @foreach($attribute->values as $val)
                                  <div class="w-4 h-4 rounded-full" style="background-color: {{ $val->hex_color ?: '#eee' }};" title="{{ $val->value }}"></div>
                                @endforeach
                              </div>
                            @else
                              {{ $attribute->values->pluck('value')->implode(', ') }}
                            @endif
                          </span>
                        @elseif(isset($attribute->value))
                          <span class="text-eco-900 font-medium">
                            {{ $attribute->value }}
                          </span>
                        @endif
                      </div>
                    @endforeach
                  </div>
                </div>
                @endforeach
              @else
                <div class="col-span-2">
                  <p class="text-eco-600">Характеристики не указаны для данного товара.</p>
                </div>
              @endif
              <div class="col-span-1">
                <h4 class="text-lg font-medium text-eco-900 mb-3">Основная информация</h4>
                <div class="space-y-2">
                  <div class="flex justify-between py-2 border-b border-eco-100">
                    <span class="text-eco-700">Артикул:</span>
                    <span class="text-eco-900 font-medium">{{ $product->sku }}</span>
                  </div>
                  @if($product->categories->isNotEmpty())
                  <div class="flex justify-between py-2 border-b border-eco-100">
                    <span class="text-eco-700">Категория:</span>
                    <span class="text-eco-900 font-medium">{{ $product->categories->pluck('name')->implode(', ') }}</span>
                  </div>
                  @endif
                  <div class="flex justify-between py-2 border-b border-eco-100">
                    <span class="text-eco-700">Наличие:</span>
                    <span class="text-eco-900 font-medium {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-500' }}">
                    @if($product->stock_quantity > 10)
                      В наличии
                    @elseif($product->stock_quantity > 0)
                      Осталось: {{ $product->stock_quantity }} шт.
                    @else
                      Нет в наличии
                    @endif
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Отзывы -->
        <div id="content-reviews" class="tab-content hidden" role="tabpanel">
          <div class="prose text-eco-800 max-w-none">
            <h3 class="text-2xl font-semibold mb-4 text-eco-900">Отзывы клиентов</h3>
            @if($reviews->count() > 0)
            <div class="space-y-6">
              @foreach($reviews as $review)
              <div class="border-b border-eco-100 pb-6 last:border-b-0">
                <div class="flex items-start mb-2">
                  <div class="w-10 h-10 bg-eco-100 rounded-full flex items-center justify-center text-eco-700 font-medium mr-3">
                    {{ substr($review->user->name ?? 'A', 0, 1) }}
                  </div>
                  <div>
                    <div class="font-medium text-eco-900">{{ $review->user->name ?? 'Анонимный пользователь' }}</div>
                    <div class="text-sm text-eco-600">{{ $review->created_at->format('d.m.Y') }}</div>
                    <div class="flex items-center mt-1">
                      @for($i = 1; $i <= 5; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 16 16">
                          <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                        </svg>
                      @endfor
                    </div>
                  </div>
                </div>
                <h4 class="text-eco-900 font-semibold mb-2">{{ $review->title }}</h4>
                <p class="text-eco-700">{{ $review->comment }}</p>
              </div>
              @endforeach
            </div>
            <div class="mt-6">{{ $reviews->links() }}</div>
            @else
              <p class="text-eco-700 mb-4">Отзывы пока отсутствуют. Будьте первым, кто оставит отзыв!</p>
            @endif
            <!-- Review Form -->
            <div class="mt-8 bg-eco-50 p-6 rounded-xl">
              <h4 class="text-lg font-semibold text-eco-900 mb-4">Оставить отзыв</h4>
              @if(auth()->check())
              <form action="{{ route('product.review', $product->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                  <label for="rating" class="block text-sm font-medium text-eco-700 mb-1">Оценка</label>
                  <div class="flex items-center space-x-1">
                    @for($i = 1; $i <= 5; $i++)
                      <button type="button" class="rating-star text-gray-300 hover:text-yellow-400 focus:outline-none" data-rating="{{ $i }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                          <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                        </svg>
                      </button>
                    @endfor
                    <input type="hidden" id="rating" name="rating" value="5" />
                  </div>
                </div>
                <div class="mb-4">
                  <label for="title" class="block text-sm font-medium text-eco-700 mb-1">Заголовок отзыва</label>
                  <input type="text" id="title" name="title" required class="w-full px-4 py-2 border border-eco-200 rounded-lg focus:ring-eco-500 focus:border-eco-500" placeholder="Кратко опишите ваши впечатления" />
                </div>
                <div class="mb-4">
                  <label for="comment" class="block text-sm font-medium text-eco-700 mb-1">Ваш отзыв</label>
                  <textarea id="comment" name="comment" rows="4" required class="w-full px-4 py-2 border border-eco-200 rounded-lg focus:ring-eco-500 focus:border-eco-500" placeholder="Поделитесь своим опытом использования"></textarea>
                </div>
                <button type="submit" class="bg-eco-600 hover:bg-eco-700 text-white font-medium py-2 px-6 rounded-lg transition">Отправить отзыв</button>
              </form>
              @else
              <div class="bg-white p-4 rounded-lg text-center">
                <p class="text-eco-700 mb-3">Чтобы оставить отзыв, пожалуйста, авторизуйтесь.</p>
                <a href="{{ route('login') }}" class="inline-flex items-center bg-eco-600 hover:bg-eco-700 text-white font-medium py-2 px-6 rounded-lg transition">Войти в аккаунт</a>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ECO-фичи и Доставка в блоке -->
    <div class="mt-10 grid md:grid-cols-2 gap-8">
      <!-- Eco Features -->
      <div class="bg-white/80 rounded-2xl border border-eco-100 shadow-inner p-8">
        <h3 class="text-lg font-semibold mb-3 text-green-900">Экологические характеристики</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          @forelse($ecoFeatures as $feature)
          <div class="flex items-center space-x-4 bg-green-50 rounded-xl p-4 border border-green-100">
            @if($feature->icon)
              <div class="w-10 h-10 flex items-center justify-center rounded-lg text-green-600 bg-green-100 border border-green-200">{!! $feature->icon !!}</div>
            @endif
            <div>
              <div class="text-eco-900 text-xs font-medium">{{ $feature->name }}</div>
              <div class="text-green-800 text-xs">{{ $feature->value ?? \Illuminate\Support\Str::limit($feature->description, 50) }}</div>
            </div>
          </div>
          @empty
          <div class="col-span-2 text-eco-500 bg-eco-100 rounded-xl p-4 text-center text-sm">Характеристики не указаны</div>
          @endforelse
        </div>
      </div>
      <!-- Delivery Info -->
      <div class="bg-white/80 rounded-2xl border border-eco-100 shadow-inner p-8">
        <h3 class="text-lg font-semibold mb-3 text-eco-900">Доставка и оплата</h3>
        <ul class="space-y-3">
          <li class="flex items-center space-x-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-eco-100 text-eco-700 border border-eco-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" stroke="currentColor" fill="none"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
            </div>
            <span class="text-eco-800 text-sm">Экологичная доставка — низкий углеродный след</span>
          </li>
          <li class="flex items-center space-x-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-eco-100 text-eco-700 border border-eco-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" stroke="currentColor" fill="none"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            </div>
            <span class="text-eco-800 text-sm">Оплата — карта, рассрочка, при получении</span>
          </li>
          <li class="flex items-center space-x-4">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-eco-100 text-eco-700 border border-eco-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" stroke="currentColor" fill="none"><path d="M14 19a6 6 0 0 0-12 0"/><circle cx="8" cy="9" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            </div>
            <span class="text-eco-800 text-sm">Эко-бонусы — увеличьте свой рейтинг за покупки</span>
          </li>
        </ul>
      </div>
    </div>
    <!-- Похожие товары -->
    <div class="my-16">
      <h2 class="text-2xl font-bold mb-6 text-eco-900">Похожие товары</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($relatedProducts as $relatedProduct)
          @include('components.product-card', ['product' => $relatedProduct])
        @endforeach
      </div>
    </div>
  </div>
</section>
<script src="{{ asset('resources/js/product-page.js') }}" defer></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    window.EcoStoreProductPage.init(@json($product->name), @json($product->id));
  });
</script>
@endsection
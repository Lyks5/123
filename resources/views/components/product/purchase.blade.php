@props(['product'])

<div class="purchase-block space-y-6" x-data="{ quantity: 1 }">
    <div class="flex flex-col gap-4">
        <!-- Селектор количества -->
        <div class="quantity-selector flex items-center justify-between p-4 bg-white/80 rounded-xl border border-eco-100 shadow-inner">
            <label for="quantity" class="text-eco-700 font-medium">Количество:</label>
            <div class="flex items-center gap-2">
                <button type="button"
                        @click="quantity > 1 ? quantity-- : null"
                        :disabled="quantity <= 1"
                        class="w-10 h-10 rounded-lg bg-eco-50 text-eco-600 hover:bg-eco-100 flex items-center justify-center transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="sr-only">Уменьшить количество</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </button>

                <input type="number"
                       id="quantity"
                       x-model.number="quantity"
                       min="1"
                       max="{{ $product->stock_quantity }}"
                       class="w-16 h-10 text-center bg-white border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500 text-eco-900"
                       @change="quantity = Math.min(Math.max($event.target.value, 1), {{ $product->stock_quantity }})" />

                <button type="button"
                        @click="quantity < {{ $product->stock_quantity }} ? quantity++ : null"
                        :disabled="quantity >= {{ $product->stock_quantity }}"
                        class="w-10 h-10 rounded-lg bg-eco-50 text-eco-600 hover:bg-eco-100 flex items-center justify-center transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="sr-only">Увеличить количество</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Отображение количества на складе -->
        <div class="text-sm text-eco-700 px-4">
            @if($product->stock_quantity > 0)
                <span>В наличии: {{ $product->stock_quantity }} шт.</span>
            @else
                <span class="text-red-500">Нет в наличии</span>
            @endif
        </div>

        <!-- Кнопки действий -->
        <div class="flex flex-col sm:flex-row gap-4">
            <button type="button"
                    id="add-to-cart-btn"
                    data-product-id="{{ $product->id }}"
                    class="flex-1 bg-gradient-to-r from-eco-600 to-eco-500 text-white font-semibold px-6 py-3 rounded-xl shadow-lg shadow-eco-500/20 hover:shadow-eco-600/30 transform hover:translate-y-[-2px] transition-all duration-300 flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
                    :disabled="{{ $product->stock_quantity }} <= 0"
                    x-data="{ loading: false }"
                    @click="if(!loading) {
                        loading = true;
                        fetch('/cart/add', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                product_id: {{ $product->id }},
                                quantity: quantity
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.dispatchEvent(new CustomEvent('cart-updated', {
                                    detail: { count: data.cartCount }
                                }));
                                window.notificationManager.show('success', data.message || 'Товар успешно добавлен в корзину');
                            } else {
                                window.notificationManager.show('error', data.message || 'Ошибка при добавлении товара');
                            }
                            loading = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            loading = false;
                        });
                    }"
                    :class="{ 'opacity-75 cursor-wait': loading }">
                <span class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                    </svg>
                    <svg x-show="loading" class="animate-spin h-5 w-5 absolute inset-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </span>
                <span x-show="!loading">Добавить в корзину</span>
                <span x-show="loading">Добавление...</span>
            </button>
            
            <button type="button"
                    id="wishlist-btn"
                    data-product-id="{{ $product->id }}"
                    class="border border-eco-200 text-eco-800 hover:bg-eco-50/80 rounded-full p-3 transition-all duration-300 hover:shadow-md hover:shadow-eco-100/20 hover:border-eco-300 group {{ in_array($product->id, auth()->user()->wishlist_data[0]['items'] ?? []) ? 'bg-eco-50/80 text-eco-600' : '' }}"
                    aria-label="Добавить в избранное"
                    title="{{ in_array($product->id, auth()->user()->wishlist_data[0]['items'] ?? []) ? 'Товар в избранном' : 'Добавить в избранное' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3.332.787-4.5 2.05C10.932 3.786 9.36 3 7.5 3A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                </svg>
            </button>
        </div>

        <div class="flex gap-2">
            <button type="button"
                    id="share-btn"
                    class="py-2 px-4 bg-gradient-to-r from-eco-50/80 to-white rounded-lg text-eco-700 flex items-center gap-2 hover:from-eco-100/80 hover:to-eco-50/80 transition-all duration-300 shadow-sm hover:shadow group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor">
                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/>
                    <polyline points="16 6 12 2 8 6"/>
                    <line x1="12" x2="12" y1="2" y2="15"/>
                </svg>
                Поделиться
            </button>
        </div>
    </form>
</div>
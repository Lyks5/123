@extends('admin.layouts.app')

@section('title', 'Просмотр заказа #' . $order->id)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-eco-600 hover:text-eco-700 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Назад к списку заказов
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Информация о заказе -->
        <div class="bg-white shadow rounded-lg p-6 md:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Заказ #{{ $order->id }}</h1>
                <span class="px-3 py-1 rounded-full text-sm
                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800 
                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                    @elseif($order->status == 'delivered') bg-indigo-100 text-indigo-800
                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    @if($order->status == 'pending') Ожидает
                    @elseif($order->status == 'processing') В обработке
                    @elseif($order->status == 'shipped') Отправлен
                    @elseif($order->status == 'delivered') Доставлен
                    @elseif($order->status == 'completed') Завершен
                    @elseif($order->status == 'cancelled') Отменен
                    @else {{ $order->status }} @endif
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Дата заказа</p>
                    <p class="font-medium">{{ $order->created_at->format('d.m.Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Способ оплаты</p>
                    <p class="font-medium">{{ $order->payment_method ?: 'Не указан' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Способ доставки</p>
                    <p class="font-medium">{{ $order->shipping_method ?: 'Не указан' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Номер отслеживания</p>
                    <p class="font-medium">{{ $order->tracking_number ?: 'Не указан' }}</p>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-3">Товары</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-2 px-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Товар</th>
                                <th class="py-2 px-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Цена</th>
                                <th class="py-2 px-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Кол-во</th>
                                <th class="py-2 px-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3 px-3">
                                        <div class="flex items-center">
                                            @if($item->product && $item->product->primaryImage)
                                                <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" 
                                                    alt="{{ $item->product_name }}" class="w-12 h-12 object-cover rounded mr-3">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                    <span class="text-gray-500 text-xs">Нет фото</span>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium">{{ $item->product_name }}</div>
                                                @if($item->variant_name)
                                                    <div class="text-sm text-gray-500">{{ $item->variant_name }}</div>
                                                @endif
                                                @if($item->product)
                                                    <div class="text-xs text-gray-400">SKU: {{ $item->product->sku }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-3 text-right">{{ number_format($item->price, 0, '.', ' ') }} ₽</td>
                                    <td class="py-3 px-3 text-right">{{ $item->quantity }}</td>
                                    <td class="py-3 px-3 text-right font-medium">{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₽</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="py-3 px-3 text-right font-medium">Подытог:</td>
                                <td class="py-3 px-3 text-right font-medium">{{ number_format($order->subtotal, 0, '.', ' ') }} ₽</td>
                            </tr>
                            @if($order->shipping_amount > 0)
                                <tr>
                                    <td colspan="3" class="py-3 px-3 text-right font-medium">Доставка:</td>
                                    <td class="py-3 px-3 text-right font-medium">{{ number_format($order->shipping_amount, 0, '.', ' ') }} ₽</td>
                                </tr>
                            @endif
                            @if($order->tax_amount > 0)
                                <tr>
                                    <td colspan="3" class="py-3 px-3 text-right font-medium">Налог:</td>
                                    <td class="py-3 px-3 text-right font-medium">{{ number_format($order->tax_amount, 0, '.', ' ') }} ₽</td>
                                </tr>
                            @endif
                            @if($order->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="py-3 px-3 text-right font-medium">Скидка:</td>
                                    <td class="py-3 px-3 text-right font-medium text-red-600">-{{ number_format($order->discount_amount, 0, '.', ' ') }} ₽</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="py-3 px-3 text-right font-bold">Итого:</td>
                                <td class="py-3 px-3 text-right font-bold">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            @if($order->notes)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Примечания к заказу</h2>
                    <div class="bg-gray-50 p-3 rounded">
                        {{ $order->notes }}
                    </div>
                </div>
            @endif
            
            @if($order->carbon_offset)
                <div class="mb-6">
                    <div class="bg-eco-50 p-3 rounded border border-eco-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-eco-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="font-medium text-eco-600">Покупатель компенсировал углеродный след заказа</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Боковая панель с информацией о клиенте и действиями -->
        <div class="space-y-6">
            <!-- Информация о клиенте -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Информация о клиенте</h2>
                <div class="mb-4">
                    <div class="font-medium">{{ $order->user->name }}</div>
                    <div class="text-gray-500">{{ $order->user->email }}</div>
                    @if($order->user->phone)
                        <div class="text-gray-500">{{ $order->user->phone }}</div>
                    @endif
                </div>
                
                @if($order->shippingAddress)
                    <h3 class="font-medium mb-2">Адрес доставки</h3>
                    <div class="text-gray-700 mb-4">
                        <div>{{ $order->shippingAddress->recipient_name }}</div>
                        <div>{{ $order->shippingAddress->address_line_1 }}</div>
                        @if($order->shippingAddress->address_line_2)
                            <div>{{ $order->shippingAddress->address_line_2 }}</div>
                        @endif
                        <div>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->region }} {{ $order->shippingAddress->postal_code }}</div>
                        <div>{{ $order->shippingAddress->country }}</div>
                        @if($order->shippingAddress->phone)
                            <div>{{ $order->shippingAddress->phone }}</div>
                        @endif
                    </div>
                @endif
                
                @if($order->billingAddress && $order->billingAddress->id != $order->shippingAddress->id)
                    <h3 class="font-medium mb-2">Адрес для счета</h3>
                    <div class="text-gray-700">
                        <div>{{ $order->billingAddress->recipient_name }}</div>
                        <div>{{ $order->billingAddress->address_line_1 }}</div>
                        @if($order->billingAddress->address_line_2)
                            <div>{{ $order->billingAddress->address_line_2 }}</div>
                        @endif
                        <div>{{ $order->billingAddress->city }}, {{ $order->billingAddress->region }} {{ $order->billingAddress->postal_code }}</div>
                        <div>{{ $order->billingAddress->country }}</div>
                        @if($order->billingAddress->phone)
                            <div>{{ $order->billingAddress->phone }}</div>
                        @endif
                    </div>
                @endif
            </div>
            
            <!-- Действия с заказом -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Действия</h2>
                
                <form action="{{ route('admin.orders.update.status', $order->id) }}" method="POST" class="mb-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Изменить статус</label>
                        <select name="status" id="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>В обработке</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Отправлен</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Доставлен</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Завершен</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-eco-600 hover:bg-eco-700 text-white font-bold py-2 px-4 rounded">
                        Обновить статус
                    </button>
                </form>
                
                <div class="flex space-x-2">
                    <a href="{{ route('admin.orders.print.invoice', $order->id) }}" target="_blank" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded text-center">
                        Печать счета
                    </a>
                    <a href="{{ route('admin.orders.print.packing-slip', $order->id) }}" target="_blank" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded text-center">
                        Печать накладной
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
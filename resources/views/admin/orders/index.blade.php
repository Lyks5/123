@extends('admin.layouts.app')

@section('title', 'Управление заказами')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Заказы</h1>
            <div class="flex space-x-2">
                <form action="{{ route('admin.orders.index') }}" method="GET" class="relative">
                    <select id="statusFilter" name="status" onchange="this.form.submit()" class="block pl-3 pr-10 py-2 rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                        <option value="">Все статусы</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ожидает</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>В обработке</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Отправлен</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Доставлен</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Завершен</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                    </select>
                </form>
                
                <div class="relative">
                    <input type="text" id="orderSearch" placeholder="Поиск по номеру или имени" 
                        class="block w-full pl-3 pr-10 py-2 rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                </div>
            </div>
        </div>

        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-700">
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Клиент</th>
                            <th class="py-3 px-4 text-left">Дата</th>
                            <th class="py-3 px-4 text-left">Сумма</th>
                            <th class="py-3 px-4 text-left">Статус</th>
                            <th class="py-3 px-4 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">#{{ $order->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-medium">{{ $order->user->name }}</div>
                                    <div class="text-gray-500 text-sm">{{ $order->user->email }}</div>
                                </td>
                                <td class="py-3 px-4">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td class="py-3 px-4">{{ number_format($order->total_amount, 0, '.', ' ') }} ₽</td>
                                <td class="py-3 px-4">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'shipped' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-indigo-100 text-indigo-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        
                                        $statusNames = [
                                            'pending' => 'Ожидает',
                                            'processing' => 'В обработке',
                                            'shipped' => 'Отправлен',
                                            'delivered' => 'Доставлен',
                                            'completed' => 'Завершен',
                                            'cancelled' => 'Отменен',
                                        ];
                                    @endphp
                                    
                                    <span class="px-2 py-1 rounded-full text-xs {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusNames[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Нет заказов в системе.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Поиск заказов
        document.getElementById('orderSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const orderIdCell = row.querySelector('td:nth-child(1)').textContent;
                const customerCell = row.querySelector('td:nth-child(2)').textContent;
                
                if (orderIdCell.toLowerCase().includes(searchTerm) || customerCell.toLowerCase().includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection
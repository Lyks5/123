
@extends('admin.layouts.app')

@section('title', 'Управление пользователями')

@section('content')
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Пользователи</h1>
            <div class="flex gap-4 items-center">
                <div class="relative">
                    <input type="text" id="userSearch" placeholder="Поиск по имени или email"
                        class="block w-64 pl-3 pr-10 py-2 rounded-md border-gray-300 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50">
                </div>
            </div>
        </div>

        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr class="text-gray-700">
                            <th class="py-3 px-4 text-left">ID</th>
                            <th class="py-3 px-4 text-left">Имя</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Дата регистрации</th>
                            <th class="py-3 px-4 text-left">Статус</th>
                            <th class="py-3 px-4 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $user->id }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full mr-2">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-eco-500 text-white flex items-center justify-center mr-2">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">{{ $user->email }}</td>
                                <td class="py-3 px-4">{{ $user->created_at->format('d.m.Y') }}</td>
                                <td class="py-3 px-4">
                                    @if($user->is_admin)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">Администратор</span>
                                    @else
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Пользователь</span>
                                    @endif
                                    
                                    @if($user->email_verified_at)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Подтвержден</span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Не подтвержден</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $users->links() }}
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
                            Нет пользователей в системе.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Фильтрация по статусу и поиск
        document.getElementById('statusFilter').addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('status', this.value);
            window.location.href = currentUrl.toString();
        });

        // Поиск пользователей
        document.getElementById('userSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const nameCell = row.querySelector('td:nth-child(2)').textContent;
                const emailCell = row.querySelector('td:nth-child(3)').textContent;
                
                if (nameCell.toLowerCase().includes(searchTerm) || emailCell.toLowerCase().includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        // Блокировка/разблокировка пользователя
        function toggleUserBlock(userId) {
            fetch(`/admin/users/${userId}/toggle-block`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Показываем уведомление
                    const notification = document.createElement('div');
                    notification.className = `fixed bottom-4 right-4 bg-${data.is_blocked ? 'red' : 'green'}-500 text-white px-4 py-2 rounded-lg z-50`;
                    notification.textContent = data.message;
                    document.body.appendChild(notification);
                    
                    // Удаляем уведомление через 3 секунды
                    setTimeout(() => notification.remove(), 3000);
                    
                    // Обновляем страницу для отображения изменений
                    setTimeout(() => window.location.reload(), 1000);
                }
            });
        }
    </script>
@endsection
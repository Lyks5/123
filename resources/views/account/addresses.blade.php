@extends('layouts.app')

@section('title', 'Мои адреса - ЭкоМаркет')

@section('content')
<!-- Header spacing -->
<div class="h-20"></div>

<div class="bg-gradient-to-b from-eco-50 to-white py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Page header -->
        <div class="mb-10 max-w-3xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-eco-900 mb-3">Мои адреса</h1>
            <p class="text-eco-600 text-lg max-w-2xl mx-auto">Управление адресами доставки и выставления счетов</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
            <!-- Sidebar navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-24">
                    <div class="p-6 border-b border-eco-100">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center text-eco-600 mr-4">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-medium text-eco-900">{{ auth()->user()->name }}</h3>
                                <p class="text-sm text-eco-600">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->is_admin)
                                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Администратор
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <nav class="p-3">
                        <div class="space-y-1">
                            <a href="{{ route('account') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                    <span class="font-medium">Обзор</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('account.orders') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                    <span class="font-medium">Мои заказы</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('account.profile') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <span class="font-medium">Профиль</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('account.wishlists') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    <span class="font-medium">Избранное</span>
                                </div>
                            </a>
                            
                            <a href="{{ route('account.addresses') }}" class="block px-4 py-2.5 rounded-lg bg-eco-100 text-eco-900 transition-colors">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    <span class="font-medium">Адреса</span>
                                </div>
                            </a>
                            
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 rounded-lg text-eco-700 hover:bg-eco-50 transition-colors mt-4 border-t border-eco-100 pt-4">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="3" y1="9" x2="21" y2="9"></line>
                                            <line x1="9" y1="21" x2="9" y2="9"></line>
                                        </svg>
                                        <span class="font-medium">Админ-панель</span>
                                    </div>
                                </a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-eco-100 pt-4">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span class="font-medium">Выйти</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Main content -->
            <div class="lg:col-span-3 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Адреса доставки
                            </h2>
                            <button type="button" class="px-4 py-2 bg-eco-600 hover:bg-eco-700 text-white rounded-lg text-sm font-medium transition-colors" onclick="document.getElementById('add-address-modal').classList.remove('hidden')">
                                Добавить адрес
                            </button>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 p-4 mx-6 mt-6 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="p-6">
                        @if($addresses->count() > 0)
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($addresses as $address)
                                    <div class="border border-eco-100 rounded-lg p-4">
                                        <div class="flex justify-between mb-2">
                                            <h3 class="font-medium text-eco-900">
                                                {{ $address->first_name }} {{ $address->last_name }}
                                                @if($address->is_default)
                                                    <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-eco-100 text-eco-800">
                                                        По умолчанию
                                                    </span>
                                                @endif
                                            </h3>
                                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-lg">
                                                {{ $address->type == 'billing' ? 'Для счетов' : 'Для доставки' }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-eco-600 text-sm">
                                            <p>{{ $address->address_line1 }}</p>
                                            @if($address->address_line2)
                                                <p>{{ $address->address_line2 }}</p>
                                            @endif
                                            <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                            <p>{{ $address->country }}</p>
                                            @if($address->phone)
                                                <p class="mt-1">{{ $address->phone }}</p>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-4 pt-4 border-t border-eco-100 flex justify-end gap-2">
                                            <form method="POST" action="{{ route('account.addresses.delete', $address->id) }}" onsubmit="return confirm('Вы уверены, что хотите удалить этот адрес?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium transition-colors">
                                                    Удалить
                                                </button>
                                            </form>
                                            <button type="button" class="px-3 py-1.5 bg-eco-50 hover:bg-eco-100 text-eco-900 rounded-lg text-sm font-medium transition-colors edit-address-btn" data-address-id="{{ $address->id }}">
                                                Изменить
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="mx-auto w-16 h-16 rounded-full bg-eco-50 flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-eco-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-eco-900 mb-2">У вас еще нет сохраненных адресов</h3>
                                <p class="text-eco-600 mb-6 max-w-md mx-auto">Добавьте адрес доставки или адрес для выставления счетов</p>
                                <button type="button" class="inline-block bg-eco-600 hover:bg-eco-700 text-white py-2 px-4 rounded-lg transition-colors" onclick="document.getElementById('add-address-modal').classList.remove('hidden')">
                                    Добавить адрес
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Edit Address Modal -->
                <div id="edit-address-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg max-w-lg w-full mx-4 max-h-90vh overflow-y-auto">
                        <div class="p-6 border-b border-eco-100 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-eco-900">Редактировать адрес</h3>
                            <button type="button" class="text-eco-500 hover:text-eco-700" onclick="document.getElementById('edit-address-modal').classList.add('hidden')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <form id="edit-address-form" method="POST" class="p-6">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit_first_name" class="block text-sm font-medium text-eco-700 mb-1">Имя</label>
                                        <input type="text" name="first_name" id="edit_first_name" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                    <div>
                                        <label for="edit_last_name" class="block text-sm font-medium text-eco-700 mb-1">Фамилия</label>
                                        <input type="text" name="last_name" id="edit_last_name" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="edit_address_line1" class="block text-sm font-medium text-eco-700 mb-1">Адрес</label>
                                    <input type="text" name="address_line1" id="edit_address_line1" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                </div>
                                
                                <div>
                                    <label for="edit_address_line2" class="block text-sm font-medium text-eco-700 mb-1">Дополнительная информация (кв./офис)</label>
                                    <input type="text" name="address_line2" id="edit_address_line2" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit_city" class="block text-sm font-medium text-eco-700 mb-1">Город</label>
                                        <input type="text" name="city" id="edit_city" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                    <div>
                                        <label for="edit_state" class="block text-sm font-medium text-eco-700 mb-1">Область/Регион</label>
                                        <input type="text" name="state" id="edit_state" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit_postal_code" class="block text-sm font-medium text-eco-700 mb-1">Почтовый индекс</label>
                                        <input type="text" name="postal_code" id="edit_postal_code" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                    <div>
                                        <label for="edit_country" class="block text-sm font-medium text-eco-700 mb-1">Страна</label>
                                        <input type="text" name="country" id="edit_country" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="edit_phone" class="block text-sm font-medium text-eco-700 mb-1">Телефон</label>
                                    <input type="text" name="phone" id="edit_phone" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-eco-700 mb-1">Тип адреса</label>
                                    <div class="flex gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="type" value="shipping" class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-eco-300">
                                            <span class="ml-2 text-eco-700">Для доставки</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="type" value="billing" class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-eco-300">
                                            <span class="ml-2 text-eco-700">Для счетов</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_default" value="1" class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-eco-300">
                                        <span class="ml-2 text-eco-700">Установить как адрес по умолчанию</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" class="px-4 py-2 border border-eco-200 text-eco-700 rounded-lg hover:bg-eco-50 transition-colors" onclick="document.getElementById('edit-address-modal').classList.add('hidden')">
                                    Отмена
                                </button>
                                <button type="submit" class="px-4 py-2 bg-eco-600 text-white rounded-lg hover:bg-eco-700 transition-colors">
                                    Сохранить изменения
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Add Address Modal -->
                <div id="add-address-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-lg max-w-lg w-full mx-4 max-h-90vh overflow-y-auto">
                        <div class="p-6 border-b border-eco-100 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-eco-900">Добавить новый адрес</h3>
                            <button type="button" class="text-eco-500 hover:text-eco-700" onclick="document.getElementById('add-address-modal').classList.add('hidden')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('account.addresses.store') }}" class="p-6">
                            @csrf
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-eco-700 mb-1">Имя</label>
                                        <input type="text" name="first_name" id="first_name" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-eco-700 mb-1">Фамилия</label>
                                        <input type="text" name="last_name" id="last_name" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="address_line1" class="block text-sm font-medium text-eco-700 mb-1">Адрес</label>
                                    <input type="text" name="address_line1" id="address_line1" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                </div>
                                
                                <div>
                                    <label for="address_line2" class="block text-sm font-medium text-eco-700 mb-1">Дополнительная информация (кв./офис)</label>
                                    <input type="text" name="address_line2" id="address_line2" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-eco-700 mb-1">Город</label>
                                        <input type="text" name="city" id="city" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-eco-700 mb-1">Область/Регион</label>
                                        <input type="text" name="state" id="state" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-eco-700 mb-1">Почтовый индекс</label>
                                        <input type="text" name="postal_code" id="postal_code" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                    <div>
                                        <label for="country" class="block text-sm font-medium text-eco-700 mb-1">Страна</label>
                                        <input type="text" name="country" id="country" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500" required>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-eco-700 mb-1">Телефон</label>
                                    <input type="text" name="phone" id="phone" class="w-full px-3 py-2 border border-eco-200 rounded-lg focus:ring-2 focus:ring-eco-500 focus:border-eco-500">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-eco-700 mb-1">Тип адреса</label>
                                    <div class="flex gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="type" value="shipping" class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-eco-300" checked>
                                            <span class="ml-2 text-eco-700">Для доставки</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="type" value="billing" class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-eco-300">
                                            <span class="ml-2 text-eco-700">Для счетов</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_default" value="1" class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-eco-300">
                                        <span class="ml-2 text-eco-700">Установить как адрес по умолчанию</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" class="px-4 py-2 border border-eco-200 text-eco-700 rounded-lg hover:bg-eco-50 transition-colors" onclick="document.getElementById('add-address-modal').classList.add('hidden')">
                                    Отмена
                                </button>
                                <button type="submit" class="px-4 py-2 bg-eco-600 text-white rounded-lg hover:bg-eco-700 transition-colors">
                                    Сохранить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('resources/js/addresses.js') }}"></script>
@endpush
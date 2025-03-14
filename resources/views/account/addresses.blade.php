
@extends('layouts.app')

@section('title', 'Адреса доставки - ЭкоМаркет')

@section('content')
<div class="bg-gray-50 py-8 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-eco-900">Адреса доставки</h1>
            <p class="text-eco-700">Управление адресами для доставки заказов</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Боковое меню -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-eco-100 rounded-full flex items-center justify-center text-eco-700">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-4">
                        <h3 class="font-medium text-eco-900">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-eco-600">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('account') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Главная
                    </a>
                    <a href="{{ route('account.orders') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        Мои заказы
                    </a>
                    <a href="{{ route('account.profile') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Профиль
                    </a>
                    <a href="{{ route('account.addresses') }}" class="block px-4 py-2 rounded-md bg-eco-50 text-eco-900 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Адреса
                    </a>
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="3" y1="9" x2="21" y2="9"></line>
                            <line x1="9" y1="21" x2="9" y2="9"></line>
                        </svg>
                        Админ-панель
                    </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 rounded-md text-eco-700 hover:bg-eco-50 hover:text-eco-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Выйти
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Основное содержимое -->
            <div class="col-span-1 md:col-span-3 space-y-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-eco-900">Сохраненные адреса</h2>
                            <p class="text-sm text-eco-600">Управление адресами для доставки заказов</p>
                        </div>
                        <button 
                            type="button" 
                            onclick="document.getElementById('add-address-form').classList.toggle('hidden')"
                            class="px-4 py-2 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                        >
                            Добавить адрес
                        </button>
                    </div>
                    
                    <div id="add-address-form" class="p-6 border-b border-gray-200 hidden">
                        <h3 class="text-lg font-medium text-eco-900 mb-4">Новый адрес</h3>
                        
                        <form action="{{ route('account.addresses.store') }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="address_line1" class="block text-sm font-medium text-eco-800 mb-1">
                                        Адрес строка 1 <span class="text-red-600">*</span>
                                    </label>
                                    <input 
                                        id="address_line1" 
                                        name="address_line1" 
                                        type="text" 
                                        value="{{ old('address_line1') }}" 
                                        required
                                        class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    >
                                    @error('address_line1')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="address_line2" class="block text-sm font-medium text-eco-800 mb-1">
                                        Адрес строка 2
                                    </label>
                                    <input 
                                        id="address_line2" 
                                        name="address_line2" 
                                        type="text" 
                                        value="{{ old('address_line2') }}" 
                                        class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    >
                                    @error('address_line2')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-eco-800 mb-1">
                                        Город <span class="text-red-600">*</span>
                                    </label>
                                    <input 
                                        id="city" 
                                        name="city" 
                                        type="text" 
                                        value="{{ old('city') }}" 
                                        required
                                        class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    >
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="state" class="block text-sm font-medium text-eco-800 mb-1">
                                        Область/край <span class="text-red-600">*</span>
                                    </label>
                                    <input 
                                        id="state" 
                                        name="state" 
                                        type="text" 
                                        value="{{ old('state') }}" 
                                        required
                                        class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    >
                                    @error('state')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-eco-800 mb-1">
                                        Почтовый индекс <span class="text-red-600">*</span>
                                    </label>
                                    <input 
                                        id="postal_code" 
                                        name="postal_code" 
                                        type="text" 
                                        value="{{ old('postal_code') }}" 
                                        required
                                        class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    >
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="country" class="block text-sm font-medium text-eco-800 mb-1">
                                        Страна <span class="text-red-600">*</span>
                                    </label>
                                    <input 
                                        id="country" 
                                        name="country" 
                                        type="text" 
                                        value="{{ old('country', 'Россия') }}" 
                                        required
                                        class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                    >
                                    @error('country')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center">
                                    <input 
                                        id="is_default" 
                                        name="is_default" 
                                        type="checkbox" 
                                        value="1" 
                                        class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-eco-300 rounded"
                                    >
                                    <label for="is_default" class="ml-2 block text-sm text-eco-700">
                                        Сделать основным адресом доставки
                                    </label>
                                </div>
                            </div>
                            
                            <div class="pt-4 flex justify-end space-x-4">
                                <button 
                                    type="button" 
                                    onclick="document.getElementById('add-address-form').classList.add('hidden')"
                                    class="px-4 py-2 bg-white border border-eco-300 text-eco-700 font-medium rounded-md hover:bg-eco-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                                >
                                    Отмена
                                </button>
                                <button 
                                    type="submit" 
                                    class="px-4 py-2 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                                >
                                    Сохранить адрес
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="p-6">
                        @if(session('success'))
                            <div class="bg-green-50 text-green-800 p-4 rounded-md mb-6">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if($addresses->count() > 0)
                            <div class="space-y-6">
                                @foreach($addresses as $address)
                                    <div class="border border-eco-200 rounded-md p-4 relative">
                                        @if($address->is_default)
                                            <span class="absolute top-4 right-4 px-2 py-1 text-xs font-medium bg-eco-100 text-eco-800 rounded-full">
                                                Основной
                                            </span>
                                        @endif
                                        
                                        <div class="mb-3">
                                            <p class="text-eco-900">{{ $address->address_line1 }}</p>
                                            @if($address->address_line2)
                                                <p class="text-eco-900">{{ $address->address_line2 }}</p>
                                            @endif
                                            <p class="text-eco-900">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                            <p class="text-eco-900">{{ $address->country }}</p>
                                        </div>
                                        
                                        <div class="flex space-x-4">
                                            <button 
                                                type="button" 
                                                onclick="document.getElementById('edit-address-{{ $address->id }}').classList.toggle('hidden')"
                                                class="text-sm text-eco-600 hover:text-eco-700"
                                            >
                                                Редактировать
                                            </button>
                                            
                                            <form action="{{ route('account.addresses.delete', $address) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот адрес?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-700">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <div id="edit-address-{{ $address->id }}" class="mt-4 pt-4 border-t border-eco-200 hidden">
                                            <h4 class="text-md font-medium text-eco-900 mb-4">Редактировать адрес</h4>
                                            
                                            <form action="{{ route('account.addresses.update', $address) }}" method="POST" class="space-y-4">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="edit_address_line1_{{ $address->id }}" class="block text-sm font-medium text-eco-800 mb-1">
                                                            Адрес строка 1 <span class="text-red-600">*</span>
                                                        </label>
                                                        <input 
                                                            id="edit_address_line1_{{ $address->id }}" 
                                                            name="address_line1" 
                                                            type="text" 
                                                            value="{{ old('address_line1', $address->address_line1) }}" 
                                                            required
                                                            class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                                        >
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="edit_address_line2_{{ $address->id }}" class="block text-sm font-medium text-eco-800 mb-1">
                                                            Адрес строка 2
                                                        </label>
                                                        <input 
                                                            id="edit_address_line2_{{ $address->id }}" 
                                                            name="address_line2" 
                                                            type="text" 
                                                            value="{{ old('address_line2', $address->address_line2) }}" 
                                                            class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                                        >
                                                    </div>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="edit_city_{{ $address->id }}" class="block text-sm font-medium text-eco-800 mb-1">
                                                            Город <span class="text-red-600">*</span>
                                                        </label>
                                                        <input 
                                                            id="edit_city_{{ $address->id }}" 
                                                            name="city" 
                                                            type="text" 
                                                            value="{{ old('city', $address->city) }}" 
                                                            required
                                                            class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                                        >
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="edit_state_{{ $address->id }}" class="block text-sm font-medium text-eco-800 mb-1">
                                                            Область/край <span class="text-red-600">*</span>
                                                        </label>
                                                        <input 
                                                            id="edit_state_{{ $address->id }}" 
                                                            name="state" 
                                                            type="text" 
                                                            value="{{ old('state', $address->state) }}" 
                                                            required
                                                            class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                                        >
                                                    </div>
                                                </div>
                                                
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label for="edit_postal_code_{{ $address->id }}" class="block text-sm font-medium text-eco-800 mb-1">
                                                            Почтовый индекс <span class="text-red-600">*</span>
                                                        </label>
                                                        <input 
                                                            id="edit_postal_code_{{ $address->id }}" 
                                                            name="postal_code" 
                                                            type="text" 
                                                            value="{{ old('postal_code', $address->postal_code) }}" 
                                                            required
                                                            class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                                        >
                                                    </div>
                                                    
                                                    <div>
                                                        <label for="edit_country_{{ $address->id }}" class="block text-sm font-medium text-eco-800 mb-1">
                                                            Страна <span class="text-red-600">*</span>
                                                        </label>
                                                        <input 
                                                            id="edit_country_{{ $address->id }}" 
                                                            name="country" 
                                                            type="text" 
                                                            value="{{ old('country', $address->country) }}" 
                                                            required
                                                            class="w-full rounded-md border-eco-300 focus:border-eco-500 focus:ring-eco-500"
                                                        >
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <div class="flex items-center">
                                                        <input 
                                                            id="edit_is_default_{{ $address->id }}" 
                                                            name="is_default" 
                                                            type="checkbox" 
                                                            value="1" 
                                                            {{ $address->is_default ? 'checked' : '' }}
                                                            class="h-4 w-4 text-eco-600 focus:ring-eco-500 border-eco-300 rounded"
                                                        >
                                                        <label for="edit_is_default_{{ $address->id }}" class="ml-2 block text-sm text-eco-700">
                                                            Сделать основным адресом доставки
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="pt-4 flex justify-end space-x-4">
                                                    <button 
                                                        type="button" 
                                                        onclick="document.getElementById('edit-address-{{ $address->id }}').classList.add('hidden')"
                                                        class="px-4 py-2 bg-white border border-eco-300 text-eco-700 font-medium rounded-md hover:bg-eco-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                                                    >
                                                        Отмена
                                                    </button>
                                                    <button 
                                                        type="submit" 
                                                        class="px-4 py-2 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500"
                                                    >
                                                        Сохранить изменения
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-eco-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                <p class="mt-4 text-eco-600">У вас пока нет сохраненных адресов.</p>
                                <button 
                                    type="button" 
                                    onclick="document.getElementById('add-address-form').classList.remove('hidden')"
                                    class="mt-2 text-eco-600 hover:text-eco-700 font-medium"
                                >
                                    Добавить новый адрес →
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
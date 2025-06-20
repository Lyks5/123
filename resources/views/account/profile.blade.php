@extends('layouts.app')

@section('title', 'Мой профиль - ЭкоМаркет')

@section('content')
<!-- Header spacing -->
<div class="h-20"></div>

<div class="bg-gradient-to-b from-eco-50 to-white py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <!-- Page header -->
        <div class="mb-10 max-w-3xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-eco-900 mb-3">Мой профиль</h1>
            <p class="text-eco-600 text-lg max-w-2xl mx-auto">Управляйте личными данными и просматривайте заказы</p>
@if(isset($eco_rating))
    <div class="mb-4 text-center">
        <span class="font-semibold text-eco-700">Ваш эко-рейтинг: </span>
        <span class="font-bold text-eco-900">{{ $eco_rating }}</span>
    </div>
@endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
            <!-- Sidebar navigation -->
            @include('account.partials.sidebar')

            <!-- Main content -->
            <div class="lg:col-span-3 space-y-8">
                @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-lg mb-6 animate-fade-in">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                <!-- Personal Info -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Личные данные
                        </h2>
                        <p class="text-sm text-eco-600 mt-1">Обновите основную информацию о себе</p>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            <!-- Avatar Upload -->
                            <div class="flex items-center space-x-6 mb-6">
                                <div class="shrink-0">
                                    <div class="relative w-24 h-24">
                                        <img class="w-24 h-24 rounded-full object-cover" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" alt="{{ $user->name }}">
                                        <label for="avatar" class="absolute bottom-0 right-0 bg-eco-600 text-white rounded-full p-2 cursor-pointer hover:bg-eco-700 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                                                <circle cx="12" cy="13" r="4"/>
                                            </svg>
                                        </label>
                                        <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*">
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-eco-900 font-medium">Фото профиля</h3>
                                    <p class="text-sm text-eco-600">JPG, PNG или GIF. Максимальный размер 2MB</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Имя
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        id="name" 
                                        name="name" 
                                        type="text" 
                                        required
                                        minlength="2"
                                        maxlength="255"
                                        pattern="[A-Za-zА-Яа-яЁё\s-]+"
                                        value="{{ old('name', $user->name) }}" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('name')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Email
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        required
                                        value="{{ old('email', $user->email) }}" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('email')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Телефон
                                    </label>
                                    <input 
                                        id="phone" 
                                        name="phone" 
                                        type="tel"
                                        pattern="^\+7\d{10}$"
                                        placeholder="+7XXXXXXXXXX"
                                        value="{{ old('phone', $user->phone) }}" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('phone')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Дата рождения
                                    </label>
                                    <input 
                                        id="birth_date" 
                                        name="birth_date" 
                                        type="date" 
                                        max="{{ date('Y-m-d') }}"
                                        value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('birth_date')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Пол
                                    </label>
                                    <select 
                                        id="gender" 
                                        name="gender" 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                        <option value="">Не указано</option>
                                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Мужской</option>
                                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Женский</option>
                                        <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Другой</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                
                            
                                <div class="md:col-span-2">
                                    <label for="bio" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        О себе
                                    </label>
                                    <textarea 
                                        id="bio" 
                                        name="bio" 
                                        rows="3"
                                        maxlength="1000"
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="pt-2">
                                <button type="submit" class="px-4 py-2.5 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                    Сохранить изменения
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Password Change -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            Сменить пароль
                        </h2>
                        <p class="text-sm text-eco-600 mt-1">Обновите пароль для обеспечения безопасности аккаунта</p>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('account.password.update') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-eco-800 mb-1.5">
                                    Текущий пароль
                                    <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    id="current_password" 
                                    name="current_password" 
                                    type="password" 
                                    required
                                    class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                >
                                @error('current_password')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Новый пароль
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        id="password" 
                                        name="password" 
                                        type="password"
                                        required
                                        minlength="6"
                                        title="Минимум 6 символов"
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                    @error('password')
                                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-eco-800 mb-1.5">
                                        Подтверждение нового пароля
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        type="password"
                                        required 
                                        class="w-full rounded-lg border-eco-200 focus:border-eco-500 focus:ring focus:ring-eco-500 focus:ring-opacity-50 transition-colors"
                                    >
                                </div>
                            </div>
                            
                            <div class="pt-2">
                                <button type="submit" class="px-4 py-2.5 bg-eco-600 hover:bg-eco-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-eco-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                    Обновить пароль
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Eco Impact -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-eco-100">
                        <h2 class="text-xl font-semibold text-eco-900 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-eco-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
                                <path d="M19 7v4h-4"></path>
                            </svg>
                            Ваш эко-вклад
                        </h2>
                        <p class="text-sm text-eco-600 mt-1">Отслеживайте положительное влияние ваших покупок на окружающую среду</p>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-eco-50 p-4 rounded-lg">
                                <div class="text-eco-600 text-sm font-medium mb-1">Углеродный след</div>
                                <div class="text-2xl font-bold text-eco-800">
                                    {{ number_format($user->getTotalEcoImpact()['carbon_saved'] ?? 0, 1) }} кг
                                </div>
                                <div class="text-xs text-eco-500 mt-1">CO2 сэкономлено</div>
                            </div>
                            
                            <div class="bg-eco-50 p-4 rounded-lg">
                                <div class="text-eco-600 text-sm font-medium mb-1">Пластик</div>
                                <div class="text-2xl font-bold text-eco-800">
                                    {{ number_format($user->getTotalEcoImpact()['plastic_saved'] ?? 0, 1) }} кг
                                </div>
                                <div class="text-xs text-eco-500 mt-1">пластика не использовано</div>
                            </div>
                            
                            <div class="bg-eco-50 p-4 rounded-lg">
                                <div class="text-eco-600 text-sm font-medium mb-1">Вода</div>
                                <div class="text-2xl font-bold text-eco-800">
                                    {{ number_format($user->getTotalEcoImpact()['water_saved'] ?? 0, 1) }} л
                                </div>
                                <div class="text-xs text-eco-500 mt-1">воды сэкономлено</div>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-eco-100">
                            <p class="text-eco-700 text-sm">
                                Эти данные отражают экологический эффект от ваших покупок на нашей платформе. 
                                Спасибо, что помогаете делать мир лучше!
                            </p>
                            
                            <div class="mt-4 flex items-center">
                                <div class="h-2.5 w-full bg-gray-200 rounded-full overflow-hidden">
                                    @php
                                        $ecoScore = $user->eco_impact_score ?? 0;
                                        $scorePercent = min(100, max(0, $ecoScore * 10)); // Convert score to percentage (0-10 → 0-100%)
                                    @endphp
                                    <div class="h-full bg-eco-500 rounded-full" style="width: {{ $scorePercent }}%"></div>
                                </div>
                                <div class="ml-4 min-w-16 text-center">
                                    <span class="text-eco-900 font-semibold">{{ $ecoScore }}/10</span>
                                </div>
                            </div>
                            
                            <div class="mt-1 text-xs text-eco-600">Ваш эко-рейтинг</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('avatar').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        if (e.target.files[0].size > 2 * 1024 * 1024) {
            alert('Размер файла превышает 2MB');
            e.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.shrink-0 img').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush
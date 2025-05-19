<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Админ-панель ЭкоМаркет</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Проверяем сохраненную тему
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        /* Фиксы для темной темы в компонентах, где нет классов для темной темы */
        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter,
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_processing,
        .dark .dataTables_wrapper .dataTables_paginate {
            color: #e5e7eb !important;
        }
        
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #e5e7eb !important;
        }
        
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #1f2937 !important;
            color: white !important;
            border-color: #374151 !important;
        }
        
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #374151 !important;
            color: white !important;
            border-color: #4b5563 !important;
        }
        
        .dark table.dataTable tbody tr {
            background-color: #1f2937 !important;
        }
        
        .dark table.dataTable.stripe tbody tr.odd {
            background-color: #111827 !important;
        }
        
        .dark table.dataTable.hover tbody tr:hover, 
        .dark table.dataTable.hover tbody tr.odd:hover {
            background-color: #374151 !important;
        }
        
        .dark table.dataTable thead th,
        .dark table.dataTable thead td {
            border-bottom: 1px solid #4b5563 !important;
        }
        
        .dark table.dataTable.row-border tbody th, 
        .dark table.dataTable.row-border tbody td, 
        .dark table.dataTable.display tbody th, 
        .dark table.dataTable.display tbody td {
            border-top: 1px solid #4b5563 !important;
        }
        
        .dark .select2-dropdown,
        .dark .select2-container--default .select2-selection--single,
        .dark .select2-container--default .select2-selection--multiple {
            background-color: #1f2937 !important;
            border-color: #4b5563 !important;
            color: #e5e7eb !important;
        }
        
        .dark .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #374151 !important;
        }
        
        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4b5563 !important;
            color: white !important;
        }
        
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e5e7eb !important;
        }
        
        .dark .ck.ck-editor__main>.ck-editor__editable {
            background: #1f2937 !important;
            color: #e5e7eb !important;
            border-color: #4b5563 !important;
        }
        
        .dark .ck.ck-toolbar {
            background: #111827 !important;
            border-color: #4b5563 !important;
        }
        
        .dark .ck.ck-button,
        .dark .ck.ck-button.ck-on {
            color: #e5e7eb !important;
            background: #1f2937 !important;
        }
        
        .dark .ck.ck-button:hover,
        .dark .ck.ck-button.ck-on:hover {
            background: #374151 !important;
        }
        
        /* Универсальный фикс для других элементов форм */
        .dark input[type="text"],
        .dark input[type="email"],
        .dark input[type="password"],
        .dark input[type="number"],
        .dark input[type="date"],
        .dark input[type="datetime-local"],
        .dark textarea,
        .dark select {
            background-color: #1f2937 !important;
            color: #e5e7eb !important;
            border-color: #4b5563 !important;
        }
        
        .dark input[type="text"]:focus,
        .dark input[type="email"]:focus,
        .dark input[type="password"]:focus,
        .dark input[type="number"]:focus,
        .dark input[type="date"]:focus,
        .dark input[type="datetime-local"]:focus,
        .dark textarea:focus,
        .dark select:focus {
            border-color: #60a5fa !important;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <!-- Боковое меню -->
        <aside class="bg-white dark:bg-gray-800 w-64 min-h-screen shadow-md hidden md:block">
            <div class="p-6 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center">
                    <span class="text-2xl font-bold text-eco-700 dark:text-eco-400">ЭкоМаркет</span>
                </a>
                <button id="theme-toggle" type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg id="theme-toggle-dark-icon" class="w-5 h-5 text-gray-700 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg id="theme-toggle-light-icon" class="w-5 h-5 text-gray-200 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>
            
            <nav class="mt-5">
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-eco-100 dark:bg-eco-900 text-eco-700 dark:text-eco-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Дашборд</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.analytics') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.analytics') ? 'bg-eco-100 dark:bg-eco-900 text-eco-700 dark:text-eco-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Аналитика</span>
                    </div>
                </a>
                
                <div class="relative">
                <button 
                    @click="catalogOpen = !catalogOpen" 
                    class="flex items-center justify-between w-full px-3 py-2 my-1 rounded-md text-sm font-medium {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.attributes.*') || request()->routeIs('admin.eco-features.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-600 dark:text-eco-400' : 'text-gray-600 dark:text-gray-300 hover:bg-eco-50 dark:hover:bg-eco-900/50 hover:text-eco-600 dark:hover:text-eco-400' }}"
                >
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span>Каталог</span>
                    </div>
                    <svg 
                        class="h-5 w-5 transition-transform" 
                        :class="{'transform rotate-180': catalogOpen}"
                        xmlns="http://www.w3.org/2000/svg" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2" 
                        stroke-linecap="round" 
                        stroke-linejoin="round"
                    >
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                
                <div x-show="catalogOpen" x-transition class="pl-8 mt-1 space-y-1">
                    <!-- Products -->
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-600 dark:text-eco-400' : 'text-gray-600 dark:text-gray-300 hover:bg-eco-50 dark:hover:bg-eco-900/50 hover:text-eco-600 dark:hover:text-eco-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span>Товары</span>
                    </a>
                    
                    <!-- Categories -->
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-600 dark:text-eco-400' : 'text-gray-600 dark:text-gray-300 hover:bg-eco-50 dark:hover:bg-eco-900/50 hover:text-eco-600 dark:hover:text-eco-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Категории</span>
                    </a>
                    
                    <!-- Attributes -->
                    <a href="{{ route('admin.attributes.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.attributes.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-600 dark:text-eco-400' : 'text-gray-600 dark:text-gray-300 hover:bg-eco-50 dark:hover:bg-eco-900/50 hover:text-eco-600 dark:hover:text-eco-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <span>Атрибуты</span>
                    </a>
                    
                    <!-- Eco Features -->
                    <a href="{{ route('admin.eco-features.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.eco-features.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-600 dark:text-eco-400' : 'text-gray-600 dark:text-gray-300 hover:bg-eco-50 dark:hover:bg-eco-900/50 hover:text-eco-600 dark:hover:text-eco-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                        </svg>
                        <span>Эко-характеристики</span>
                    </a>
                </div>
            </div>
                
                <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-700 dark:text-eco-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Категории</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.orders.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-700 dark:text-eco-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Заказы</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-700 dark:text-eco-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Пользователи</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.blog.posts.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.blog.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-700 dark:text-eco-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span>Блог</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.blog.categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.blog.categories.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-700 dark:text-eco-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Категории блога</span>
                    </div>
                </a>

                <a href="{{ route('admin.initiatives.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.initiatives.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-700 dark:text-eco-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Эко-инициативы</span>
                    </div>
                </a>
                
                <a href="{{ route('admin.contact-requests.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.contacts.*') ? 'bg-eco-100 dark:bg-eco-900 text-eco-700 dark:text-eco-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300' }}">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span>Обращения</span>
                    </div>
                </a>
                
                
            </nav>
            
            <div class="mt-auto p-4 border-t border-gray-200 dark:border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left block py-2 px-4 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300 transition duration-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Выйти</span>
                        </div>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Мобильное меню -->
        <div class="md:hidden fixed bottom-0 left-0 z-50 w-full border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex justify-around">
                <a href="{{ route('admin.dashboard') }}" class="p-3 text-center {{ request()->routeIs('admin.dashboard') ? 'text-eco-700 dark:text-eco-400' : 'text-gray-500 dark:text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-xs">Дашборд</span>
                </a>
                
                <a href="{{ route('admin.analytics') }}" class="p-3 text-center {{ request()->routeIs('admin.analytics') ? 'text-eco-700 dark:text-eco-400' : 'text-gray-500 dark:text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs">Аналитика</span>
                </a>
                
                <a href="{{ route('admin.products.index') }}" class="p-3 text-center {{ request()->routeIs('admin.products.*') ? 'text-eco-700 dark:text-eco-400' : 'text-gray-500 dark:text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="text-xs">Товары</span>
                </a>
                
                <a href="{{ route('admin.orders.index') }}" class="p-3 text-center {{ request()->routeIs('admin.orders.*') ? 'text-eco-700 dark:text-eco-400' : 'text-gray-500 dark:text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="text-xs">Заказы</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="p-3 text-center {{ request()->routeIs('admin.users.*') ? 'text-eco-700 dark:text-eco-400' : 'text-gray-500 dark:text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-xs">Пользователи</span>
                </a>
                
                <button id="mobile-theme-toggle" type="button" class="p-3 text-center text-gray-500 dark:text-gray-400">
                    <svg id="mobile-dark-icon" class="h-6 w-6 mx-auto dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg id="mobile-light-icon" class="h-6 w-6 mx-auto hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-xs">Тема</span>
                </button>
            </div>
        </div>
        
        <!-- Основной контент -->
        <main class="flex-1 bg-gray-100 dark:bg-gray-900">
            <div class="py-6 md:py-12 px-4 md:px-8">
                @if(session('success'))
                    <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-6" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-6" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-6" role="alert">
                        <p class="font-bold">Произошла ошибка:</p>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <script>
        // Функция переключения темной темы
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
        }
        
        // Обработчики событий для кнопок переключения темы
        document.getElementById('theme-toggle').addEventListener('click', toggleDarkMode);
        document.getElementById('mobile-theme-toggle').addEventListener('click', toggleDarkMode);
    </script>
</body>
</html>
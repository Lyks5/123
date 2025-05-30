<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Админ-панель | {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        #layoutSidenav {
            display: flex;
        }
        #layoutSidenav_nav {
            flex-basis: 225px;
            flex-shrink: 0;
            transition: transform .15s ease-in-out;
            z-index: 1038;
            transform: translateX(0);
        }
        #layoutSidenav_content {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
            flex-grow: 1;
            min-height: calc(100vh - 56px);
            margin-left: -225px;
        }
        .sb-sidenav-toggled #layoutSidenav_nav {
            transform: translateX(-225px);
        }
        .sb-sidenav-toggled #layoutSidenav_content {
            margin-left: 0;
        }
        @media (min-width: 992px) {
            #layoutSidenav_nav {
                transform: translateX(0);
            }
            #layoutSidenav_content {
                margin-left: 0;
                transition: margin .15s ease-in-out;
            }
            .sb-sidenav-toggled #layoutSidenav_nav {
                transform: translateX(0);
            }
            .sb-sidenav-toggled #layoutSidenav_content {
                margin-left: 225px;
            }
        }
        .sb-nav-fixed #layoutSidenav #layoutSidenav_nav {
            width: 225px;
            height: 100vh;
            z-index: 1038;
        }
        .sb-nav-fixed #layoutSidenav #layoutSidenav_nav .sb-sidenav {
            padding-top: 56px;
        }
        .sb-nav-fixed #layoutSidenav #layoutSidenav_nav .sb-sidenav .sb-sidenav-menu {
            overflow-y: auto;
        }
        .sb-nav-fixed #layoutSidenav #layoutSidenav_content {
            padding-left: 225px;
            top: 56px;
        }
        .nav .nav-link .sb-nav-link-icon {
            margin-right: 0.5rem;
        }
        .sb-topnav {
            padding-left: 0;
            height: 56px;
            z-index: 1039;
        }
        .sb-sidenav {
            display: flex;
            flex-direction: column;
            height: 100%;
            flex-wrap: nowrap;
        }
        .sb-sidenav .sb-sidenav-menu {
            flex-grow: 1;
        }
        .sb-sidenav .sb-sidenav-menu .nav {
            flex-direction: column;
            flex-wrap: nowrap;
        }
        .sb-sidenav .sb-sidenav-menu .nav .sb-sidenav-menu-heading {
            padding: 1.75rem 1rem 0.75rem;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .sb-sidenav .sb-sidenav-menu .nav .nav-link {
            display: flex;
            align-items: center;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            position: relative;
        }
        .sb-sidenav .sb-sidenav-footer {
            padding: 0.75rem;
        }
        .sb-sidenav-dark {
            background-color: #212529;
            color: rgba(255, 255, 255, 0.5);
        }
        .sb-sidenav-dark .sb-sidenav-menu .sb-sidenav-menu-heading {
            color: rgba(255, 255, 255, 0.25);
        }
        .sb-sidenav-dark .sb-sidenav-menu .nav-link {
            color: rgba(255, 255, 255, 0.5);
        }
        .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover {
            color: #fff;
        }
        .sb-sidenav-dark .sb-sidenav-menu .nav-link.active {
            color: #fff;
        }
        .sb-sidenav-dark .sb-sidenav-footer {
            background-color: #343a40;
        }
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter, 
        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_processing, 
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="{{ route('admin.dashboard') }}">{{ config('app.name') }}</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="bi bi-list"></i>
        </button>
        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></div>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-fill"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">На сайт</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                           Выйти
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Главная</div>
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="bi bi-speedometer2"></i></div>
                            Панель управления
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.analytics.index') ? 'active' : '' }}" href="{{ route('admin.analytics.index') }}">
                            <div class="sb-nav-link-icon"><i class="bi bi-graph-up"></i></div>
                            Аналитика
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Каталог</div>
                        <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                            <div class="sb-nav-link-icon"><i class="bi bi-box-seam"></i></div>
                            Товары
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                            <div class="sb-nav-link-icon"><i class="bi bi-folder"></i></div>
                            Категории
                        </a>
                        <a class="nav-link {{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}" href="{{ route('admin.attributes.index') }}">
                            <div class="sb-nav-link-icon"><i class="bi bi-tags"></i></div>
                            Атрибуты
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Заказы</div>
                        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <div class="sb-nav-link-icon"><i class="bi bi-cart"></i></div>
                            Заказы
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Пользователи</div>
                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <div class="sb-nav-link-icon"><i class="bi bi-people"></i></div>
                            Пользователи
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Вы вошли как:</div>
                    {{ Auth::user()->name }}
                </div>
            </nav>
        </div>
        
        <!-- Основной контент -->
        <main class="flex-1 bg-gray-100 dark:bg-gray-900 overflow-y-auto">
            <div class="py-6 px-4 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4" role="alert">
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="mb-4 bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4" role="alert">
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4" role="alert">
                        <p class="font-bold">Произошла ошибка:</p>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </div>
            
            <footer class="mt-auto bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Все права защищены.
                    </p>
                </div>
            </footer>
        </main>
    </div>

    <!-- DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.tailwind.min.js"></script>
    
    <script>
        // Переключение темной темы
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
        }
        
        // Инициализация DataTables
        if (document.querySelector('.datatable')) {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.1/i18n/ru.json'
                }
            });
        }
        
        // Обработчики событий
        document.addEventListener('DOMContentLoaded', () => {
            // Переключатель темы
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                themeToggle.addEventListener('click', toggleDarkMode);
            }
            
            // Пользовательское меню
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');
            
            if (userMenuButton && userMenu) {
                userMenuButton.addEventListener('click', () => {
                    userMenu.classList.toggle('hidden');
                });
                
                // Закрытие меню при клике вне его
                document.addEventListener('click', (event) => {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
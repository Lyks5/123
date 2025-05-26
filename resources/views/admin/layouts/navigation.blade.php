<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold text-gray-800">
                        Админ-панель
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('admin.products.index') }}"
                        class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900">
                        Товары
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Категории
                    </a>
                    <a href="{{ route('admin.attributes.index') }}"
                        class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700">
                        Атрибуты
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="ml-3 relative">
                    <div class="flex items-center">
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="ml-4 text-sm text-gray-500 hover:text-gray-700">
                            Выход
                        </a>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="sm:hidden hidden mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('admin.products.index') }}"
                class="block pl-3 pr-4 py-2 text-base font-medium text-gray-700">
                Товары
            </a>
            <a href="{{ route('admin.categories.index') }}"
                class="block pl-3 pr-4 py-2 text-base font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                Категории
            </a>
            <a href="{{ route('admin.attributes.index') }}"
                class="block pl-3 pr-4 py-2 text-base font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                Атрибуты
            </a>
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.querySelector('.mobile-menu-button');
        const menu = document.querySelector('.mobile-menu');
        
        button.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    });
</script>
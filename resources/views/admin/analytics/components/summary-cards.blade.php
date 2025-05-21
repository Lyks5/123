<!-- Сводка -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    <!-- Карточка выручки -->
    @include('admin.analytics.components.cards.revenue')
    
    <!-- Карточка заказов -->
    @include('admin.analytics.components.cards.orders')
    
    <!-- Карточка пользователей -->
    @include('admin.analytics.components.cards.users')
    
    <!-- Карточка конверсии -->
    @include('admin.analytics.components.cards.conversion')
</div>
<div class="list-group shadow">
    <div class="list-group-item bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i> Мой аккаунт</h5>
    </div>
    <a href="{{ route('account') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Панель управления
    </a>
    <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.profile') ? 'active' : '' }}">
        <i class="bi bi-person me-2"></i> Профиль
    </a>
    <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.orders') ? 'active' : '' }}">
        <i class="bi bi-box-seam me-2"></i> Заказы
    </a>
    <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.addresses') ? 'active' : '' }}">
        <i class="bi bi-geo-alt me-2"></i> Адреса доставки
    </a>
    <a href="{{ route('logout') }}" class="list-group-item list-group-item-action text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right me-2"></i> Выйти
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>

<div class="card mt-4 shadow">
    <div class="card-body">
        <h5 class="card-title"><i class="bi bi-headset me-2"></i> Нужна помощь?</h5>
        <p class="card-text small">Если у вас возникли вопросы, свяжитесь с нашей службой поддержки.</p>
        <a href="{{ route('contact') }}" class="btn btn-sm btn-outline-primary">Связаться с нами</a>
    </div>
</div>
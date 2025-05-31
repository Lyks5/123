# Диаграмма потоков данных (DFD) для e-commerce сайта

## Описание компонентов

### Внешние сущности
- Покупатель - пользователь системы, который может просматривать товары, совершать покупки и управлять своим аккаунтом
- Администратор - пользователь с расширенными правами для управления системой

### Основные процессы
1. Управление продуктами - добавление, редактирование и удаление товаров
2. Обработка заказов - управление процессом оформления и выполнения заказов
3. Управление пользователями - регистрация, авторизация и управление данными пользователей
4. Управление корзиной - добавление и удаление товаров из корзины
5. Управление списком желаний - сохранение товаров в избранное
6. Управление отзывами - создание и модерация отзывов о товарах

### Хранилища данных
- Products - информация о товарах
- Categories - категории товаров
- Users - данные пользователей
- Orders - информация о заказах
- Carts - данные корзин пользователей
- Wishlists - списки желаний пользователей
- Reviews - отзывы о товарах
- EcoFeatures - эко-характеристики товаров

## Диаграмма

```mermaid
graph TB
    %% Внешние сущности
    Customer((Покупатель))
    Admin((Администратор))

    %% Основные процессы
    ProductManagement["1.0<br/>Управление<br/>продуктами"]
    OrderProcess["2.0<br/>Обработка<br/>заказов"]
    UserManagement["3.0<br/>Управление<br/>пользователями"]
    CartManagement["4.0<br/>Управление<br/>корзиной"]
    WishlistManagement["5.0<br/>Управление<br/>списком желаний"]
    ReviewManagement["6.0<br/>Управление<br/>отзывами"]

    %% Хранилища данных
    Products[("Товары<br/>Products")]
    Categories[("Категории<br/>Categories")]
    Users[("Пользователи<br/>Users")]
    Orders[("Заказы<br/>Orders")]
    Carts[("Корзины<br/>Carts")]
    Wishlists[("Списки желаний<br/>Wishlists")]
    Reviews[("Отзывы<br/>Reviews")]
    EcoFeatures[("Эко-характеристики<br/>EcoFeatures")]

    %% Потоки данных от покупателя
    Customer -->|"Регистрация/<br/>авторизация"| UserManagement
    Customer -->|"Просмотр товаров"| ProductManagement
    Customer -->|"Добавление в корзину"| CartManagement
    Customer -->|"Оформление заказа"| OrderProcess
    Customer -->|"Добавление в избранное"| WishlistManagement
    Customer -->|"Написание отзыва"| ReviewManagement

    %% Потоки данных от администратора
    Admin -->|"Управление товарами"| ProductManagement
    Admin -->|"Управление заказами"| OrderProcess
    Admin -->|"Управление пользователями"| UserManagement

    %% Потоки данных к хранилищам
    ProductManagement --> Products
    ProductManagement --> Categories
    ProductManagement --> EcoFeatures
    UserManagement --> Users
    OrderProcess --> Orders
    CartManagement --> Carts
    WishlistManagement --> Wishlists
    ReviewManagement --> Reviews

    %% Потоки данных от хранилищ
    Products -->|"Информация о товарах"| ProductManagement
    Categories -->|"Категории товаров"| ProductManagement
    Users -->|"Данные пользователей"| UserManagement
    Orders -->|"Информация о заказах"| OrderProcess
    Carts -->|"Содержимое корзины"| CartManagement
    Wishlists -->|"Списки желаний"| WishlistManagement
    Reviews -->|"Отзывы о товарах"| ReviewManagement
    EcoFeatures -->|"Эко-характеристики"| ProductManagement

    %% Стили
    classDef process fill:#fff,stroke:#000,stroke-width:2px,color:#000
    classDef storage fill:#fff,stroke:#000,stroke-width:2px,color:#000
    classDef entity fill:#fff,stroke:#000,stroke-width:2px,color:#000

    class ProductManagement,OrderProcess,UserManagement,CartManagement,WishlistManagement,ReviewManagement process
    class Products,Categories,Users,Orders,Carts,Wishlists,Reviews,EcoFeatures storage
    class Customer,Admin entity
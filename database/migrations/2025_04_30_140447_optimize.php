<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Миграция для удаления ненужных таблиц (2025_04_29_000000_optimize_tables.php)
public function up()
{
    Schema::dropIfExists('blog_post_categories');
    Schema::dropIfExists('contact_requests');
    Schema::dropIfExists('environmental_initiatives');
    Schema::dropIfExists('newsletters');

    Schema::dropIfExists('wishlists');
    Schema::dropIfExists('wishlist_items');
    Schema::dropIfExists('product_images');
    Schema::dropIfExists('carts');
    Schema::dropIfExists('cart_items');
   

    // Изменения для blog_posts
    Schema::table('blog_posts', function (Blueprint $table) {
        $table->unsignedBigInteger('category_id')->after('id');
        $table->foreign('category_id')->references('id')->on('blog_categories');
    });

    // Интеграция корзины в users
    Schema::table('users', function (Blueprint $table) {
        $table->json('cart_data')->nullable()->after('eco_impact_score'); 
        // Формат: {items: [{product_id, variant_id, quantity}]}
    });
    
    


    Schema::table('users', function (Blueprint $table) {
        $table->json('wishlist_data')
              ->nullable()
              ->after('cart_data')
              ->comment('Формат: [{list_name: "Мой список", items: [{product_id: 1, variant_id: 2}]}]');
    });
}

public function down()
{
    // Восстановление удалённых таблиц потребует отдельного подхода
    // Здесь показан пример для blog_post_categories
    

   

    // Восстановление других таблиц аналогично...

    // Откат изменений blog_posts
    Schema::table('blog_posts', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });

    // Удаление столбца type из attributes
    Schema::table('attributes', function (Blueprint $table) {
        $table->dropColumn('type');
    });

    // Удаление JSON-полей
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('cart_data');
    });

    
}
};
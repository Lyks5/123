<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        // Сначала создаем основные таблицы
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->decimal('price', 10, 2)->unsigned();
            $table->decimal('sale_price', 10, 2)->unsigned()->nullable();
            $table->string('sku', 50)->unique();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_new')->default(false);
            $table->json('images')->nullable()->comment('Формат: [{path: "...", alt: "...", is_primary: true}]');
            $table->timestamps();
        });
        
    }

   
    public function down(): void
    {
        Schema::dropIfExists('eco_feature_product');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('products');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('eco_features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });
        // Промежуточная таблица для эко-особенностей
        Schema::create('eco_feature_product', function (Blueprint $table) {
            $table->foreignId('eco_feature_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('value')->nullable();
            $table->timestamps();
            // Составной первичный ключ
            $table->primary(['eco_feature_id', 'product_id']);
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('eco_features');
    }
};
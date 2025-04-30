<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OptimizeDataTypes extends Migration
{
    public function up()
    {
        // Таблица: addresses
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('first_name', 50)->change();
            $table->string('last_name', 50)->change();
            $table->string('address_line1', 100)->change();
            $table->string('address_line2', 100)->nullable()->change();
            $table->string('city', 50)->change();
            $table->string('state', 50)->change();
            $table->string('postal_code', 20)->change();
            $table->string('country', 50)->change();
            $table->string('phone', 20)->nullable()->change();
        });

        // Таблица: users
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 50)->change();
            $table->string('email', 100)->change();
            $table->string('avatar', 200)->nullable()->change();
            $table->string('gender', 10)->nullable()->change();
        });

        // Таблица: products
        Schema::table('products', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('slug', 120)->change();
            $table->decimal('price', 10, 2)->unsigned()->change();
            $table->decimal('sale_price', 10, 2)->unsigned()->nullable()->change();
            $table->string('sku', 50)->change();
        });

        // Таблица: variants
        Schema::table('variants', function (Blueprint $table) {
            $table->string('sku', 50)->change();
            $table->decimal('price', 10, 2)->unsigned()->nullable()->change();
            $table->decimal('sale_price', 10, 2)->unsigned()->nullable()->change();
        });

        // Таблица: blog_posts
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('title', 150)->change();
            $table->string('slug', 150)->change();
            $table->string('status', 20)->default('draft')->change();
        });

        // Таблица: orders
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_amount', 12, 2)->unsigned()->change();
            $table->decimal('subtotal', 12, 2)->unsigned()->change();
            $table->string('payment_method', 30)->change();
            $table->string('shipping_method', 30)->change();
        });

        // Таблица: categories
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name', 50)->change();
            $table->string('slug', 60)->change();
        });

        // Таблица: eco_features
        Schema::table('eco_features', function (Blueprint $table) {
            $table->string('name', 50)->change();
            $table->string('slug', 60)->change();
        });
    }

    
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('eco_impact_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('carbon_saved', 10, 2)->default(0);
            $table->decimal('plastic_saved', 10, 2)->default(0);
            $table->decimal('water_saved', 10, 2)->default(0);
            $table->string('type');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('environmental_initiatives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('image')->nullable();
            $table->text('goal');
            $table->decimal('current_progress', 10, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('environmental_initiatives');
        Schema::dropIfExists('eco_impact_records');
    }
};

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CategoryService;
use App\Services\AttributeService;
use App\Services\ProductService;
use App\Services\ProductImageService;
use App\Services\ProductAttributeService;
use App\Services\ProductVariantService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CategoryService::class);
        $this->app->singleton(AttributeService::class);
        $this->app->singleton(ProductService::class);
        $this->app->singleton(ProductImageService::class);
        $this->app->singleton(ProductAttributeService::class);
        $this->app->singleton(ProductVariantService::class);
    }
}

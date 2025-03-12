<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="bg-eco-600 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl font-bold mb-6">Экологичные спортивные товары</h1>
            <a href="{{ route('shop') }}" 
               class="bg-white text-eco-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100">
                Начать покупки
            </a>
        </div>
    </div>
</div>

<!-- Featured Products -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Популярные товары</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endsection
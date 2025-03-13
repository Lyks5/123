@extends('layouts.app')
<script src="//unpkg.com/alpinejs" defer></script>
@section('content')
    <!-- Hero Section -->
    @include('components.hero')

    <!-- Featured Products -->
    @include('components.featured-products')

    <!-- About Section -->
    @include('components.about')

    <!-- Newsletter -->
    @include('components.newsletter')
@endsection
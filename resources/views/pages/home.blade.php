@extends('layouts.app')
<script src="//unpkg.com/alpinejs" defer></script>
@section('content')
    <!-- Hero Section -->
    @include('partials.hero')

    <!-- Featured Products -->
    @include('partials.featured-products')

    <!-- About Section -->
    @include('partials.about')

    <!-- Newsletter -->
    @include('partials.newsletter')
@endsection
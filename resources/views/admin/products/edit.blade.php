@extends('layouts.admin')

@section('content')
<div id="edit-product-root" 
    data-product-id="{{ $product->id }}"
    data-categories="{{ json_encode($categories) }}"
    data-attributes="{{ json_encode($attributes) }}"
></div>
@endsection

@push('scripts')
    @vite(['resources/js/app.tsx'])
@endpush
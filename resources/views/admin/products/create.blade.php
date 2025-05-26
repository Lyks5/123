@extends('layouts.admin')

@section('content')
<div id="create-product-root" 
    data-categories="{{ json_encode($categories) }}"
    data-attributes="{{ json_encode($attributes) }}"
></div>
@endsection

@push('scripts')
    @viteReactRefresh
    @vite(['resources/js/index.tsx'])
@endpush
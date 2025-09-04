@extends('admin.layout')

@section('title', 'Activation Fund')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Activation Fund Management</h1>
        <p class="mt-2 text-gray-600">Manage account activation funds</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="text-center py-12">
            <div class="text-gray-500 text-lg">No activation funds found.</div>
            <p class="text-gray-400 mt-2">No activation fund activities yet.</p>
        </div>
    </div>
</div>
@endsection

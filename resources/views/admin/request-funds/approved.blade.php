@extends('admin.layout')

@section('title', 'Approved Request Funds')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Approved Request Funds</h1>
        <p class="mt-2 text-gray-600">History of approved fund requests</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="text-center py-12">
            <div class="text-gray-500 text-lg">No approved request funds found.</div>
            <p class="text-gray-400 mt-2">No fund requests have been approved yet.</p>
        </div>
    </div>
</div>
@endsection

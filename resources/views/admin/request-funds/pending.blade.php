@extends('admin.layout')

@section('title', 'Pending Request Funds')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Pending Request Funds</h1>
        <p class="mt-2 text-gray-600">Review and approve fund requests</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="text-center py-12">
            <div class="text-gray-500 text-lg">No pending request funds found.</div>
            <p class="text-gray-400 mt-2">All fund requests have been processed.</p>
        </div>
    </div>
</div>
@endsection

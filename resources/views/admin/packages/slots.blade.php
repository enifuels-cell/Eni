@extends('admin.layout')

@section('title', 'Package Slots Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Package Slots Management</h1>
        <p class="mt-2 text-gray-600">Manage available slots for investment packages</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($packages as $package)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $package->name }}</h3>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-sm rounded">
                        {{ $package->available_slots ?? 'Unlimited' }} slots
                    </span>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Min Amount:</span>
                        <span class="font-medium">${{ number_format($package->min_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Max Amount:</span>
                        <span class="font-medium">${{ number_format($package->max_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Daily Rate:</span>
                        <span class="font-medium">{{ $package->daily_shares_rate }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-medium">{{ $package->effective_days }} days</span>
                    </div>
                </div>

                <form action="{{ route('admin.packages.update-slots', $package) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="slots_{{ $package->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Available Slots
                        </label>
                        <input type="number" 
                               name="available_slots" 
                               id="slots_{{ $package->id }}" 
                               value="{{ $package->available_slots }}"
                               min="0"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Leave empty for unlimited slots</p>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors">
                        Update Slots
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    @if($packages->count() === 0)
    <div class="text-center py-12">
        <div class="text-gray-500 text-lg">No investment packages found.</div>
        <p class="text-gray-400 mt-2">Create investment packages to manage their slots.</p>
    </div>
    @endif
</div>
@endsection

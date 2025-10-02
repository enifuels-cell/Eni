@extends('admin.layout')

@section('title', 'Franchise Applications')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Franchise Applications</h1>
        <p class="mt-2 text-gray-600">Review and manage franchise applications</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gradient-to-br from-eni-dark/90 to-eni-charcoal/90 backdrop-blur-xl rounded-2xl border border-white/10 shadow-xl overflow-hidden">
        @if($applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-eni-dark to-eni-charcoal text-eni-yellow uppercase text-xs tracking-wide">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">Applicant</th>
                            <th class="px-6 py-3 text-left font-semibold">Location</th>
                            <th class="px-6 py-3 text-left font-semibold">Investment</th>
                            <th class="px-6 py-3 text-left font-semibold">Status</th>
                            <th class="px-6 py-3 text-left font-semibold">Applied</th>
                            <th class="px-6 py-3 text-left font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($applications as $application)
                        <tr class="border-b border-white/10 hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($application->user->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $application->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $application->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $application->preferred_location ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($application->investment_amount ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                                    @if($application->status === 'approved') bg-green-100 text-green-800
                                    @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($application->status === 'approved')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @elseif($application->status === 'rejected')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01" />
                                        </svg>
                                    @endif
                                    {{ ucfirst($application->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($application->status === 'pending')
                                <div class="flex space-x-2">
                                    <form action="{{ route('admin.franchise.approve', $application) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-all duration-200 transform hover:scale-105"
                                                onclick="return confirm('Are you sure you want to approve this application?')">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.franchise.reject', $application) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-all duration-200 transform hover:scale-105"
                                                onclick="return confirm('Are you sure you want to reject this application?')">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                                @else
                                <span class="text-gray-500">Processed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $applications->onEachSide(1)->links('vendor.pagination.tailwind') }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 text-lg">No franchise applications found.</div>
                <p class="text-gray-400 mt-2">No franchise applications have been submitted yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

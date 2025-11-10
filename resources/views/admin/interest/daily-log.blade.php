@extends('admin.layout')

@section('title', 'Daily Interest Log')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Daily Interest Log</h1>
        <p class="mt-2 text-gray-600">Track daily interest payments to users</p>
    </div>

    <div class="mb-6 bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('admin.interest.daily') }}" class="flex items-center space-x-4">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Filter by Date</label>
                <input type="date" name="date" id="date" value="{{ request('date') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="pt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('admin.interest.daily') }}" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <div class="mb-6 bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Today's Summary</h3>
        <div class="text-2xl font-bold text-green-600">${{ number_format($totalToday, 2) }}</div>
        <p class="text-gray-600">Total interest paid today</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Investment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Interest Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($log->user?->name ?? 'NA', 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $log->user?->name ?? 'User Deleted' }}</div>
                                        <div class="text-sm text-gray-500">{{ $log->user?->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${{ number_format($log->investment?->amount ?? 0, 2) }}</div>
                                <div class="text-sm text-gray-500">{{ $log->investment?->investmentPackage?->name ?? 'Package Not Found' }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-green-600">${{ number_format($log->interest_amount, 2) }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('M d, Y g:i A') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $logs->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 text-lg">No interest logs found.</div>
                <p class="text-gray-400 mt-2">No daily interest has been calculated yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('admin.layout')

@section('title', 'User Management')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <p class="mt-2 text-gray-600">Manage platform users and their accounts</p>
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

    <!-- Referral Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalUsers) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-user-plus text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Referred Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($referredUsers) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-handshake text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Active Referrers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($activeReferrers) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-percentage text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-500">Referral Rate</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($referralRate, 1) }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Filter by:</label>
                <select class="border border-gray-300 rounded-md px-3 py-1 text-sm" id="userFilter">
                    <option value="all">All Users</option>
                    <option value="referred">Referred Users Only</option>
                    <option value="referrers">Active Referrers Only</option>
                    <option value="direct">Direct Signups</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <input type="text" placeholder="Search by name or email..."
                       class="border border-gray-300 rounded-md px-3 py-1 text-sm w-64"
                       id="searchInput">
            </div>
            <button onclick="resetFilters()" class="px-4 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm">
                Reset
            </button>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Deposit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referred By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50 {{ !$user->has_investments ? 'bg-gray-700 bg-opacity-5' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center gap-2">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            @if($user->isSuspended())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-ban text-xs mr-1"></i>
                                                    Suspended
                                                </span>
                                            @endif
                                            @if(!$user->has_investments)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-300 text-gray-700">
                                                    <i class="fas fa-star-o text-xs mr-1"></i>
                                                    New
                                                </span>
                                            @endif
                                            @if($user->referrals_count > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-eni-yellow text-eni-dark">
                                                    <i class="fas fa-users text-xs mr-1"></i>
                                                    {{ $user->referrals_count }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($user->role ?? 'user') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($user->account_balance ?? 0, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->total_active_deposit > 0)
                                    <div class="text-sm font-semibold text-green-600">${{ number_format($user->total_active_deposit, 2) }}</div>
                                @else
                                    <div class="text-sm text-gray-500">-</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->referralReceived && $user->referralReceived->referrer)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-6 w-6">
                                            <div class="h-6 w-6 rounded-full bg-eni-yellow flex items-center justify-center">
                                                <span class="text-xs font-medium text-eni-dark">{{ substr($user->referralReceived->referrer->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-2">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->referralReceived->referrer->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->referralReceived->referrer->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500 italic">Direct signup</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} w-fit">
                                        {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                    </span>
                                    @if($user->isSuspended())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-600 text-white w-fit">
                                            <i class="fas fa-ban mr-1"></i>
                                            SUSPENDED
                                        </span>
                                    @endif
                                </div>
                                @if(config('app.debug'))
                                    <div class="text-xs text-gray-500 mt-1">
                                        ID: {{ $user->id }} | Verified: {{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'NULL' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if(!$user->email_verified_at)
                                    <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                            Verify
                                        </button>
                                    </form>
                                    @endif

                                    @if($user->role !== 'admin')
                                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1 rounded text-sm {{ $user->isSuspended() ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-red-600 hover:bg-red-700' }} text-white"
                                                onclick="return confirm('Are you sure?')">
                                            {{ $user->isSuspended() ? 'Unsuspend' : 'Suspend' }}
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 text-lg">No users found.</div>
                <p class="text-gray-400 mt-2">No users have registered yet.</p>
            </div>
        @endif
    </div>
</div>

<script>
// Filter and search functionality
document.addEventListener('DOMContentLoaded', function() {
    const userFilter = document.getElementById('userFilter');
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('tbody tr');

    function applyFilters() {
        const filterValue = userFilter.value;
        const searchValue = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const nameCell = row.querySelector('td:first-child .text-gray-900');
            const emailCell = row.querySelector('td:first-child .text-gray-500');
            const referredByCell = row.querySelector('td:nth-child(4)');
            const referralBadge = row.querySelector('.bg-eni-yellow');

            const name = nameCell ? nameCell.textContent.toLowerCase() : '';
            const email = emailCell ? emailCell.textContent.toLowerCase() : '';
            const hasReferrer = referredByCell && !referredByCell.textContent.includes('Direct signup');
            const isReferrer = referralBadge !== null;

            // Search filter
            const matchesSearch = name.includes(searchValue) || email.includes(searchValue);

            // Category filter
            let matchesFilter = true;
            switch(filterValue) {
                case 'referred':
                    matchesFilter = hasReferrer;
                    break;
                case 'referrers':
                    matchesFilter = isReferrer;
                    break;
                case 'direct':
                    matchesFilter = !hasReferrer;
                    break;
                default:
                    matchesFilter = true;
            }

            // Show/hide row
            row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
        });
    }

    userFilter.addEventListener('change', applyFilters);
    searchInput.addEventListener('input', applyFilters);
});

function resetFilters() {
    document.getElementById('userFilter').value = 'all';
    document.getElementById('searchInput').value = '';
    document.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = '';
    });
}
</script>
@endsection

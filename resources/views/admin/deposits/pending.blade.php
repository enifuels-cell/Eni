@extends('admin.layout')

@section('title', 'Pending Deposits')

@section('content')
<div class="px-4 py-6 sm:px-0">
    <!-- Page Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <h1 class="text-3xl font-bold leading-tight text-gray-900">Pending Deposits</h1>
        <p class="mt-2 text-sm text-gray-600">Review and approve user deposit submissions</p>
    </div>

    @if($deposits->count() > 0)
        <!-- Deposits Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($deposits as $deposit)
                    <li class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                                        <i class="fas fa-clock text-orange-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">{{ $deposit->user->name }}</p>
                                        <span class="ml-2 text-sm text-gray-500">({{ $deposit->user->email }})</span>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500">
                                        <span class="font-medium">${{ number_format($deposit->amount, 2) }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $deposit->created_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                @if($deposit->receipt_path)
                                    <a href="{{ Storage::url($deposit->receipt_path) }}" target="_blank" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <i class="fas fa-eye mr-1"></i>View Receipt
                                    </a>
                                @endif
                                
                                <!-- Approve Button -->
                                <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to approve this deposit?')"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <i class="fas fa-check mr-1"></i>Approve
                                    </button>
                                </form>
                                
                                <!-- Deny Button -->
                                <button onclick="openDenyModal({{ $deposit->id }})" 
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                    <i class="fas fa-times mr-1"></i>Deny
                                </button>
                                
                                <!-- Delete Button -->
                                <button onclick="openDeleteModal({{ $deposit->id }})" 
                                        class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </div>
                        </div>
                        
                        @if($deposit->payment_method)
                            <div class="mt-2 ml-14">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Payment Method: {{ ucfirst($deposit->payment_method) }}
                                </span>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $deposits->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i class="fas fa-clock text-gray-400 text-6xl mb-4"></i>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending deposits</h3>
            <p class="mt-1 text-sm text-gray-500">All deposits have been processed.</p>
        </div>
    @endif
</div>

<!-- Deny Modal -->
<div id="denyModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Deny Deposit</h3>
            <form id="denyForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for denial</label>
                    <textarea name="reason" id="reason" required rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="Please provide a reason for denying this deposit..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDenyModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Deny Deposit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Delete Deposit</h3>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="mb-4">
                    <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Admin Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="admin_password" id="admin_password" required 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                           placeholder="Enter your admin password">
                </div>
                <div class="bg-red-50 border border-red-200 rounded-md p-3 mb-4">
                    <p class="text-sm text-red-800">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Warning: This action cannot be undone. This will permanently delete the deposit record.
                    </p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete Permanently
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openDenyModal(depositId) {
    document.getElementById('denyForm').action = `/admin/deposits/${depositId}/deny`;
    document.getElementById('denyModal').classList.remove('hidden');
}

function closeDenyModal() {
    document.getElementById('denyModal').classList.add('hidden');
    document.getElementById('reason').value = '';
}

function openDeleteModal(depositId) {
    document.getElementById('deleteForm').action = `/admin/deposits/${depositId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('admin_password').value = '';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const denyModal = document.getElementById('denyModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === denyModal) {
        closeDenyModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}
</script>
@endsection

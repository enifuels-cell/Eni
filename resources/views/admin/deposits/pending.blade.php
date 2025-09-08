@extends('admin.layout')

@section('title', 'Pending Deposits')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="eni-card rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white flex items-center">
                    <i class="fas fa-clock mr-3 text-white/60"></i>
                    Pending Deposits
                </h1>
                <p class="mt-2 text-white/60">Review and approve user deposit submissions</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="bg-white/10 border border-white/20 rounded-lg px-4 py-2">
                    <span class="text-white font-semibold">{{ $deposits->count() }}</span>
                    <span class="text-white/60 ml-2">Pending</span>
                </div>
                <button onclick="location.reload()" class="eni-button px-4 py-2 rounded-lg font-medium flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    @if($deposits->count() > 0)
        <!-- Deposits Grid -->
        <div class="grid gap-6">
            @foreach($deposits as $deposit)
                <div class="eni-card rounded-xl p-6 hover:bg-white/10 transition-all duration-300">
                    <div class="flex items-start justify-between">
                        <!-- User Info -->
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/10 border border-white/20 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($deposit->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-white font-semibold text-lg">{{ $deposit->user->name }}</h3>
                                    <span class="text-white/60 text-sm">({{ $deposit->user->email }})</span>
                                </div>
                                
                                <!-- Amount & Reference -->
                                <div class="flex items-center space-x-4 mb-3">
                                    <div class="bg-white/10 border border-white/20 rounded-lg px-3 py-1">
                                        <span class="text-white font-bold text-xl">${{ number_format($deposit->amount, 2) }}</span>
                                    </div>
                                    <div class="text-white/60 text-sm">
                                        <i class="fas fa-hashtag mr-1 text-white/40"></i>{{ $deposit->reference }}
                                    </div>
                                    <div class="text-white/60 text-sm">
                                        <i class="fas fa-calendar mr-1 text-white/40"></i>{{ $deposit->created_at->format('M d, Y g:i A') }}
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                @if($deposit->reference && str_contains($deposit->reference, 'Bank'))
                                    <div class="inline-flex items-center bg-white/10 border border-white/20 rounded-lg px-3 py-1 text-sm">
                                        <i class="fas fa-university mr-2 text-white/60"></i>
                                        <span class="text-white/80">{{ $deposit->reference }}</span>
                                    </div>
                                @endif

                                <!-- Description -->
                                @if($deposit->description)
                                    <div class="mt-3 text-white/70 text-sm bg-white/5 rounded-lg p-3">
                                        <i class="fas fa-info-circle mr-2 text-white/50"></i>
                                        {{ $deposit->description }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-3">
                            @if($deposit->receipt_path)
                                <a href="{{ Storage::url($deposit->receipt_path) }}" target="_blank" 
                                   class="bg-white/10 hover:bg-white/20 border border-white/20 text-white/80 px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center">
                                    <i class="fas fa-eye mr-2 text-white/60"></i>View Receipt
                                </a>
                            @endif
                            
                            <!-- Approve Button -->
                            <form method="POST" action="{{ route('admin.deposits.approve', $deposit) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to approve this deposit?')"
                                        class="bg-green-600/80 hover:bg-green-600 border border-green-600/50 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center">
                                    <i class="fas fa-check mr-2"></i>Approve
                                </button>
                            </form>
                            
                            <!-- Deny Button -->
                            <button onclick="openDenyModal('{{ $deposit->id }}')" 
                                    class="bg-red-600/80 hover:bg-red-600 border border-red-600/50 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center">
                                <i class="fas fa-times mr-2"></i>Deny
                            </button>
                            
                            <!-- Delete Button -->
                            <button onclick="openDeleteModal('{{ $deposit->id }}')" 
                                    class="bg-white/10 hover:bg-white/20 border border-white/20 text-white/80 px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center">
                                <i class="fas fa-trash mr-2 text-white/60"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="eni-card rounded-xl p-6">
            {{ $deposits->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="eni-card rounded-xl p-12 text-center">
            <div class="w-24 h-24 bg-white/10 border border-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clock text-white/40 text-4xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-2">No Pending Deposits</h3>
            <p class="text-white/60 mb-6">All deposits have been processed or there are no new submissions.</p>
            <button onclick="location.reload()" class="eni-button px-6 py-3 rounded-lg font-medium">
                <i class="fas fa-sync-alt mr-2"></i>Refresh Page
            </button>
        </div>
    @endif
</div>

<!-- Deny Modal -->
<div id="denyModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="eni-card rounded-2xl max-w-lg w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-times-circle mr-3 text-white/60"></i>
                    Deny Deposit
                </h3>
                <button onclick="closeDenyModal()" class="text-white/60 hover:text-white text-2xl">×</button>
            </div>
            
            <form id="denyForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label for="reason" class="block text-white/80 font-medium mb-3">Reason for denial</label>
                    <textarea name="reason" id="reason" required rows="4" 
                              class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-white/40 focus:ring-2 focus:ring-red-400 focus:border-transparent"
                              placeholder="Please provide a detailed reason for denying this deposit..."></textarea>
                </div>
                
                <div class="bg-white/10 border border-white/20 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-white/60 mt-1 mr-3"></i>
                        <div>
                            <p class="text-white/80 text-sm">
                                This action will reject the deposit and notify the user with your reason.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDenyModal()" 
                            class="px-6 py-3 bg-white/10 border border-white/20 text-white rounded-lg hover:bg-white/20 transition-all">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-all">
                        <i class="fas fa-times mr-2"></i>Deny Deposit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="eni-card rounded-2xl max-w-lg w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-trash-alt mr-3 text-white/60"></i>
                    Delete Deposit
                </h3>
                <button onclick="closeDeleteModal()" class="text-white/60 hover:text-white text-2xl">×</button>
            </div>
            
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="mb-6">
                    <label for="admin_password" class="block text-white/80 font-medium mb-3">
                        Admin Password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" name="admin_password" id="admin_password" required 
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-white/40 focus:ring-2 focus:ring-red-400 focus:border-transparent"
                           placeholder="Enter your admin password">
                </div>
                
                <div class="bg-white/10 border border-white/20 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-white/60 mt-1 mr-3"></i>
                        <div>
                            <h4 class="text-white font-medium mb-1">Dangerous Action</h4>
                            <p class="text-white/70 text-sm">
                                This will permanently delete the deposit record. This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-6 py-3 bg-white/10 border border-white/20 text-white rounded-lg hover:bg-white/20 transition-all">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-all">
                        <i class="fas fa-trash mr-2"></i>Delete Permanently
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
    document.body.style.overflow = 'hidden';
}

function closeDenyModal() {
    document.getElementById('denyModal').classList.add('hidden');
    document.getElementById('reason').value = '';
    document.body.style.overflow = 'auto';
}

function openDeleteModal(depositId) {
    document.getElementById('deleteForm').action = `/admin/deposits/${depositId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('admin_password').value = '';
    document.body.style.overflow = 'auto';
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

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDenyModal();
        closeDeleteModal();
    }
});
</script>
@endsection

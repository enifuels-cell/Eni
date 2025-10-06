@extends('admin.layout')

@section('title', 'Activation Fund')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Activation Fund Management</h1>
        <p class="mt-2 text-gray-600">Manage account activation funds</p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
        <div class="max-w-2xl mx-auto">
            <form method="POST" action="{{ route('admin.activation-fund.send') }}" class="grid grid-cols-1 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Recipient Email</label>
                    <input name="email" type="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="user@example.com" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Amount (NGN)</label>
                    <input name="amount" type="number" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="1000000" />
                    <p class="text-xs text-gray-500 mt-1">Default set to 1,000,000 — change if needed.</p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="px-4 py-2 bg-eni-yellow text-eni-dark font-semibold rounded">Send Activation Fund</button>
                </div>
            </form>

            <hr class="my-6" />

            <h2 class="text-lg font-semibold mb-3">Recent Activation Fund Activity</h2>
            @if($activationFunds->isEmpty())
                <div class="text-gray-500">No activation fund activities yet.</div>
            @else
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-2">Date</th>
                            <th class="p-2">Recipient</th>
                            <th class="p-2">Amount</th>
                            <th class="p-2">Reference</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activationFunds as $tx)
                            <tr class="border-t">
                                <td class="p-2 text-gray-700">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                                <td class="p-2 text-gray-800">{{ $tx->user->email ?? 'Unknown' }}</td>
                                <td class="p-2 text-gray-800">{{ number_format($tx->amount, 2) }}</td>
                                <td class="p-2 text-gray-700">{{ $tx->reference }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $activationFunds->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function(){
        const form = document.querySelector('form[action="{{ route('admin.activation-fund.send') }}"]');
        if (!form) return;

        form.addEventListener('submit', function(e){
            const amountInput = form.querySelector('input[name="amount"]');
            const amount = parseFloat(amountInput.value || 0);
            const threshold = 100000; // threshold for confirmation

            if (amount >= threshold) {
                e.preventDefault();
                // Create modal if not exists
                let modal = document.getElementById('confirm-large-amount-modal');
                if (!modal) {
                    modal = document.createElement('div');
                    modal.id = 'confirm-large-amount-modal';
                    modal.innerHTML = `
                        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded shadow p-6 max-w-lg w-full text-gray-900">
                                <h3 class="text-lg font-semibold mb-2">Confirm large transfer</h3>
                                <p class="mb-4">You're about to send <strong>${amount.toLocaleString()}</strong> NGN. This action cannot be undone. Are you sure?</p>
                                <div class="flex justify-end space-x-2">
                                    <button id="confirm-cancel" class="px-4 py-2 rounded bg-gray-200">Cancel</button>
                                    <button id="confirm-ok" class="px-4 py-2 rounded bg-eni-yellow text-eni-dark font-semibold">Confirm</button>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);

                    document.getElementById('confirm-cancel').addEventListener('click', function(){
                        modal.remove();
                    });
                    document.getElementById('confirm-ok').addEventListener('click', function(){
                        // Submit the form after confirmation
                        modal.remove();
                        form.submit();
                    });
                }
            }
        });
    })();
</script>
@endsection

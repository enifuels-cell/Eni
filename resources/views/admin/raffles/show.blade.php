@extends('admin.layout')

@section('title', 'Raffle Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $raffle->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.raffles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Raffles
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Raffle Information</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Month:</strong> {{ \Carbon\Carbon::create($raffle->raffle_year, $raffle->raffle_month)->format('F Y') }}</p>
                                    <p><strong>Status:</strong>
                                        <span class="badge badge-{{ $raffle->status === 'active' ? 'success' : ($raffle->status === 'drawn' ? 'info' : 'secondary') }}">
                                            {{ ucfirst($raffle->status) }}
                                        </span>
                                    </p>
                                    <p><strong>Description:</strong> {{ $raffle->description }}</p>
                                    @if($raffle->winner)
                                        <p><strong>Winner:</strong> {{ $raffle->winner->name }} ({{ $raffle->winner->email }})</p>
                                        <p><strong>Drawn At:</strong> {{ $raffle->drawn_at->format('M j, Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Total Participants:</strong> {{ $eligibleUsers->count() }}</p>
                                    <p><strong>Total Tickets:</strong> {{ $eligibleUsers->sum('total_tickets') }}</p>
                                    @if($raffle->status === 'drawn' && $raffle->draw_details)
                                        <p><strong>Winner Tickets:</strong> {{ $raffle->draw_details['winner_tickets'] ?? 'N/A' }}</p>
                                        <p><strong>Draw Method:</strong> {{ $raffle->draw_details['draw_method'] ?? 'N/A' }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($raffle->status === 'active')
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Eligible Participants</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Tickets</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($eligibleUsers as $user)
                                                        <tr>
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->total_tickets }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
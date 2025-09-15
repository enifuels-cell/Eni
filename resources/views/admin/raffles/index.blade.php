@extends('admin.layout')

@section('title', 'Raffle Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Monthly Raffles</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.raffles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create Raffle
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Month/Year</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Winner</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($raffles as $raffle)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::create($raffle->raffle_year, $raffle->raffle_month)->format('M Y') }}</td>
                                        <td>{{ $raffle->title }}</td>
                                        <td>
                                            <span class="badge badge-{{ $raffle->status === 'active' ? 'success' : ($raffle->status === 'drawn' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($raffle->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($raffle->winner)
                                                {{ $raffle->winner->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.raffles.show', $raffle) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            @if($raffle->status === 'active')
                                                <form action="{{ route('admin.raffles.conduct-draw', $raffle) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to conduct this raffle draw?')">
                                                        <i class="fas fa-trophy"></i> Conduct Draw
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('admin.raffles.edit', $raffle) }}" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @if($raffle->status !== 'drawn')
                                                <form action="{{ route('admin.raffles.destroy', $raffle) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this raffle?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No raffles found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $raffles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Welcome Section -->
        <div class="col-12 bg-primary text-white py-4 mb-4">
            <div class="container">
                <h1 class="display-4">Welcome, {{ $user->name }}!</h1>
                <p class="lead">
                    {{ $user->isAdmin() ? 'Administrator' : 'User' }} Dashboard
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-12 mb-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Notebooks</h5>
                                <p class="display-4">{{ $stats['total_notebooks'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Your Notebooks</h5>
                                <p class="display-4">{{ $stats['user_notebooks_count'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Manufacturers</h5>
                                <p class="display-4">{{ $stats['total_manufacturers'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Avg Notebook Price</h5>
                                <p class="display-4">${{ number_format($stats['average_notebook_price'], 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 mb-4">
            <div class="container">
                <div class="card">
                    <div class="card-header">Quick Actions</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('notebooks.create') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-plus"></i> Add New Notebook
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('notebooks.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-list"></i> View All Notebooks
                                </a>
                            </div>
                            @if($user->isAdmin())
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('admin.users') }}" class="btn btn-warning w-100">
                                    <i class="fas fa-users"></i> User Management
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Notebooks -->
        <div class="col-md-6 mb-4">
            <div class="container">
                <div class="card">
                    <div class="card-header">Recent Notebooks</div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($recentNotebooks as $notebook)
                                <a href="{{ route('notebooks.show', $notebook) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $notebook->manufacturer }} {{ $notebook->type }}</h5>
                                        <small>{{ $notebook->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        Price: ${{ number_format($notebook->price, 2) }} 
                                        | Processor: {{ $notebook->processor->type }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Your Notebooks -->
        <div class="col-md-6 mb-4">
            <div class="container">
                <div class="card">
                    <div class="card-header">Your Notebooks</div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($userNotebooks as $notebook)
                                <a href="{{ route('notebooks.show', $notebook) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $notebook->manufacturer }} {{ $notebook->type }}</h5>
                                        <small>{{ $notebook->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        Price: ${{ number_format($notebook->price, 2) }}
                                    </p>
                                </a>
                            @empty
                                <p class="text-center text-muted">No notebooks yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="col-12 mb-4">
            <div class="container">
                <div class="card">
                    <div class="card-header">Top Manufacturers</div>
                    <div class="card-body">
                        <canvas id="manufacturerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('manufacturerChart'), {
        type: 'bar',
        data: {
            labels: {!! $topManufacturers->pluck('manufacturer')->toJson() !!},
            datasets: [{
                label: 'Notebooks by Manufacturer',
                data: {!! $topManufacturers->pluck('count')->toJson() !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.bg-primary {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%) !important;
}
.card {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.card:hover {
    transform: translateY(-5px);
}
</style>
@endpush


@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Welcome Section -->
        <div class="col-12 bg-primary text-white py-4 mb-4">
            <div class="container">
                <h1 class="display-4">Welcome, {{ $user->name }}!</h1>
                <p class="lead">
                    {{ $user->isAdmin() ? 'Administrator' : 'User' }} Dashboard
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-12 mb-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Total Notebooks</h5>
                                <p class="display-4">{{ $stats['total_notebooks'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Your Notebooks</h5>
                                <p class="display-4">{{ $stats['user_notebooks_count'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Manufacturers</h5>
                                <p class="display-4">{{ $stats['total_manufacturers'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Avg Notebook Price</h5>
                                <p class="display-4">${{ number_format($stats['average_notebook_price'], 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 mb-4">
            <div class="container">
                <div class="card">
                    <div class="card-header">Quick Actions</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('notebooks.create') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-plus"></i> Add New Notebook
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('notebooks.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-list"></i> View All Notebooks
                                </a>
                            </div>
                            @if($user->isAdmin())
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('admin.users') }}" class="btn btn-warning w-100">
                                    <i class="fas fa-users"></i> User Management
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Notebooks -->
        <div class="col-md-6 mb-4">
            <div class="container">
                <div class="card">
                    <div class="card-header">Recent Notebooks</div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($recentNotebooks as $notebook)
                                <a href="{{ route('notebooks.show', $notebook) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $notebook->manufacturer }} {{ $notebook->type }}</h5>
                                        <small>{{ $notebook->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        Price: ${{ number_format($notebook->price, 2) }} 
                                        | Processor: {{ $notebook->processor->type }}
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Your Notebooks -->
        <div class="col-md-6 mb-4">
            <div class="container">
                <div class="card">
                    <div class="card-header">Your Notebooks</div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($userNotebooks as $notebook)
                                <a href="{{ route('notebooks.show', $notebook) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $notebook->manufacturer }} {{ $notebook->type }}</h5>
                                        <small>{{ $notebook->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        Price: ${{ number_format($notebook->price, 2) }}
                                    </p>
                                </a>
                            @empty
                                <p class="text-center text-muted">No notebooks yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="col-12 mb-4">
            <div class="container">
                <div class="card">
                    <div class="card-header">Top Manufacturers</div>
                    <div class="card-body">
                        <canvas id="manufacturerChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('manufacturerChart'), {
        type: 'bar',
        data: {
            labels: {!! $topManufacturers->pluck('manufacturer')->toJson() !!},
            datasets: [{
                label: 'Notebooks by Manufacturer',
                data: {!! $topManufacturers->pluck('count')->toJson() !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.bg-primary {
    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%) !important;
}
.card {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.card:hover {
    transform: translateY(-5px);
}
</style>
@endpush

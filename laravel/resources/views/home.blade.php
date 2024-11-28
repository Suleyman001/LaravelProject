@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Hero Section -->
        <div class="col-12 bg-primary text-white text-center py-5">
            <h1 class="display-4">Notebook Universe</h1>
            <p class="lead">Discover, Compare, and Find Your Perfect Laptop</p>
            <a href="{{ route('notebooks.index') }}" class="btn btn-light btn-lg">
                Explore Notebooks
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="col-12 my-4">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">{{ $stats['total_notebooks'] }}</h3>
                            <p class="card-text">Total Notebooks</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">{{ $stats['total_users'] }}</h3>
                            <p class="card-text">Registered Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">{{ $stats['total_manufacturers'] }}</h3>
                            <p class="card-text">Manufacturers</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title">${{ number_format($stats['average_notebook_price'], 2) }}</h3>
                            <p class="card-text">Avg Notebook Price</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Notebooks -->
        <div class="col-12 my-4">
            <h2 class="text-center mb-4">Latest Notebooks</h2>
            <div class="row">
                @foreach($latestNotebooks as $notebook)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $notebook->manufacturer }} {{ $notebook->type }}
                            </h5>
                            <p class="card-text">
                                Price: ${{ number_format($notebook->price, 2) }}
                                <br>Processor: {{ $notebook->processor->type }}
                            </p>
                            <a href="{{ route('notebooks.show', $notebook) }}" class="btn btn-primary">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Charts Section -->
        <div class="col-12 my-4">
            <div class="row">
                <div class="col-md-6">
                    <canvas id="manufacturerChart"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas id="priceRangeChart"></canvas>
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
    // Manufacturer Distribution Chart
    new Chart(document.getElementById('manufacturerChart'), {
        type: 'bar',
        data: {
            labels: {!! $manufacturerDistribution->pluck('manufacturer')->toJson() !!},
            datasets: [{
                label: 'Notebooks by Manufacturer',
                data: {!! $manufacturerDistribution->pluck('count')->toJson() !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        }
    });

    // Price Range Distribution Chart
    new Chart(document.getElementById('priceRangeChart'), {
        type: 'pie',
        data: {
            labels: Object.keys({!! json_encode($priceRanges) !!}),
            datasets: [{
                label: 'Notebooks by Price Range',
                data: Object.values({!! json_encode($priceRanges) !!}),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)'
                ]
            }]
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
    transition: transform 0.3s;
}
.card:hover {
    transform: scale(1.05);
}
</style>
@endpush

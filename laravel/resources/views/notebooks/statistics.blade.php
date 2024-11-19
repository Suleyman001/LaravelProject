@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notebook Statistics</h1>
    
    <div class="row">
        <div class="col-md-4">
            <h3>Notebooks by Manufacturer</h3>
            <canvas id="manufacturerChart"></canvas>
        </div>
        <div class="col-md-4">
            <h3>Average Price by Processor</h3>
            <canvas id="processorPriceChart"></canvas>
        </div>
        <div class="col-md-4">
            <h3>Notebooks by Operating System</h3>
            <canvas id="osDistributionChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manufacturer Chart
    new Chart(document.getElementById('manufacturerChart'), {
        type: 'bar',
        data: {
            labels: {!! $manufacturerStats->pluck('manufacturer')->toJson() !!},
            datasets: [{
                label: 'Notebooks by Manufacturer',
                data: {!! $manufacturerStats->pluck('count')->toJson() !!},
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        }
    });

    // Processor Price Chart
    new Chart(document.getElementById('processorPriceChart'), {
        type: 'pie',
        data: {
            labels: {!! $processorPriceStats->pluck('type')->toJson() !!},
            datasets: [{
                label: 'Average Price by Processor',
                data: {!! $processorPriceStats->pluck('avg_price')->toJson() !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)'
                ]
            }]
        }
    });

    // Operating System Distribution Chart
    new Chart(document.getElementById('osDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: {!! $osStats->pluck('name')->toJson() !!},
            datasets: [{
                label: 'Notebooks by Operating System',
                data: {!! $osStats->pluck('count')->toJson() !!},
                backgroundColor: [
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(75, 192, 192, 0.6)'
                ]
            }]
        }
    });
});
</script>
@endpush
@endsection
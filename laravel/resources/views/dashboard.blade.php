@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notebook Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Total Counts</div>
                <div class="card-body">
                    <p>Notebooks: {{ $stats['total_notebooks'] }}</p>
                    <p>Processors: {{ $stats['total_processors'] }}</p>
                    <p>Operating Systems: {{ $stats['total_operating_systems'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Top Manufacturers</div>
                <div class="card-body">
                    <ul>
                        @foreach($stats['manufacturers'] as $manufacturer)
                            <li>{{ $manufacturer->manufacturer }}: {{ $manufacturer->count }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Price Distribution</div>
                <div class="card-body">
                    <p>Budget (< 50,000): {{ $stats['price_distribution']['budget'] }}</p>
                    <p>Mid-Range (50,000 - 150,000): {{ $stats['price_distribution']['mid_range'] }}</p>
                    <p>High-End (> 150,000): {{ $stats['price_distribution']['high_end'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Top Processors</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Manufacturer</th>
                                <th>Type</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['top_processors'] as $processor)
                                <tr>
                                    <td>{{ $processor->manufacturer }}</td>
                                    <td>{{ $processor->type }}</td>
                                    <td>{{ $processor->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Operating System Distribution</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Operating System</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['operating_system_distribution'] as $os)
                                <tr>
                                    <td>{{ $os->name }}</td>
                                    <td>{{ $os->count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
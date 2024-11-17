@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Advanced Notebook Search</h1>

    <form method="GET" action="{{ route('notebooks.advanced-search') }}">
        <div class="row">
            <div class="col-md-3">
                <label>Manufacturer</label>
                <select name="manufacturer" class="form-control">
                    <option value="">All Manufacturers</option>
                    @foreach($filterOptions['manufacturers'] as $manufacturer)
                        <option value="{{ $manufacturer }}" 
                            {{ request('manufacturer') == $manufacturer ? 'selected' : '' }}>
                            {{ $manufacturer }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Processor</label>
                <select name="processor" class="form-control">
                    <option value="">All Processors</option>
                    @foreach($filterOptions['processors'] as $processor)
                        <option value="{{ $processor }}" 
                            {{ request('processor') == $processor ? 'selected' : '' }}>
                            {{ $processor }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Operating System</label>
                <select name="operating_system" class="form-control">
                    <option value="">All Operating Systems</option>
                    @foreach($filterOptions['operating_systems'] as $os)
                        <option value="{{ $os }}" 
                            {{ request('operating_system') == $os ? 'selected' : '' }}>
                            {{ $os }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Memory (Min)</label>
                <select name="memory" class="form-control">
                    <option value="">Any Memory</option>
                    @foreach($filterOptions['memory_options'] as $memory)
                        <option value="{{ $memory }}" 
                            {{ request('memory') == $memory ? 'selected' : '' }}>
                            {{ $memory }} MB
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-3">
                <label>Hard Disk (Min)</label>
                <select name="harddisk" class="form-control">
                    <option value="">Any Hard Disk</option>
                    @foreach($filterOptions['harddisk_options'] as $harddisk)
                        <option value="{{ $harddisk }}" 
                            {{ request('harddisk') == $harddisk ? 'selected' : '' }}>
                            {{ $harddisk }} GB
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Price Range</label>
                <select name="min_price" class="form-control">
                    <option value="">Min Price</option>
                    @foreach($filterOptions['price_ranges'] as $range)
                        <option value="{{ $range['min'] }}" 
                            {{ request('min_price') == $range['min'] ? 'selected' : '' }}>
                            {{ $range['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Sort By</label>
                <select name="sort_by" class="form-control">
                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Price</option>
                    <option value="memory" {{ request('sort_by') == 'memory' ? 'selected' : '' }}>Memory</option>
                    <option value="harddisk" {{ request('sort_by') == 'harddisk' ? 'selected' : '' }}>Hard Disk</option>
                </select>
            </div>

            <div class="col-md-3">
                <label>Sort Direction</label>
                <select name="sort_direction" class="form-control">
                    <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Search</button>
    </form>

    <div class="mt-4">
        <h2>Search Results ({{ $notebooks->total() }} notebooks)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Manufacturer</th>
                    <th>Model</th>
                    <th>Memory</th>
                    <th>Hard Disk</th>
                    <th>Processor</th>
                    <th>OS</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notebooks as $notebook)
                <tr>
                    <td>{{ $notebook->manufacturer }}</td>
                    <td>{{ $notebook->type }}</td>
                    <td>{{ $notebook->memory }} MB</td>
                    <td>{{ $notebook->harddisk }} GB</td>
                    <td>{{ $notebook->processor->manufacturer }} {{ $notebook->processor->type }}</td>
                    <td>{{ $notebook->operatingSystem->name }}</td>
                    <td>{{ $notebook->price }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $notebooks->appends(request()->input())->links() }}
    </div>
</div>
@endsection
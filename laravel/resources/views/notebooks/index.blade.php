@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Notebooks</h1>

            {{-- Search and Filter Form --}}
            <form method="GET" action="{{ route('notebooks.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by notebook type" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('notebooks.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            {{-- Notebooks Table --}}
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Manufacturer</th>
                        <th>Type</th>
                        <th>Display</th>
                        <th>Memory</th>
                        <th>HDD</th>
                        <th>Processor</th>
                        <th>OS</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notebooks as $notebook)
                    <tr>
                        <td>{{ $notebook->manufacturer }}</td>
                        <td>{{ $notebook->type }}</td>
                        <td>{{ $notebook->display }}"</td>
                        <td>{{ $notebook->memory }} GB</td>
                        <td>{{ $notebook->harddisk }} GB</td>
                        <td>{{ $notebook->processor->type }}</td>
                        <td>{{ $notebook->operatingSystem->name }}</td>
                        <td>{{ number_format($notebook->price, 2) }}</td>
                        <td>
                            <a href="{{ route('notebooks.show', $notebook->id) }}" class="btn btn-sm btn-info">View</a>
                            
                            @auth
                                @if(auth()->user()->isAdmin() || auth()->user()->isUser())
                                    <a href="{{ route('notebooks.edit', $notebook->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                            @endauth
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $notebooks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
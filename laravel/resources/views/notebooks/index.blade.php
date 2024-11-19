@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-12">
            <h1>Notebook Inventory</h1>
        </div>
    </div>

    <table class="table table-hover table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Manufacturer</th>
                <th>Type</th>
                <th>Display</th>
                <th>Memory</th>
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
                <td>{{ $notebook->memory }} MB</td>
                <td>{{ number_format($notebook->price, 2) }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <a href="{{ route('notebooks.show', $notebook->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                        
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->user()->isUser())
                                <a href="{{ route('notebooks.edit', $notebook->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endif
                        @endauth
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $notebooks->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.075);
    }
    .btn-group .btn {
        margin-right: 2px;
    }
</style>
@endpush
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>{{ $notebook->manufacturer }} {{ $notebook->type }} Details</h2>
                    <a href="{{ route('notebooks.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Notebooks
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Notebook Specifications</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Manufacturer</th>
                                    <td>{{ $notebook->manufacturer }}</td>
                                </tr>
                                <tr>
                                    <th>Model Type</th>
                                    <td>{{ $notebook->type }}</td>
                                </tr>
                                <tr>
                                    <th>Display</th>
                                    <td>{{ $notebook->display }}" inches</td>
                                </tr>
                                <tr>
                                    <th>Memory</th>
                                    <td>{{ $notebook->memory }} MB</td>
                                </tr>
                                <tr>
                                    <th>Hard Disk</th>
                                    <td>{{ $notebook->harddisk }} GB</td>
                                </tr>
                                <tr>
                                    <th>Video Controller</th>
                                    <td>{{ $notebook->videocontroller }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h4>Additional Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Processor</th>
                                    <td>{{ $notebook->processor->manufacturer }} {{ $notebook->processor->type }}</td>
                                </tr>
                                <tr>
                                    <th>Operating System</th>
                                    <td>{{ $notebook->operatingSystem->name }}</td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td>{{ number_format($notebook->price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Available Pieces</th>
                                    <td>{{ $notebook->pieces }}</td>
                                </tr>
                                <tr>
                                    <th>Created By</th>
                                    <td>{{ $notebook->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $notebook->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @auth
                        <div class="card-footer">
                            @if(auth()->user()->isAdmin() || auth()->user()->id === $notebook->user_id)
                                <div class="btn-group" role="group">
                                    <a href="{{ route('notebooks.edit', $notebook->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Notebook
                                    </a>
                                    
                                    @if(auth()->user()->isAdmin())
                                        <form action="{{ route('notebooks.destroy', $notebook->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this notebook?')">
                                                <i class="fas fa-trash"></i> Delete Notebook
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .card-header {
        background-color: #e9ecef;
    }
</style>
@endpush
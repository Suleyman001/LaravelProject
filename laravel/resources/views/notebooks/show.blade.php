@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $notebook->manufacturer }} {{ $notebook->type }}</h2>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Display</th>
                            <td>{{ $notebook->display }}"</td>
                        </tr>
                        <tr>
                            <th>Memory</th>
                            <td>{{ $notebook->memory }} GB</td>
                        </tr>
                        <tr>
                            <th>Hard Disk</th>
                            <td>{{ $notebook->harddisk }} GB</td>
                        </tr>
                        <tr>
                            <th>Video Controller</th>
                            <td>{{ $notebook->videocontroller }}</td>
                        </tr>
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
                    </table>
                </div>

                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isUser())
                    <div class="card-footer">
                        <a href="{{ route('notebooks.edit', $notebook->id) }}" class="btn btn-warning">Edit</a>
                        
                        @if(auth()->user()->isAdmin())
                        <form action="{{ route('notebooks.destroy', $notebook->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        @endif
                    </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
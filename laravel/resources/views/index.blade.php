@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notebooks</h1>
    
    <form method="GET" action="{{ route('notebooks.index') }}">
        <input type="text" name="search" placeholder="Search notebooks">
        <select name="manufacturer">
            <option value="">All Manufacturers</option>
            <!-- Populate with unique manufacturers -->
        </select>
        <button type="submit">Filter</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Manufacturer</th>
                <th>Type</th>
                <th>Price</th>
                <th>Processor</th>
                <th>Operating System</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notebooks as $notebook)
            <tr>
                <td>{{ $notebook->manufacturer }}</td>
                <td>{{ $notebook->type }}</td>
                <td>{{ $notebook->price }}</td>
                <td>{{ $notebook->processor->manufacturer }} {{ $notebook->processor->type }}</td>
                <td>{{ $notebook->operatingSystem->name }}</td>
                <td>
                    <a href="{{ route('notebooks.show', $notebook->id) }}">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $notebooks->links() }}
</div>
@endsection
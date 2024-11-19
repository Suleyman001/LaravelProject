@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Notebook</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('notebooks.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Manufacturer</label>
                    <input type="text" name="manufacturer" class="form-control" 
                           value="{{ old('manufacturer') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Type</label>
                    <input type="text" name="type" class="form-control" 
                           value="{{ old('type') }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Processor</label>
                    <select name="processorid" class="form-control" required>
                        <option value="">Select Processor</option>
                        @foreach($processors as $processor)
                            <option value="{{ $processor->id }}" 
                                {{ old('processorid') == $processor->id ? 'selected' : '' }}>
                                {{ $processor->manufacturer }} - {{ $processor->type }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Operating System</label>
                    <select name="opsystemid" class="form-control" required>
                        <option value="">Select Operating System</option>
                        @foreach($operatingSystems as $os)
                            <option value="{{ $os->id }}" 
                                {{ old('opsystemid') == $os->id ? 'selected' : '' }}>
                                {{ $os->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Display (inches)</label>
                    <input type="number" name="display" class="form-control" 
                           value="{{ old('display') }}" step="0.1" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Memory (MB)</label>
                    <input type="number" name="memory" class="form-control" 
                           value="{{ old('memory') }}" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Hard Disk (GB)</label>
                    <input type="number" name="harddisk" class="form-control" 
                           value="{{ old('harddisk') }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Video Controller</label>
                    <input type="text" name="videocontroller" class="form-control" 
                           value="{{ old('videocontroller') }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" class="form-control" 
                           value="{{ old('price') }}" step="0.01" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Pieces Available</label>
            <input type="number" name="pieces" class="form-control" 
                   value="{{ old('pieces') }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Create Notebook</button>
    </form>
</div>
@endsection
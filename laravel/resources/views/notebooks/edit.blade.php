@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Notebook</h1>

    <form action="{{ route('notebooks.update', $notebook) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Manufacturer</label>
                    <input type="text" name="manufacturer" class="form-control" 
                           value="{{ old('manufacturer', $notebook->manufacturer) }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Type</label>
                    <input type="text" name="type" class="form-control" 
                           value="{{ old('type', $notebook->type) }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Processor</label>
                    <select name="processorid" class="form-control" required>
                        @foreach($processors as $processor)
                            <option value="{{ $processor->id }}" 
                                {{ $notebook->processorid == $processor->id ? 'selected' : '' }}>
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
                        @foreach($operatingSystems as $os)
                            <option value="{{ $os->id }}" 
                                {{ $notebook->opsystemid == $os->id ? 'selected' : '' }}>
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
                           value="{{ old('display', $notebook->display) }}" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Memory (MB)</label>
                    <input type="number" name="memory" class="form-control" 
                           value="{{ old('memory', $notebook->memory) }}" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Hard Disk (GB)</label>
                    <input type="number" name="harddisk" class="form-control" 
                           value="{{ old('harddisk', $notebook->harddisk) }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Video Controller</label>
                    <input type="text" name="videocontroller" class="form-control" 
                           value="{{ old('videocontroller', $notebook->videocontroller) }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" class="form-control" 
                           value="{{ old('price', $notebook->price) }}" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Pieces Available</label>
            <input type="number" name="pieces" class="form-control" 
                   value="{{ old('pieces', $notebook->pieces) }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Notebook</button>
    </form>
</div>
@endsection
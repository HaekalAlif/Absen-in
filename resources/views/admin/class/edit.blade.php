@extends('admin.layouts.layout')

@section('admin_page_title')
Edit Class - Admin Panel
@endsection

@section('admin_layout')
    <div class="container">
        <h3>Edit Class Page</h3>
        <form action="{{ route('class.update', $classroom->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Method spoofing untuk PUT request -->
            
            <div class="mb-3">
                <label for="name" class="form-label">Class Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $classroom->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="year" class="form-label">Batch Year</label>
                <input type="number" class="form-control" id="batch_year" name="batch_year" value="{{ old('batch_year', $classroom->batch_year) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Class</button>
        </form>
    </div>
@endsection

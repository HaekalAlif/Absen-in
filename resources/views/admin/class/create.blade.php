@extends('admin.layouts.layout')

@section('admin_page_title')
Create Class - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h3>Create New Class</h3>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('class.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Class Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Batch Year</label>
            <input type="number" class="form-control" id="batch_year" name="batch_year" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Class</button>
    </form>
</div>
@endsection

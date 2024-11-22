@extends('admin.layouts.layout')

@section('admin_page_title')
Edit Class - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h3>Edit Class</h3>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('class.update', $classroom->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Menentukan bahwa ini adalah request PUT -->
        
        <div class="mb-3">
            <label for="name" class="form-label">Class Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $classroom->name }}" required>
        </div>

        <div class="mb-3">
            <label for="batch_year" class="form-label">Batch Year</label>
            <input type="number" class="form-control" id="batch_year" name="batch_year" value="{{ $classroom->batch_year }}" required>
        </div>

        <!-- Tambahkan input untuk tahun ajaran dengan value dari data yang sudah ada -->
        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun Ajaran</label>
            <input type="text" class="form-control" id="tahun" name="tahun" value="{{ $classroom->tahun }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Class</button>
    </form>
</div>
@endsection

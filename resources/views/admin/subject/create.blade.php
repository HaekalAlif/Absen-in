@extends('admin.layouts.layout')

@section('admin_page_title')
Add Subject - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h1>Create New Subject</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('subject.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Subject</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="classroom_id" class="form-label">Kelas dan Angkatan</label>
            <select class="form-select" id="classroom_id" name="classroom_id" required>
                <option value="" disabled selected>Pilih Kelas dan Angkatan</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }} ({{ $classroom->batch_year }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="semester" class="form-label">Semester</label>
            <select name="semester" id="semester" class="form-select" required>
                <option value="" disabled selected>Pilih Semester</option>
                @for ($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Dosen</label>
            <select class="form-select" id="user_id" name="user_id" required>
                <option value="" disabled selected>Pilih Dosen</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection

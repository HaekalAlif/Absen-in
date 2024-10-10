@extends('admin.layouts.layout')

@section('admin_page_title')
Edit Subject - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h1>Edit Subject</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('subject.update', $subject->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Method spoofing untuk PUT -->
        
        <div class="mb-3">
            <label for="name" class="form-label">Nama Subject</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $subject->name }}" required>
        </div>

        <div class="mb-3">
            <label for="classroom_id" class="form-label">Kelas dan Angkatan</label>
            <select class="form-select" id="classroom_id" name="classroom_id" required>
                <option value="" disabled>Pilih Kelas dan Angkatan</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $classroom->id == $subject->classroom_id ? 'selected' : '' }}>
                        {{ $classroom->name }} (Angkatan: {{ $classroom->batch_year }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Dosen</label>
            <select class="form-select" id="user_id" name="user_id" required>
                <option value="" disabled>Pilih Dosen</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $subject->user_id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
    </form>
</div>
@endsection

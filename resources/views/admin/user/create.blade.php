@extends('admin.layouts.layout')

@section('admin_page_title')
Add User - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h1>Add User</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="1">Dosen</option>
                <option value="2">Mahasiswa</option>
            </select>
        </div>

        <div class="form-group">
            <label for="class_id">Kelas</label>
            <select class="form-control" id="class_id" name="class_id" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($classrooms as $class)
                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->batch_year }})</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection

@extends('admin.layouts.layout')

@section('admin_page_title')
Edit User - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h1>Edit User</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password (Kosongkan jika tidak ingin mengubah)</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Mahasiswa</option>
                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Dosen</option>
            </select>
        </div>

        <div class="form-group">
            <label for="class_id">Kelas</label>
            <select class="form-control" id="class_id" name="class_id">
                <option value="">-- Pilih Kelas --</option>
                @foreach($classrooms as $class)
                    <option value="{{ $class->id }}" {{ $user->class_id == $class->id ? 'selected' : '' }}>
                        {{ $class->name }} ({{ $class->batch_year }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="batch_year">Tahun Angkatan</label>
            <input type="text" class="form-control" id="batch_year" name="batch_year" value="{{ old('batch_year', $user->batch_year) }}">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

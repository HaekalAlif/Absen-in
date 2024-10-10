@extends('admin.layouts.layout')

@section('admin_page_title')
Manage Users - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h1>Manage Users</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Kelas</th>
                <th>Tahun Angkatan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role == 0)
                        Admin
                    @elseif($user->role == 1)
                        Dosen
                    @else
                        Mahasiswa
                    @endif
                </td>
                <td>{{ $user->classroom ? $user->classroom->name : '-' }}</td>
                <td>{{ $user->batch_year }}</td>
                <td>{{ $user->status }}</td>
                <td>
                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                    @if($user->role == 2) <!-- Cek apakah user adalah mahasiswa -->
                        <a href="{{ route('user.qr_code', $user->id) }}" class="btn btn-info">Cek QR</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

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

    <form method="GET" action="{{ route('user.manage') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control">
                    <option value="">Pilih Role</option>
                    <option value="1" {{ request('role') == 1 ? 'selected' : '' }}>Dosen</option>
                    <option value="2" {{ request('role') == 2 ? 'selected' : '' }}>Mahasiswa</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="class_id">Kelas</label>
                <select name="class_id" id="class_id" class="form-control">
                    <option value="">Pilih Kelas</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ request('class_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="batch_year">Tahun Angkatan</label>
                <input type="number" name="batch_year" id="batch_year" class="form-control" value="{{ request('batch_year') }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Filter</button>
        <a href="{{ route('user.manage') }}" class="btn btn-secondary mt-3">Reset</a>
    </form>

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
                @if($user->role != 0)  <!-- Pengecualian untuk Admin -->
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 1)
                                Dosen
                            @elseif($user->role == 2)
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
                            @if($user->role == 2)
                                <a href="{{ route('user.qr_code', $user->id) }}" class="btn btn-info">Cek QR</a>
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection

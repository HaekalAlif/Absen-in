@extends('admin.layouts.layout')

@section('admin_page_title')
Add Subject - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h1>Manage Subjects</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('subject.create') }}" class="btn btn-primary mb-3">Add New Subject</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Subject</th>
                <th>Kelas</th>
                <th>Tahun Angkatan</th> <!-- Kolom Tahun Angkatan ditambahkan -->
                <th>Dosen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects as $subject)
            <tr>
                <td>{{ $subject->id }}</td>
                <td>{{ $subject->name }}</td>
                <td>{{ $subject->classroom->name }}</td>
                <td>{{ $subject->classroom->batch_year }}</td> <!-- Menampilkan Tahun Angkatan -->
                <td>{{ $subject->user->name }}</td>
                <td>
                    <a href="{{ route('subject.edit', $subject->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('subject.destroy', $subject->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus subject ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

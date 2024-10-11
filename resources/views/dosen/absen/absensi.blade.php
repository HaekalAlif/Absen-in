@extends('dosen.layouts.layout')
@section('dosen_page_title')
Absensi - Dosen Panel
@endsection
@section('dosen_layout')
<div class="container mt-4">
    <h2>Rekap Absensi Mahasiswa</h2>

    <!-- Pilihan Kelas -->
    <div class="form-group">
        <label for="classroom">Pilih Kelas:</label>
        <select id="classroom" class="form-control">
            @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Daftar Absensi -->
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $absen) <!-- Ubah schedule menjadi absensi -->
                <tr>
                    <td>{{ $absen->user->name }}</td>
                    <td>{{ $absen->date }}</td>
                    <td>{{ $absen->start_time }}</td>
                    <td>{{ $absen->end_time }}</td>
                    <td>{{ $absen->attendance_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
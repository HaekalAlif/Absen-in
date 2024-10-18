@extends('dosen.layouts.layout')

@section('dosen_page_title')
Absensi - Dosen Panel
@endsection

@section('dosen_layout')
<div class="container mt-4">
    <h2>Rekap Absensi Mahasiswa</h2>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Status Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $absen)
                <tr>
                    <td>{{ $absen->classroom->name }}</td>
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

@extends('dosen.layouts.layout')

@section('dosen_page_title')
Detail Absensi - Mata Kuliah: {{ $subject->name }}
@endsection

@section('dosen_layout')
<div class="container">
    <h1>Detail Absensi Mata Kuliah: {{ $subject->name }}</h1>

    <h3>Daftar Mahasiswa yang Terdaftar di Kelas: {{ $classroom->name }}</h3>

    <form action="{{ route('absensi.updateAll', [
            'absenId' => $id,  
            'subject_id' => $subject->id, 
            'classroom_id' => $classroom->id,
            'user_id' => Auth::user()->id 
]) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Input untuk subject_id, classroom_id, dan absen_id -->
    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
    <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
    <input type="hidden" name="absen_id" value="{{ $id }}">

    <table class="table">
        <thead>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>Status Absensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                @foreach($attendanceRecords as $attendance)
                    @php
                        $rekap = $rekapAbsensi->where('user_id', $student->id)
                                              ->where('absen_id', $attendance->id)
                                              ->first();

                        $status = $rekap ? $rekap->attendance_status : 'alpha';
                    @endphp

                    <tr>
                        <td>{{ $student->name }}</td>

                        <td>
                            <select name="attendance_statuses[{{ $rekap->id ?? 'new_' . $student->id . '_' . $attendance->id }}]" class="form-control">
                                <option value="alpha" {{ $status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                <option value="hadir" {{ $status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="izin" {{ $status == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="sakit" {{ $status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            </select>
                        </td>

                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Simpan Semua Perubahan</button>
</form>


</div>
@endsection

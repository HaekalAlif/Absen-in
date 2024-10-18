@extends('dosen.layouts.layout')

@section('dosen_page_title')
Create Absensi - Dosen Panel
@endsection

@section('dosen_layout')
<div class="container mt-4">
    <h2>Buat Absensi</h2>

    <form action="{{ route('absen.store') }}" method="POST">
        @csrf
        
        <!-- Pilihan Kelas -->
        <div class="form-group">
            <label for="classroom_id" class="form-label">Kelas dan Angkatan</label>
            <select class="form-select" id="classroom_id" name="classroom_id" required>
                <option value="" disabled selected>Pilih Kelas dan Angkatan</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }} ({{ $classroom->batch_year }})</option>
                @endforeach
            </select>
        </div>
        
        <!-- Pilihan Subject -->
        <div class="form-group">
            <label for="subject">Pilih Mata Kuliah:</label>
            <select id="subject" name="subject_id" class="form-control">
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div> 

        <!-- Tanggal -->
        <div class="form-group">
            <label for="date">Tanggal:</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>

        <!-- Jam Mulai -->
        <div class="form-group">
            <label for="start_time">Jam Mulai:</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
        </div>

        <!-- Jam Selesai -->
        <div class="form-group">
            <label for="end_time">Jam Selesai:</label>
            <input type="time" name="end_time" id="end_time" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Absensi</button>
    </form>
</div>
@endsection
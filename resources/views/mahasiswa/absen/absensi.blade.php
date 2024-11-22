@extends('mahasiswa.layouts.layout')

@section('mahasiswa_page_title', 'Absensi - Mata Kuliah')

@section('mahasiswa_layout')
    <div class="container">
        <h2>Daftar Absensi</h2>

        <!-- Jika ada mata kuliah yang dipilih -->
        @isset($subject)
            <h3>Mata Kuliah: {{ $subject->name }}</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Akhir</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensi as $item)
                        <tr>
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->start_time }}</td>
                            <td>{{ $item->end_time }}</td>
                            <td style="color: black;">
                                {{ $item->attendance_status == 'open' || $item->attendance_status == 'pending' ? 'Buka' : 'Tutup' }}
                            </td>
                            <td>
                                @if($item->attendance_status == 'open' || $item->attendance_status == 'pending')
                                    @if($item->already_attended)
                                        <button class="btn btn-success" disabled>Sudah Absen</button>
                                    @else
                                        <button id="absenButton{{ $item->id }}" class="btn btn-danger"
                                           data-start-time="{{ $item->start_time }}"
                                           data-end-time="{{ $item->end_time }}"
                                           data-date="{{ $item->date }}"
                                           data-absensi-id="{{ $item->id }}">
                                            Absen
                                        </button>
                                    @endif
                                @else
                                    @if($item->already_attended)
                                        <button class="btn btn-success" disabled>Sudah Absen</button>
                                    @else
                                        <button class="btn btn-danger" disabled>Absen Ditutup</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Silakan pilih mata kuliah untuk melihat absensi.</p>
        @endisset
    </div>

@foreach($absensi as $item)
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const absensiButton = document.getElementById('absenButton{{ $item->id }}');
            if(absensiButton) {
                absensiButton.addEventListener('click', function (e) {
                    e.preventDefault();

                    const absensiId = this.getAttribute('data-absensi-id');
                    const startTime = this.getAttribute('data-start-time');
                    const endTime = this.getAttribute('data-end-time');
                    const absensiDate = this.getAttribute('data-date');

                    // Mendapatkan waktu Indonesia (WIB)
                    const currentDateTime = new Date();
                    const currentDate = currentDateTime.toISOString().split('T')[0]; // Format: DD/MM/YYYY
                    const currentTime = currentDateTime.toLocaleTimeString('en-GB', { hour12: false }); // Format: HH:mm:ss

                    // Memeriksa apakah tanggal sekarang sama dengan tanggal absensi
                    if (currentDate !== absensiDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tanggal Tidak Sesuai',
                            text: 'Tanggal absensi tidak sesuai dengan tanggal sekarang.',
                            confirmButtonText: 'OK'
                        });
                        return; // Hentikan eksekusi jika tanggal tidak sesuai
                    }

                    // Memeriksa apakah waktu sekarang berada dalam rentang waktu (start_time <= current_time <= end_time)
                    if (currentTime >= startTime && currentTime <= endTime) {
                        // Jika waktu sekarang valid, arahkan ke halaman QR Scanner
                        window.location.href = "{{ route('mahasiswa.absensi.qr', [
                            'absenId' => '__absenId__',
                            'subjectId' => $subject->id,
                            'classroomId' => $user->classroom->id
                        ]) }}".replace('__absenId__', absensiId);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Waktu Absen Tidak Valid',
                            text: 'Waktu absensi sudah berakhir atau belum dimulai.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    </script>
@endforeach

@endsection

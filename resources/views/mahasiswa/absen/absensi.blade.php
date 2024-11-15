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
                                        <a id="absenButton" 
                                           href="{{ route('mahasiswa.absensi.qr', [
                                               'absenId' => $item->id, 
                                               'subjectId' => $subject->id, 
                                               'classroomId' => $user->classroom->id
                                           ]) }}" 
                                           class="btn btn-danger"
                                           data-start-time="{{ $item->start_time }}"
                                           data-end-time="{{ $item->end_time }}"
                                           data-date="{{ $item->date }}"
                                           data-absensi-id="{{ $item->id }}">
                                            Absen
                                        </a>
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

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Fungsi untuk mendapatkan waktu server melalui AJAX
            function checkAbsensiTime(absensiId, startTime, endTime, absensiDate) {
                $.ajax({
                    url: "{{ route('mahasiswa.absensi.check-time') }}", // Gantilah dengan rute yang sesuai
                    method: "GET",
                    data: {
                        absensi_id: absensiId,
                        start_time: startTime,
                        end_time: endTime,
                        date: absensiDate
                    },
                    success: function(response) {
                        if (response.status === 'invalid') {
                            // Tampilkan SweetAlert jika waktu absen sudah lewat
                            Swal.fire({
                                icon: 'warning',
                                title: 'Waktu Absen Telah Berakhir',
                                text: 'Jam absensi telah berakhir atau belum dimulai.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Setelah SweetAlert ditutup, kembali ke halaman absensi
                                window.location.href = window.location.href;
                            });
                        } else {
                            // Jika waktu valid, lanjutkan ke halaman absen
                            window.location.href = response.url;
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal memeriksa waktu absensi.',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            document.querySelectorAll('#absenButton').forEach(button => {
                button.addEventListener('click', function(e) {
                    const absensiId = button.getAttribute('data-absensi-id');
                    const startTime = button.getAttribute('data-start-time').split(':');
                    const endTime = button.getAttribute('data-end-time').split(':');
                    const absensiDate = button.getAttribute('data-date');

                    checkAbsensiTime(absensiId, startTime, endTime, absensiDate);
                    e.preventDefault(); // Mencegah pengalihan halaman langsung
                });
            });
        </script>
    @endsection
@endsection

@extends('dosen.layouts.layout')

@section('dosen_page_title')
Absensi - {{ $subject->name }} - Dosen Panel
@endsection

@section('dosen_layout')

<div class="container">
    <h1>Absensi - Mata Kuliah: {{ $subject->name }}</h1>
    <br>
    {{-- Flash Message untuk error --}}
    @if(session('error'))
        <script>
            console.error("{{ session('error') }}");
            alert("{{ session('error') }}");
        </script>
    @endif

    {{-- Form Pembuatan Absensi --}}
    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="classroom_id">Kelas</label>
            <select name="classroom_id" id="classroom_id" class="form-control" required>
                <option value="{{ $subject->classroom_id }}" selected>{{ $subject->classroom->name }} ({{ $subject->classroom->batch_year }})</option>
            </select>
        </div>
        <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="start_time">Waktu Mulai</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end_time">Waktu Selesai</label>
            <input type="time" name="end_time" id="end_time" class="form-control" required>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Buat Absensi</button>
    </form>
    <br>
    {{-- Daftar Absensi --}}
    <h2>Daftar Absensi untuk Mata Kuliah: {{ $subject->name }}</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
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
                <td>{{ $item->id }}</td>
                <td>{{ $item->date }}</td>
                <td>{{ $item->start_time }}</td>
                <td>{{ $item->end_time }}</td>
                <td>
                    <select onchange="updateAttendanceStatus({{ $item->id }}, this.value)" class="form-control">
                        <option value="open" {{ $item->attendance_status == 'buka' ? 'selected' : '' }}>Buka</option>
                        <option value="ditutup" {{ $item->attendance_status == 'tutup' ? 'selected' : '' }}>Tutup</option>
                    </select>
                </td>
                <td>
                    <a href="{{ route('absensi.detail', ['absenId' => $item->id, 'subject_id' => $item->subject_id, 'classroom_id' => $item->classroom_id]) }}" class="btn btn-info">Detail</a>
                    <a href="{{ route('absensi.edit', $item->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> <!-- Ikon untuk edit -->
                    </a>
                    <form id="delete-form-{{ $item->id }}" action="{{ route('absensi.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger" onclick="confirmDeleteForm({{ $item->id }})">
                            <i class="fas fa-trash"></i> <!-- Ikon untuk hapus -->
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>



    {{-- SweetAlert2 dan JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        // Fungsi update status absensi
        function updateAttendanceStatus(id, status) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Ambil token CSRF dari meta tag

            fetch(`/absensi/${id}/update`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token // Sertakan token CSRF di header
                },
                body: JSON.stringify({ attendance_status: status }) // Kirim status yang dipilih
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Status absensi berhasil diperbarui!',
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: `Gagal memperbarui status absensi: ${error.message}`,
                });
                console.error('Error updating attendance status:', error);
            });
        }

        // Fungsi konfirmasi sebelum menghapus absensi
        function confirmDeleteForm(id) {
            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                text: 'Apakah Anda yakin ingin menghapus absensi ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Temukan form berdasarkan ID dan submit
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
    
</div>
@endsection

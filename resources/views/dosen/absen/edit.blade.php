@extends('dosen.layouts.layout')

@section('dosen_page_title')
Edit Absensi - Dosen Panel
@endsection

@section('dosen_layout')
<div class="container mt-4">
    <h1>Edit Absensi</h1>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}',
            });
        </script>
    @endif

    <form action="{{ route('absensi.update', $absensi->id) }}" method="POST" id="edit-absensi-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="classroom_id">Kelas</label>
            <select name="classroom_id" id="classroom_id" class="form-control" required>
                @foreach($classrooms as $class)
                    <option value="{{ $class->id }}" {{ $absensi->classroom_id == $class->id ? 'selected' : '' }}>
                        {{ $class->name }} ({{ $class->batch_year }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $absensi->date }}" required>
        </div>

        <div class="form-group">
            <label for="start_time">Waktu Mulai</label>
            <input type="time" name="start_time" id="start_time" class="form-control" value="{{ $absensi->start_time }}" required>
        </div>

        <div class="form-group">
            <label for="end_time">Waktu Selesai</label>
            <input type="time" name="end_time" id="end_time" class="form-control" value="{{ $absensi->end_time }}" required>
        </div>

        <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event)">Simpan</button>
    </form>

</div>

{{-- SweetAlert2 Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    // Fungsi konfirmasi sebelum submit form
    function confirmSubmit(event) {
        event.preventDefault(); // Mencegah form disubmit secara langsung

        Swal.fire({
            title: 'Konfirmasi Simpan',
            text: 'Apakah Anda yakin ingin menyimpan perubahan absensi ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika dikonfirmasi
                document.getElementById('edit-absensi-form').submit();
            }
        });
    }
</script>

@endsection

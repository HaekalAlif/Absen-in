@extends('admin.layouts.layout')

@section('admin_page_title')
Settings - Admin Panel
@endsection

@section('admin_layout')

<!-- Pesan Sukses dengan SweetAlert -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#08B2F0'
        });
    </script>
@endif

<!-- Pesan Error dengan SweetAlert -->
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#08B2F0'
        });
    </script>
@endif

<!-- Tombol Download Backup (Hanya Tampil Jika OTP Sudah Diverifikasi) -->
@if(session('otp_verified') == true)
    <div class="text-center mb-4">
        <a href="{{ route('admin.settings.backup') }}" class="btn btn-lg" style="background-color: #08B2F0; color: white; border-radius: 10px; padding: 10px 20px;">Download Backup Database</a>
    </div>
@else
    <div class="alert alert-warning mb-4" role="alert">
        Anda harus memverifikasi OTP untuk dapat mengunduh backup database.
    </div>
@endif

<!-- Form untuk Restore Database -->
<div class="mb-4">
    <h1 class="text-muted mb-3">Restore Database</h5>
    <form action="{{ route('admin.settings.restore') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="sql_file">Pilih File Backup (.sql):</label>
            <input type="file" name="sql_file" id="sql_file" class="form-control" accept=".sql" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3" style="background-color: #08B2F0; border-radius: 5px; padding: 10px 20px;">Restore Database</button>
    </form>
</div>

<!-- Form untuk Mengirim OTP -->
<div class="mb-4">
    <h2 class="text-muted mb-3">Kirim OTP</h5>
    <form action="{{ route('admin.settings.sendOtp') }}" method="POST">
        @csrf
        <div class="text-center">
            <button type="submit" class="btn btn-warning" style="background-color: #08B2F0; color: white; border-radius: 10px; padding: 10px 20px;">Kirim OTP ke Email</button>
        </div>
    </form>
</div>

<!-- Form untuk Verifikasi OTP -->
<div class="mb-4">
    <h2 class="text-muted mb-3">Verifikasi OTP</h5>
    <form action="{{ route('admin.settings.verifyOtp') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="otp">Masukkan OTP:</label>
            <input type="text" name="otp" id="otp" class="form-control" required>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary mt-3" style="background-color: #08B2F0; color: white; border-radius: 10px; padding: 10px 20px;">Verifikasi OTP</button>
        </div>
    </form>
</div>

@endsection

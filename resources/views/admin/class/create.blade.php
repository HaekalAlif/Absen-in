@extends('admin.layouts.layout')

@section('admin_page_title')
Create Class - Admin Panel
@endsection

@section('admin_layout')
<div class="container">
    <h1>Create New Class</h1>
    
    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    <form action="{{ route('class.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Kelas</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="batch_year" class="form-label">Angkatan</label>
            <input type="number" class="form-control" id="batch_year" name="batch_year" required>
        </div>

        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun Ajaran</label>
            <input type="text" class="form-control" id="tahun" name="tahun" required>
        </div>

        <button type="submit" class="btn btn-primary" style="background-color:#00AFEF">Simpan</button>
    </form>
</div>
@endsection

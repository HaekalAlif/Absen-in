@extends('mahasiswa.layouts.layout')

@section('mahasiswa_page_title', 'Settings - Mahasiswa Panel')

@section('mahasiswa_layout')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center font-weight-bold mb-4">Pengaturan Akun</h3>

            <!-- Form Pengaturan Notifikasi -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pengaturan Notifikasi</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="emailNotifications">
                            <label class="form-check-label" for="emailNotifications">
                                Notifikasi Melalui Email
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="pushNotifications">
                            <label class="form-check-label" for="pushNotifications">
                                Notifikasi Push
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Simpan Pengaturan</button>
                    </form>
                </div>
            </div>

            <!-- Form Pilihan Bahasa -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pengaturan Bahasa</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="language" class="form-label">Pilih Bahasa</label>
                            <select class="form-select" id="language">
                                <option value="id">Bahasa Indonesia</option>
                                <option value="en">English</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                    </form>
                </div>
            </div>

            <!-- Form Mode Tema -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pengaturan Tema</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="darkMode">
                            <label class="form-check-label" for="darkMode">Aktifkan Mode Gelap</label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Simpan Pengaturan</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

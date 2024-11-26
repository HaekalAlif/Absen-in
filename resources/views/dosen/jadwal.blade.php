@extends('dosen.layouts.layout')
@section('dosen_page_title')
Jadwal - Dosen Panel
@endsection
@section('dosen_layout')
    <div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="text-center font-weight-bold mb-4">Jadwal Kuliah Anda</h3>

            <!-- Tabel Jadwal Mahasiswa -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="2">Waktu</th>
                            <th class="text-center" colspan="5">Hari</th>
                        </tr>
                        <tr>
                            <th class="text-center">Senin</th>
                            <th class="text-center">Selasa</th>
                            <th class="text-center">Rabu</th>
                            <th class="text-center">Kamis</th>
                            <th class="text-center">Jumat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Baris 1: Jam 08:00 - 09:00 -->
                        <tr>
                            <td class="text-center">08:00 - 09:00</td>
                            <td>
                                Algoritma & Pemrograman
                            </td>
                            <td>
                                Basis Data
                            </td>
                            <td>
                                Matematika Diskrit
                            </td>
                            <td>
                                Jaringan Komputer
                            </td>
                            <td>
                                Sistem Operasi
                            </td>
                        </tr>

                        <!-- Baris 2: Jam 09:00 - 10:00 -->
                        <tr>
                            <td class="text-center">09:00 - 10:00</td>
                            <td>
                                Bahasa Inggris
                            </td>
                            <td>
                                Struktur Data
                            </td>
                            <td>
                                Sistem Informasi
                            </td>
                            <td>
                                Jaringan Komputer
                            </td>
                            <td>
                                Matematika Diskrit
                            </td>
                        </tr>

                        <!-- Baris 3: Jam 10:00 - 11:00 -->
                        <tr>
                            <td class="text-center">10:00 - 11:00</td>
                            <td>
                                Matematika Diskrit
                            </td>
                            <td>
                                Algoritma & Pemrograman
                            </td>
                            <td>
                                Sistem Operasi
                            </td>
                            <td>
                                Basis Data
                            </td>
                            <td>
                                Sistem Informasi
                            </td>
                        </tr>

                        <!-- Baris 4: Jam 11:00 - 12:00 -->
                        <tr>
                            <td class="text-center">11:00 - 12:00</td>
                            <td>
                                Pemrograman Web
                            </td>
                            <td>
                                Bahasa Indonesia
                            </td>
                            <td>
                                Bahasa Inggris
                            </td>
                            <td>
                                Jaringan Komputer
                            </td>
                            <td>
                                Struktur Data
                            </td>
                        </tr>

                        <!-- Baris 5: Jam 12:00 - 13:00 -->
                        <tr>
                            <td class="text-center">12:00 - 13:00</td>
                            <td>
                                Pemrograman Web
                            </td>
                            <td>
                                Sistem Operasi
                            </td>
                            <td>
                                Algoritma & Pemrograman
                            </td>
                            <td>
                                Matematika Diskrit
                            </td>
                            <td>
                                Bahasa Inggris
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    
@endsection
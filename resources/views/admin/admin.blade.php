@extends('admin.layouts.layout')

@section('admin_page_title')
    Dashboard - Admin Panel
@endsection

@section('admin_layout')
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm rounded custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-building" style="font-size: 24px; color: black; margin-right: 10px;"></i>
                            <h5 class="card-title custom-card-title">Jumlah Kelas</h5>
                        </div>
                        <p class="card-text">{{ $total_classes }} Kelas</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm rounded custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-book" style="font-size: 24px; color: black; margin-right: 10px;"></i>
                            <h5 class="card-title custom-card-title">Jumlah Mata Kuliah</h5>
                        </div>
                        <p class="card-text">{{ $total_subjects }} Mata Kuliah</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm rounded custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-check" style="font-size: 24px; color: black; margin-right: 10px;"></i>
                            <h5 class="card-title custom-card-title">Jumlah Dosen</h5>
                        </div>
                        <p class="card-text">{{ $total_lecturers }} Dosen</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card shadow-sm rounded custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-fill" style="font-size: 24px; color: black; margin-right: 10px;"></i>
                            <h5 class="card-title custom-card-title">Jumlah Mahasiswa</h5>
                        </div>
                        <p class="card-text">{{ $total_students }} Mahasiswa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik jumlah mahasiswa per kelas -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm rounded custom-card">
                    <div class="card-body">
                        <h5 class="card-title custom-card-title">Jumlah Mahasiswa per Kelas</h5>
                        <canvas id="classroomChart" width="300" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inisialisasi Chart.js -->
    <script>
        var ctx = document.getElementById('classroomChart').getContext('2d');
        var classroomChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($classroomNames), // Nama kelas
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: @json($studentsCount), 
                    backgroundColor: '#3abef9',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection

@section('style')
    <style>
        /* Card Styling */
        .custom-card {
            border: 1px solid #e0e0e0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background-color: #fff;
        }

        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        /* Title Styling */
        .custom-card-title {
            font-size: 16px;
            font-weight: bold;
            color: #333; /* Warna netral untuk teks */
        }

        /* Text Styling */
        .card-text {
            font-size: 14px;
            color: #555; /* Sedikit lebih gelap untuk kontras */
        }

        /* Body Padding */
        .card-body {
            padding: 15px;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
@endsection

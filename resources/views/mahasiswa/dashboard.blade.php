@extends('mahasiswa.layouts.layout')

@section('mahasiswa_page_title', 'Dashboard - Mahasiswa Panel')

@section('mahasiswa_layout')
    <div class="container">
        <!-- Informasi Mahasiswa -->
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="profile-box p-4 bg-light rounded shadow-sm">
                    @if($user->profile_image)
                        <img src="{{ asset('profile_images/' . $user->profile_image) }}" 
                             class="rounded-circle mb-3" width="120" height="120" alt="Profile Image">
                    @else
                        <img src="https://via.placeholder.com/120" 
                             class="rounded-circle mb-3" alt="Default Profile">
                    @endif
                    <h5 class="fw-bold">{{ $user->name }}</h5>
                    <p class="mb-1 ">
                        Kelas {{ $user->classroom ? $user->classroom->name : 'Kelas Tidak Tersedia' }}
                    </p>
                    <p class="">Tahun Angkatan: {{ $user->batch_year }}</p>
                </div>
            </div>
        </div>

        <!-- Jumlah Mata Kuliah yang Diambil -->
        <div class="my-4">
            <h4 class="fw-bold">Jumlah Mata Kuliah yang Diambil</h4>
            <div class="bg-light p-3 rounded shadow-sm">
                <p class="mb-3">{{ $subjects->count() }} Mata Kuliah</p>
                
                <!-- Daftar Nama Mata Kuliah -->
                @if($subjects->isNotEmpty())
                    <ul class="list-group">
                        @foreach($subjects as $subject)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $subject->name }}
                                <span class="badge bg-primary">{{ $subject->code }}</span> 
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Tidak ada mata kuliah yang diambil.</p>
                @endif
            </div>
        </div>


        <!-- Rekap Absensi -->
        <div class="my-4">
            <h4 class="fw-bold">Rekap Absensi</h4>
            <div class="bg-light p-3 rounded shadow-sm">
                @if(isset($persentaseKehadiran))
                    <h5 class="fw-bold mb-3">Persentase Kehadiran: 
                        <span class="text-primary">{{ number_format($persentaseKehadiran, 2) }}%</span>
                    </h5>
                    <div class="d-flex justify-content-center">
                        <canvas id="attendanceChart" width="350" height="350"></canvas>
                    </div>
                @else
                    <p class="text-muted">Data persentase kehadiran tidak tersedia.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        @if(isset($persentaseKehadiran))
            var ctx = document.getElementById('attendanceChart').getContext('2d');
            var attendanceChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Tidak Hadir'],
                    datasets: [{
                        label: 'Persentase Kehadiran',
                        data: [{{ $persentaseKehadiran }}, {{ 100 - $persentaseKehadiran }}],
                        backgroundColor: ['#12B3E8', '#59637C'],
                        borderColor: ['#12B3E8', '#59637C'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                                }
                            }
                        }
                    }
                }
            });
        @endif
    </script>

    <style>
        /* Styling untuk elemen */
        .profile-box {
            max-width: 400px;
            margin: 0 auto;
        }

        #attendanceChart {
            max-width: 350px;
            max-height: 350px;
        }

        h4, h5 {
            color: #333;
        }

        p {
            color: #555;
        }
    </style>
@endsection

@extends('dosen.layouts.layout')

@section('dosen_page_title')
    Dashboard - Dosen Panel
@endsection

@section('dosen_layout')
    <div class="container">
        <div class="row">
            <!-- Displaying subjects data as cards -->
            @foreach($subjectNames as $key => $subjectName)
                <div class="col-md-3 mb-4">
                    <div class="card shadow-sm rounded custom-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-book" style="font-size: 24px; color: black; margin-right: 10px;"></i>
                                <h5 class="card-title custom-card-title">{{ $subjectName }} ({{ $classNames[$key] }})</h5>
                            </div>
                            <p class="card-text">Jumlah mahasiswa: {{ $studentsCount[$key] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Grafik jumlah mahasiswa per mata kuliah -->
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm rounded custom-card">
                    <div class="card-body">
                        <h5 class="card-title custom-card-title">Jumlah Mahasiswa per Mata Kuliah</h5>
                        <canvas id="subjectChart" width="300" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inisialisasi Chart.js -->
    <script>
        var ctx = document.getElementById('subjectChart').getContext('2d');
        var subjectChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($subjectNames), // Nama mata kuliah
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: @json($studentsCount),
                    backgroundColor: '#3abef9',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                var subjectName = tooltipItem.label;
                                var className = @json($classNames)[tooltipItem.dataIndex];
                                var studentsCount = tooltipItem.raw;
                                return subjectName + ' (' + className + '): ' + studentsCount + ' mahasiswa';
                            }
                        }
                    }
                },
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
            color: #333; 
        }

        /* Text Styling */
        .card-text {
            font-size: 14px;
            color: #555; 
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

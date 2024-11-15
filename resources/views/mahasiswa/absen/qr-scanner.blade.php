@extends('mahasiswa.layouts.layout')

@section('mahasiswa_page_title', 'Absensi - Mata Kuliah')

@section('mahasiswa_layout')
    <h3>QR Scanner untuk Mata Kuliah: {{ $subject->name ?? 'Mata Kuliah Tidak Diketahui' }}</h3>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <!-- Button untuk mengakses lokasi mahasiswa -->
                <button id="location-check-btn" class="btn btn-primary mb-4">Cek Lokasi untuk Absensi</button>

                <!-- QR Reader Section -->
                <div id="qr-reader" style="width: 50%; height: 400px; display: none;"></div>
                <div id="qr-reader-results"></div>
            </div>
        </div>
    </div>

    <style>
        #qr-reader {
            border: 2px dashed #4CAF50;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            background-color: #f9f9f9;
        }

        #qr-reader::before {
            content: "Scanning...";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #4CAF50;
            font-size: 20px;
            font-weight: bold;
            z-index: 10;
        }
    </style>

    <!-- Tambahkan SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const RADIUS_KM = 4; // Radius 1 km
            const CAMPUS_LAT = -7.562139308357166; // Ganti dengan latitude kampus Anda
            const CAMPUS_LNG =  110.83612721701697; // Ganti dengan longitude kampus Anda

            // Fungsi untuk menghitung jarak menggunakan Haversine formula
            function calculateDistance(lat1, lon1, lat2, lon2) {
                const R = 6371; // Radius bumi dalam kilometer
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                          Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                          Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c; // Jarak dalam kilometer
            }

            // Fungsi untuk memeriksa lokasi mahasiswa
            function checkLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;

                        const distance = calculateDistance(userLat, userLng, CAMPUS_LAT, CAMPUS_LNG);
                        console.log('Jarak ke kampus:', distance, 'km');

                        if (distance <= RADIUS_KM) {
                            // Tampilkan scanner jika dalam radius
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Anda berada dalam radius 1 km dari kampus. Silakan lanjutkan absen.',
                                icon: 'success',
                                confirmButtonText: 'Lanjut'
                            });
                            document.getElementById("qr-reader").style.display = "block";
                            startScanner();
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Anda berada di luar radius 1 km dari kampus.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }, function (error) {
                        console.error(error);
                        Swal.fire({
                            title: 'Kesalahan',
                            text: 'Gagal mengakses lokasi Anda.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                } else {
                    Swal.fire({
                        title: 'Tidak Didukung',
                        text: 'Perangkat Anda tidak mendukung akses lokasi.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            }

            // Fungsi untuk memulai QR scanner
            function startScanner() {
                let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let isScanning = false;

                function onScanSuccess(decodedText) {
                    if (isScanning) return;
                    isScanning = true;

                    Swal.fire({
                        title: 'QR Terdeteksi',
                        text: 'Memproses absensi...',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    setTimeout(() => {
                        fetch("{{ route('absensi.qr.submit') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                absen_id: {{ $absensi->id ?? 'null' }},
                                subject_id: {{ $subject->id ?? 'null' }},
                                classroom_id: {{ $classroom->id ?? 'null' }},
                                qr_code: decodedText
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: 'Absensi Berhasil!',
                                text: 'Data absensi Anda telah disimpan.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = "{{ route('mahasiswa.absensi', ['id' => $subject->id ?? 0]) }}";
                            });
                        })
                        .catch((error) => {
                            Swal.fire({
                                title: 'Kesalahan',
                                text: 'Gagal memperbarui absensi.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        })
                        .finally(() => {
                            setTimeout(() => { isScanning = false; }, 3000);
                        });
                    }, 2000);
                }

                function onScanFailure(error) {
                    console.warn(`Code scan error = ${error}`);
                }

                let html5QrCode = new Html5Qrcode("qr-reader");
                html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 250 },
                    onScanSuccess,
                    onScanFailure
                ).catch((err) => {
                    Swal.fire({
                        title: 'Kesalahan',
                        text: 'Gagal memulai pemindaian QR.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }

            document.getElementById("location-check-btn").addEventListener("click", checkLocation);
        });
    </script>
@endsection
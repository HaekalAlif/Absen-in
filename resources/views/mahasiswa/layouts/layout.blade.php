<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<title>Dashboard Mahasiswa</title>

	<link href="{{ asset('admin_asset/css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="#">
                    <img src="/admin_asset/img/icons/logo.png" alt="">
                    <span class="align-middle">Absen-in</span>
                </a>

                <ul class="sidebar-nav">
                

                    <li class="sidebar-item {{request()->routeIs('mahasiswa')?'active':''}}">
						<a class="sidebar-link" href="{{route('mahasiswa')}}">
              <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
            </a>
					</li>

                    @if(Auth::user()->role == 2)
                        
                        <li class="sidebar-item {{ request()->routeIs('mahasiswa.faq') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('mahasiswa.faq') }}">
                                <i class="align-middle" data-feather="file-plus"></i> 
                                <span class="align-middle">FAQ</span>
                            </a>
                        </li>

                        
                        <li class="sidebar-item {{ request()->routeIs('mahasiswa.jadwal') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('mahasiswa.jadwal') }}">
                                <i class="align-middle" data-feather="clipboard"></i> 
                                <span class="align-middle">Jadwal</span>
                            </a>
                        </li>

                        <li class="sidebar-header">
                            Mata Kuliah Saya
                        </li>
                        @php
                            // Ambil kelas mahasiswa saat ini
                            $classroom = Auth::user()->classroom;
                            $subjects = $classroom ? $classroom->subjects : collect();
                        @endphp

                        @if($subjects->isNotEmpty())
                            @foreach($subjects as $subject)
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="{{ route('mahasiswa.absensi', $subject->id) }}">
                                        <i class="align-middle" data-feather="book"></i>
                                        <span class="align-middle">{{ $subject->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li class="sidebar-item">
                                <span class="sidebar-link text-muted">Tidak ada mata kuliah yang tersedia.</span>
                            </li>
                        @endif

                        <li class="sidebar-item {{ request()->routeIs('mahasiswa.settings') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('mahasiswa.settings') }}">
                                <i class="align-middle" data-feather="settings"></i> 
                                <span class="align-middle">Settings</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								<span class="text-dark">{{ Auth::user()->name }}</span>
							</a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="profile"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<div class="dropdown-divider"></div>
								<!-- Authentication -->
								<form method="POST" action="{{ route('logout') }}">
									@csrf
									<x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
										{{ __('Log Out') }}
									</x-dropdown-link>
								</form>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">
					@yield('mahasiswa_layout')
				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="#" target="_blank"><strong>Absen-In</strong></a> &copy;
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="https://www.instagram.com/mhmmdhaekall_/" target="_blank">Haekal</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://www.instagram.com/luthfan.zip/" target="_blank">Luthfan</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://www.instagram.com/feysoniapl/" target="_blank">Fevy</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="{{ asset('admin_asset/js/app.js') }}"></script>

</body>
</html>

<script>


    // Pastikan untuk memulai hanya setelah DOM sepenuhnya dimuat
    window.addEventListener('DOMContentLoaded', (event) => {
        // Mendapatkan CSRF token dan menyimpannya di localStorage
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        localStorage.setItem('csrf-token', csrfToken); // Simpan di localStorage

        // Cek data classroom dan subjects
        let classroom = @json($classroom);
        let subjects = @json($subjects);

        // Log data classroom dan subjects
        if (!classroom) {
            console.error("Classroom data is missing or invalid.");
        } else {
            console.log("Classroom:", classroom);
        }

        if (!subjects || subjects.length === 0) {
            console.error("Subjects data is missing or there are no subjects available.");
        } else {
            console.log("Subjects:", subjects);
        }

        // Fungsi untuk menampilkan waktu saat ini di console setiap detik
        function logCurrentTime() {
            const currentTime = new Date();
            console.log("Current Time:", currentTime.toLocaleString());
        }

        // Log waktu setiap detik
        setInterval(logCurrentTime, 1000); // Setiap detik
    });


</script>


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
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

	<title>@yield('dosen_page_title')</title>

	<link href="{{ asset('admin_asset/css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	@yield('style')	
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            <li class="sidebar-item {{ request()->routeIs('dosen') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('dosen') }}">
                        <i class="align-middle" data-feather="calendar"></i> <span class="align-middle">Dashboard</span>
                    </a>
                </li>

            @if(Auth::user()->role == 1) 

                <li class="sidebar-item {{ request()->routeIs('dosen.jadwal') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('dosen.jadwal') }}">
                        <i class="align-middle" data-feather="calendar"></i> <span class="align-middle">Jadwal Kuliah</span>
                    </a>
                </li>

                <li class="sidebar-header">Mata Kuliah Saya</li>

                {{-- Dropdown untuk mata kuliah yang diajarkan oleh dosen --}}
				@foreach(Auth::user()->taughtSubjects as $subject)
					<li class="sidebar-item {{ request()->routeIs('dosen.absensi') && request()->route('id') == $subject->id ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('dosen.absensi', $subject->id) }}">
							<i class="align-middle" data-feather="book"></i>
							<span class="align-middle">{{ $subject->name }} - {{ $subject->classroom->name }}</span> <!-- Menambahkan nama kelas -->
						</a>
					</li>
				@endforeach
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
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
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
					@yield('dosen_layout')
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

	<script src="{{ asset('admin_asset/js/app.js') }}"></script>
	@yield('scripts')	
</body>

</html>

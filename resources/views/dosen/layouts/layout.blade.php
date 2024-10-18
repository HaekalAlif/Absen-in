<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/pages-blank.html" />

	<title>@yield('dosen_page_title')</title>

	<link href="{{ asset('admin_asset/css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="#">
					<span class="align-middle">Absen-in</span>
				</a>

				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Pages
					</li>

					<li class="sidebar-item {{ request()->routeIs('admin') ? 'active' : '' }}">
						<a class="sidebar-link" href="{{ route('admin') }}">
							<i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
						</a>
					</li>

					@if(Auth::user()->role == 1)  {{-- Sidebar untuk dosen --}}
						<li class="sidebar-header">
							Jadwal
						</li>

						<li class="sidebar-item {{ request()->routeIs('dosen.jadwal') ? 'active' : '' }}">
							<a class="sidebar-link" href="{{ route('dosen.jadwal') }}">
								<i class="align-middle" data-feather="file-plus"></i> <span class="align-middle">Jadwal Kuliah</span>
							</a>
						</li>

						<li class="sidebar-header">
							Absensi
						</li>

						<li class="sidebar-item {{ request()->routeIs('absen.absensi') ? 'active' : '' }}">
							<a class="sidebar-link" href="{{ route('absen.absensi') }}">
								<i class="align-middle" data-feather="clipboard"></i> <span class="align-middle">Absensi</span>
							</a>
						</li>

						<li class="sidebar-item {{ request()->routeIs('absen.create') ? 'active' : '' }}">
							<a class="sidebar-link" href="{{ route('absen.create') }}">
								<i class="align-middle" data-feather="clipboard"></i> <span class="align-middle">Create Absensi</span>
							</a>
						</li>

						<li class="sidebar-header">
							Settings
						</li>

						<li class="sidebar-item {{ request()->routeIs('dosen.settings') ? 'active' : '' }}">
							<a class="sidebar-link" href="{{ route('dosen.settings') }}">
								<i class="align-middle" data-feather="settings"></i> <span class="align-middle">Settings</span>
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
								<img src="img/avatars/avatar.jpg" class="avatar img-fluid rounded me-1" alt="Charles Hall" />
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
								<a class="text-muted" href="https://adminkit.io/" target="_blank"><strong>AdminKit</strong></a> &copy;
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Support</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Help Center</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Privacy</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Terms</a>
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

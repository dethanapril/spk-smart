<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <meta content="" name="description">
        <meta content="" name="keywords">

        <!-- Favicons -->
        <link href="{{asset('fe/img/logo.png')}}" rel="icon">
        <link href="{{asset('fe/img/logo.png')}}" rel="apple-touch-icon">

        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('admin/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
        <link href="{{asset('admin/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
        <link href="{{asset('admin/assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
        <link href="{{asset('admin/assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
        <link href="{{asset('admin/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
        <link href="{{asset('admin/assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

        <!-- Template Main CSS File -->
        <link href="{{asset('admin/assets/css/style.css')}}" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
        <a href="/dashboard" class="logo d-flex align-items-center">
            <img src="{{asset('fe/img/logo.png')}}" alt="">
            <span class="d-none d-lg-block">SPK-SMART</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->


        <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <img src="{{asset('admin/assets/img/profile-img.jpg')}}" alt="Profile" class="rounded-circle">
                <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
            </a><!-- End Profile Iamge Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                <li>
                <hr class="dropdown-divider">
                </li>

                <li>
                <form action="{{route('logout')}}" method="post">
                    @csrf
                    <a class="dropdown-item d-flex align-items-center" href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Sign Out</span>
                    </a>
                </form>
                
                </li>

            </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? '' : 'collapsed' }}" href="/dashboard">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('siswas.index') ? '' : 'collapsed' }}" href="{{ route('siswas.index') }}">
                    <i class="bi bi-menu-button-wide"></i>
                    <span>Data Siswa</span>
                </a>
            </li><!-- End Alternative Page Nav -->

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('kriterias.index') ? '' : 'collapsed' }}" href="{{ route('kriterias.index') }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Data Kriteria</span>
                </a>
            </li><!-- End Kriteria Page Nav -->

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('penilaian.index') ? '' : 'collapsed' }}" href="{{ route('penilaian.index') }}">
                    <i class="bi bi-bar-chart"></i>
                    <span>Data Penilaian</span>
                </a>
            </li><!-- End Nilai Alternative Page Nav -->

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('perhitungan.index') ? '' : 'collapsed' }}" href="{{ route('perhitungan.index') }}">
                    <i class="bi bi-layout-text-window-reverse"></i>
                    <span>Data Perhitungan</span>
                </a>
            </li><!-- End Hasil Perhitungan Page Nav -->

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('hasil.index') ? '' : 'collapsed' }}" href="{{ route('hasil.index') }}">
                    <i class="bi bi-layout-text-window-reverse"></i>
                    <span>Laporan</span>
                </a>
            </li><!-- End Laporan Page Nav -->

        </ul>

    </aside><!-- End Sidebar-->

    <main id="main" class="main">

        @yield('content')

    </main><!-- End #main -->

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                });
            });
        </script>
    @endif

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
        &copy; Copyright <strong><span>Laravel SPK</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
        Designed by <a href="#">Sinyo</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{asset('admin/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/chart.js/chart.umd.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/echarts/echarts.min.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/quill/quill.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('admin/assets/vendor/php-email-form/validate.js')}}"></script>

    <!-- Template Main JS File -->
    <script src="{{asset('admin/assets/js/main.js')}}"></script>

    </body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />

        <!-- Favicons -->
        <link href="{{asset('fe/img/favicon.png')}}" rel="icon" />
        <link href="{{asset('fe/img/apple-touch-icon.png')}}" rel="apple-touch-icon" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com" rel="preconnect" />
        <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet"
        />

        <!-- library CSS Files -->
        <link
            href="{{asset('fe/library/bootstrap/css/bootstrap.min.css')}}"
            rel="stylesheet"
        />
        <link href="{{asset('fe/library/aos/aos.css')}}" rel="stylesheet" />

        <!-- Main CSS File -->
        <link href="{{asset('fe/css/main.css')}}" rel="stylesheet" />
    </head>
    <body class="index-page">
        <main class="main">
            <!-- Hero Section -->
            <section id="hero" class="hero section">
                <div
                    class="container d-flex flex-column justify-content-center align-items-center text-center position-relative"
                    data-aos="zoom-out"
                >
                    <img src="{{asset('fe/img/logo.png')}}" class="animated" alt="" />
                    <h1>Sistem Pendukung Keputusan Pemilihan Siswa <span>Berprestasi</span></h1>
                    <p>
                        Sistem Pendukung Keputusan Untuk Menentukan Siswa Berprestasi
                        Berdasarkan Nilai Akademik dan Non Akademik Menggunakan Metode SMART (Simple Multi Attribute Rating Technique)
                    </p>
                    <div class="d-flex">
                        <a href="/login" class="btn-get-started scrollto"
                            >Mulai</a
                        >
                    </div>
                </div>
            </section>
            <!-- /Hero Section -->
        </main>

        <!-- library JS Files -->
        <script src="{{asset('fe/library/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('fe/library/aos/aos.js')}}"></script>
        <script src="{{asset('fe/library/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>

        <!-- Main JS File -->
        <script src="{{asset('fe/js/main.js')}}"></script>
    </body>
</html>

@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Detail Siswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Siswa</li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Siswa</h5>

                        <!-- Detail Siswa -->
                        <div class="row mb-3">
                            <label for="nisn" class="col-sm-3 col-form-label">NISN</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $siswa->nisn }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $siswa->nama }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jeniskelamin" class="col-sm-3 col-form-label">Kelas</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $siswa->kelas }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="jeniskelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $siswa->jeniskelamin }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9">
                                <p class="form-control-plaintext">{{ $siswa->alamat }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-9 offset-sm-3">
                                <a href="{{ route('siswas.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                            </div>
                        </div>
                        <!-- End Detail Siswa -->

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
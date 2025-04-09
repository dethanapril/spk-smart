@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Detail Penilaian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Penilaian</li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Penilaian - {{ $siswa->nama }}</h5>

                        <table class="table table-bordered">
                            <tr>
                                <th>NISN</th>
                                <td>{{ $siswa->nisn }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td>{{ $siswa->nama }}</td>
                            </tr>
                            <tr>
                                <th>Periode/Tahun</th>
                                <td>{{ $periode }}</td>
                            </tr>
                        </table>

                        <h5 class="mt-4">Nilai Penilaian</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penilaian as $p)
                                <tr>
                                    <td>{{ $p->kriteria }}</td>
                                    <td>{{ $p->nilai }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <a href="{{ route('penilaians.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                        <a href="{{ route('penilaians.edit', $siswa->nisn) }}" class="btn btn-primary btn-sm">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

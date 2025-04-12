@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Penilaian Siswa</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Penilaian</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="row">
        
        <!-- Tabel Penilaian -->
        <div class="col-lg-12">
            <div class="card recent-sales">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        {{-- <a class="btn btn-sm btn-primary" href="{{ route('penilaians.create') }}"><i class="bi bi-plus"></i></a> --}}
                    </div>
                    <h5 class="card-title">Daftar Penilaian Siswa</h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped datatable">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($siswas as $siswa)
                                    <tr>
                                        <td>{{ $siswa->nisn }}</td>
                                        <td>{{ $siswa->nama }}</td>
                                        <td>{{ $siswa->kelas }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('penilaian.edit', $siswa->nisn) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit me-1"></i> Kelola Nilai
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada data siswa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
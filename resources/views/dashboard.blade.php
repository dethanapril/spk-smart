@extends('layouts.app')
@section('content')

<style>
    .info-card {
        transition: transform 0.3s ease;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .info-card:hover {
        transform: translateY(-5px);
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
    }
    
    .primary-card .card-icon { background: #e3f2fd; color: #2196F3; }
    .success-card .card-icon { background: #e8f5e9; color: #4CAF50; }
    .warning-card .card-icon { background: #fff3e0; color: #FF9800; }
    .danger-card .card-icon { background: #ffebee; color: #f44336; }
    
    .progress {
        height: 25px;
        border-radius: 12px;
    }
    
    .progress-bar {
        border-radius: 12px;
        font-weight: 500;
    }
</style>

<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">
        <!-- Welcome Alert -->
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-emoji-smile me-2 fs-4"></i>
                <div>
                    <strong>Selamat Datang {{ Auth::user()->name }}!</strong> Kami senang melihat Anda kembali.
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="col-lg-12">
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card danger-card">
                        <div class="card-body">
                            <h5 class="card-title">Siswa</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-trophy fs-4"></i>
                                </div>
                                <div class="ps-3">
                                    <h2 class="mb-0">{{ $siswa ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-xxl-3 col-md-6">
                    <div class="card info-card success-card">
                        <div class="card-body">
                            <h5 class="card-title">Siswa Berprestasi</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <div class="ps-3">
                                    <h2 class="mb-0">{{ $siswaBerprestasi ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <div class="col-xxl-3 col-md-6">
                    <div class="card info-card primary-card">
                        <div class="card-body">
                            <h5 class="card-title">Kriteria</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-list-check fs-4"></i>
                                </div>
                                <div class="ps-3">
                                    <h2 class="mb-0">{{ $kriteria ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-xxl-3 col-md-6">
                    <div class="card info-card warning-card">
                        <div class="card-body">
                            <h5 class="card-title">Rata-rata Nilai</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-clipboard-data fs-4"></i>
                                </div>
                                <div class="ps-3">
                                    <h2 class="mb-0">{{ $rataNilai ?? 0 }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        <!-- Top Students Table -->
        {{-- <div class="col-12">
            <div class="card recent-sales">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center">
                        <i class="bi bi-arrow-up-right-circle me-2"></i>
                        Top 10 Siswa Berprestasi
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Rank</th>
                                    <th scope="col">NISN</th>
                                    <th scope="col">Nama Siswa</th>
                                    <th scope="col">Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasil as $index => $item)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>{{ $item->siswa_nisn }}</td>
                                        <td>{{ $item->siswa->nama }}</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $item->nilai_total_smart }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Criteria Table -->
        <div class="col-12">
            <div class="card recent-sales">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center">
                        <i class="bi bi-list-check me-2"></i>
                        Data Kriteria
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Kriteria</th>
                                    <th scope="col">Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriterias as $index => $kriteria)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $kriteria->nama }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $kriteria->bobot }}%" 
                                                 aria-valuenow="{{ $kriteria->bobot }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                {{ $kriteria->bobot }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
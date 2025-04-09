@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Hasil Penilaian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item active">Hasil Penilaian</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Tabel Hasil Penilaian -->
            <div class="col-lg-12">
                <div class="card recent-sales">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Top 10 Hasil Penilaian</h5>
                            <a href="{{ route('hasil.pdf') }}" class="btn btn-danger" target="_blank">
                                <i class="fas fa-file-pdf me-1"></i> Generate Laporan PDF
                            </a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Ranking</th>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th class="text-end">Nilai Akhir SMART</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($top10Hasil as $hasil)
                                        <tr>
                                            <td class="text-center"><strong>{{ $hasil->ranking }}</strong></td>
                                            <td>{{ $hasil->siswa->nisn ?? 'N/A' }}</td>
                                            <td>{{ $hasil->siswa->nama ?? 'N/A' }}</td>
                                            <td>{{ $hasil->siswa->kelas ?? 'N/A' }}</td>
                                            <td class="text-end">{{ number_format($hasil->nilai_total_smart, 5) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada data hasil perhitungan. Silakan lakukan perhitungan terlebih dahulu.</td>
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
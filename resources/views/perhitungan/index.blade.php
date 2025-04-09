@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Perhitungan SPK Metode SMART</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Penilaian</li>
                <li class="breadcrumb-item active">Perhitungan SPK</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Form untuk memilih periode -->
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Filter Periode</h5>
                        <form action="{{ route('perhitungan.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="periode" class="form-label fw-semibold">Pilih Periode</label>
                                    <select id="periode" name="periode" class="form-select form-select-md">
                                        @for($year = now()->year - 2; $year <= now()->year + 2; $year++)
                                            <option value="{{ $year }}" {{ $year == $periode ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-filter me-1"></i> Terapkan Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>            
            </div>

            <!-- Langkah 1: Normalisasi Bobot Kriteria -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Langkah 1: Normalisasi Bobot Kriteria</h5>
                        <p>Normalisasi bobot kriteria dilakukan dengan cara membagi setiap bobot dengan total bobot kriteria.</p>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Jenis</th>
                                    <th>Bobot Asli</th>
                                    <th>Bobot Normalisasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriterias as $kriteria)
                                <tr>
                                    <td>{{ $kriteria->nama }}</td>
                                    <td>{{ $kriteria->jenis }}</td>
                                    <td>{{ $kriteria->bobot }}</td>
                                    <td>{{ number_format($bobotNormalisasi[$kriteria->nama], 3) }}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td class="text-center" colspan="2"><strong>Total</strong></td>
                                    <td><strong>{{ $kriterias->sum('bobot') }}</strong></td>
                                    <td><strong>{{ number_format(array_sum($bobotNormalisasi), 3) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Langkah 2: Menghitung Nilai Utility -->
            <div class="col-lg-12">
                <div class="card recent-sales">
                    <div class="card-body">
                        <h5 class="card-title">Langkah 2: Menghitung Nilai Utility</h5>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    @foreach($kriterias as $kriteria)
                                    <th>{{ $kriteria->nama }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $siswa)
                                <tr>
                                    <td>{{ $siswa->nisn }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    @foreach($kriterias as $kriteria)
                                    <td>{{ number_format($siswa->utility[$kriteria->nama], 3) }}</td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Langkah 3: Menghitung Nilai Akhir -->
            <div class="col-lg-12">
                <div class="card recent-sales">
                    <div class="card-body">
                        <h5 class="card-title">Langkah 3: Menghitung Nilai Akhir</h5>
                        <p>Nilai akhir dihitung dengan menjumlahkan semua nilai utility yang telah dikalikan dengan bobot normalisasi.</p>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $siswa)
                                <tr>
                                    <td>{{ $siswa->nisn }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->nilai_akhir }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Langkah 4: 10 Nilai Tertinggi -->
            <div class="col-lg-12">
                <div class="card recent-sales">
                    <div class="card-body">
                        <h5 class="card-title">Langkah 4: 10 Nilai Tertinggi</h5>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sortedData as $index => $siswa)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $siswa->nisn }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->nilai_akhir }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
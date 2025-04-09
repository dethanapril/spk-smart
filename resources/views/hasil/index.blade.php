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
            <!-- Form untuk memilih periode -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Filter Periode</h5>
                        <form action="{{ route('hasil.index') }}" method="GET">
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="periode" class="form-label fw-semibold">Pilih Periode</label>
                                    <select id="periode" name="periode" class="form-select form-select-md">
                                        @for($year = 2022; $year <= now()->year + 2; $year++)
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

            <!-- Tabel Hasil Penilaian -->
            <div class="col-lg-12">
                <div class="card recent-sales">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Top 10 Hasil Penilaian</h5>
                            <a href="{{ route('hasil.pdf', ['periode' => $periode]) }}" class="btn btn-danger">
                                <i class="bi bi-file-earmark-pdf"></i> Generate PDF
                            </a>
                        </div>
                        <p><strong>Periode/Tahun : {{$periode}}</strong></p>
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
                                @foreach ($hasil as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->siswa->nisn }}</td>
                                        <td>{{ $item->siswa->nama }}</td>
                                        <td>{{ $item->nilai_akhir }}</td>
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
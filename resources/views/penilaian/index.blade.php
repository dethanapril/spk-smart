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
        <!-- Form untuk memilih periode -->
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Filter Periode</h5>
                    <form action="{{ route('penilaians.filter') }}" method="GET">
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

        <!-- Tabel Penilaian -->
        <div class="col-lg-12">
            <div class="card recent-sales">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Daftar Penilaian Siswa</h5>
                        <a class="btn btn-sm btn-primary" href="{{ route('penilaians.create') }}"><i class="bi bi-plus"></i></a>
                    </div>
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Periode</th>
                                <th>Nilai Raport</th>
                                <th>Presensi</th>
                                <th>Prestasi</th>
                                <th>Ekstrakurikuler</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $siswa)
                                <tr>
                                    <td>{{ $siswa->nisn }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->periode }}</td>
                                    <td>{{ $siswa->Nilai_Raport }}</td>
                                    <td>{{ $siswa->Presensi }}</td>
                                    <td>{{ $siswa->Prestasi }}</td>
                                    <td>{{ $siswa->Ekstrakulikuler }}</td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('penilaians.show', $siswa->nisn) }}" class="text-decoration-none">
                                            <span class="badge bg-secondary d-inline-block align-middle"><i class="bi bi-eye"></i></span>
                                        </a>
                                        <a href="{{ route('penilaians.edit', $siswa->nisn) }}" class="text-decoration-none">
                                            <span class="badge bg-warning d-inline-block align-middle"><i class="bi bi-pencil-square"></i></span>
                                        </a>
                                        <a href="#" onclick="event.preventDefault(); confirmDelete({{ $siswa->nisn }});" class="text-decoration-none">
                                            <span class="badge bg-danger d-inline-block align-middle"><i class="bi bi-trash"></i></span>
                                        </a>
                                        <form id="delete-form-{{ $siswa->nisn }}" action="{{ route('penilaians.destroy', $siswa->nisn) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
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
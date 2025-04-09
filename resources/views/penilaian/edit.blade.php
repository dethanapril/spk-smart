@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Edit Penilaian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Penilaian</li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Edit Penilaian</h5>

                        <!-- General Form Elements -->
                        <form action="{{ route('penilaian.update', $siswa->nisn) }}" method="POST">
                            @csrf
                            @method('PUT') {{-- Atau POST jika route Anda POST --}}
            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center align-middle" style="width: 20%;">Kriteria</th>
                                            @foreach ($semesters as $semester)
                                                <th class="text-center">Semester {{ $semester }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kriterias as $kriteria)
                                            <tr>
                                                <td class="align-middle">
                                                    <strong>{{ $kriteria->nama }}</strong> <br>
                                                    <small class="text-muted">({{ ucfirst($kriteria->jenis) }})</small>
                                                </td>
                                                @foreach ($semesters as $semester)
                                                    <td>
                                                        {{-- Nama input dibuat array: nilai[id_kriteria][nomor_semester] --}}
                                                        <input
                                                            type="number"
                                                            step="any" {{-- Izinkan desimal --}}
                                                            class="form-control form-control-sm @error('nilai.' . $kriteria->id . '.' . $semester) is-invalid @enderror"
                                                            name="nilai[{{ $kriteria->id }}][{{ $semester }}]"
                                                            {{-- Ambil nilai dari map jika ada, jika tidak kosongkan --}}
                                                            value="{{ old('nilai.' . $kriteria->id . '.' . $semester, $nilaiMap[$kriteria->id][$semester] ?? '') }}"
                                                            placeholder="Nilai {{ $semester }}"
                                                            {{-- Tambahkan atribut lain jika perlu (min, max) --}}
                                                            min="0"
                                                            >
                                                         @error('nilai.' . $kriteria->id . '.' . $semester)
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($semesters) + 1 }}" class="text-center">
                                                    Belum ada data kriteria. Silakan tambahkan kriteria terlebih dahulu.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
            
                            <div class="d-flex justify-content-end mt-3">
                                 <a href="{{ route('penilaian.index') }}" class="btn btn-secondary me-2">
                                     <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Siswa
                                 </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan Nilai
                                </button>
                            </div>
            
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

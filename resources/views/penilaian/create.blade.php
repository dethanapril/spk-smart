@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Tambah Penilaian</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Penilaian</li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Tambah Penilaian</h5>

                        <!-- General Form Elements -->
                        <form method="POST" action="{{ route('penilaians.store') }}">
                            @csrf
                            
                            <!-- Pilih Siswa -->
                            <div class="row mb-3">
                                <label for="nisn" class="col-sm-3 col-form-label">Nama</label>
                                <div class="col-sm-9">
                                    <select name="nisn" class="form-select">
                                        <option value="">Pilih Siswa</option>
                                        @foreach ($siswas as $siswa)
                                            <option value="{{ $siswa->nisn }}" {{ old('nisn') == $siswa->nisn ? 'selected' : '' }}>
                                                {{ $siswa->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('nisn'))
                                        <span class="text-danger">{{ $errors->first('nisn') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Pilih Periode -->
                            <div class="row mb-3">
                                <label for="periode" class="col-sm-3 col-form-label">Periode</label>
                                <div class="col-sm-9">
                                    <select name="periode" class="form-select">
                                        <option value="">Pilih Periode</option>
                                        @for ($year = now()->year - 2; $year <= now()->year + 2; $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>   
                                    @if ($errors->has('periode'))
                                        <span class="text-danger">{{ $errors->first('periode') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Input Nilai untuk Setiap Kriteria -->
                            @foreach ($kriterias as $kriteria)
                            <div class="row mb-3">
                                <label for="nilai[{{ $kriteria->id }}]" class="col-sm-3 col-form-label">{{ $kriteria->nama }}</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nilai[{{ $kriteria->id }}]" class="form-control" 
                                           value="{{ old('nilai.' . $kriteria->id) }}" placeholder="Masukkan Nilai {{ $kriteria->nama }}">
                                    @if ($errors->has('nilai.' . $kriteria->id))
                                        <span class="text-danger">{{ $errors->first('nilai.' . $kriteria->id) }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach

                            <div class="row mb-3">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                                </div>
                            </div>
                        </form><!-- End General Form Elements -->

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

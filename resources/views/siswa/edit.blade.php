@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Edit Siswa</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Siswa</li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Edit Siswa</h5>

                        <!-- General Form Elements -->
                        <form method="POST" action="{{ route('siswas.update', $siswa->nisn) }}">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="nisn" class="col-sm-3 col-form-label">NISN</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nisn" class="form-control" value="{{ $siswa->nisn }}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                <div class="col-sm-9">
                                    <input type="text" name="nama" class="form-control" value="{{ $siswa->nama }}">
                                    @if ($errors->has('nama'))
                                        <span class="text-danger">{{ $errors->first('nama') }}</span>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="row mb-3">
                                <label for="kelas" class="col-sm-3 col-form-label">Kelas</label>
                                <div class="col-sm-9">
                                    <select name="kelas" class="form-control">
                                        <option value="">Pilih Kelas</option>
                                        <option value="X" {{ $siswa->kelas == 'X' ? 'selected' : '' }}>X</option>
                                        <option value="XI" {{ $siswa->kelas == 'XI' ? 'selected' : '' }}>XI</option>
                                        <option value="XII" {{ $siswa->kelas == 'XII' ? 'selected' : '' }}>XII</option>
                                    </select>
                                    @if ($errors->has('kelas'))
                                        <span class="text-danger">{{ $errors->first('kelas') }}</span>
                                    @endif
                                </div>
                            </div> --}}
                            <div class="row mb-3">
                                <label for="jeniskelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                                <div class="col-sm-9">
                                    <select name="jeniskelamin" class="form-control">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ $siswa->jeniskelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $siswa->jeniskelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @if ($errors->has('jeniskelamin'))
                                        <span class="text-danger">{{ $errors->first('jeniskelamin') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                                <div class="col-sm-9">
                                    <input type="text" name="alamat" class="form-control" value="{{ $siswa->alamat }}">
                                    @if ($errors->has('alamat'))
                                        <span class="text-danger">{{ $errors->first('alamat') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </div>
                            </div>
                        </form><!-- End General Form Elements -->

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
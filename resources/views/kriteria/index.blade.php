@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h1>Daftar Kriteria</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Kriteria</li>
                <li class="breadcrumb-item active">Daftar</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-12">
                <div class="card recent-sales">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Daftar Kriteria</h5>
                            <button class="btn btn-sm btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal"><i class="bi bi-plus"></i></button>
                        </div>
                        <!-- Table with stripped rows -->
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Bobot</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriterias as $kriteria)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kriteria->nama }}</td>
                                        <td>{{ $kriteria->jenis }}</td>
                                        <td>{{ $kriteria->bobot }} %</td>
                                        <td>
                                            <span style="cursor: pointer" class="badge bg-secondary" data-bs-toggle="modal" data-bs-target="#showModal{{ $kriteria->id }}"><i class="bi bi-eye"></i></span>
                                            <span style="cursor: pointer" class="badge bg-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $kriteria->id }}"><i class="bi bi-pencil-square"></i></span>
                                            <span style="cursor: pointer" class="badge bg-danger" onclick="confirmDelete({{ $kriteria->id }})"><i class="bi bi-trash"></i></span>
                                            <form id="delete-form-{{ $kriteria->id }}" action="{{ route('kriterias.destroy', $kriteria->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Show Modal -->
                                    <div class="modal fade" id="showModal{{ $kriteria->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $kriteria->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="showModalLabel{{ $kriteria->id }}">Detail Kriteria</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Nama:</strong> {{ $kriteria->nama }}</p>
                                                    <p><strong>Jenis:</strong> {{ $kriteria->jenis }}</p>
                                                    <p><strong>Bobot:</strong> {{ $kriteria->bobot }} %</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal{{ $kriteria->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $kriteria->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $kriteria->id }}">Edit Kriteria</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('kriterias.update', $kriteria->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="nama" class="form-label">Nama</label>
                                                            <input type="text" name="nama" class="form-control" value="{{ $kriteria->nama }}">
                                                            @if ($errors->has('nama'))
                                                                <span class="text-danger">{{ $errors->first('nama') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="jenis" class="form-label">Jenis</label>
                                                            <select name="jenis" class="form-control">
                                                                <option value="benefit" {{ $kriteria->jenis == 'benefit' ? 'selected' : '' }}>Benefit</option>
                                                                <option value="cost" {{ $kriteria->jenis == 'cost' ? 'selected' : '' }}>Cost</option>
                                                            </select>
                                                            @if ($errors->has('jenis'))
                                                                <span class="text-danger">{{ $errors->first('jenis') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="bobot" class="form-label">Bobot</label>
                                                            <input type="text" name="bobot" class="form-control" value="{{ $kriteria->bobot }}">
                                                            @if ($errors->has('bobot'))
                                                                <span class="text-danger">{{ $errors->first('bobot') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Tambah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('kriterias.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}">
                            @if ($errors->has('nama'))
                                <span class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="jenis" class="form-label">Jenis</label>
                            <select name="jenis" class="form-control">
                                <option value="">Pilih Jenis</option>
                                <option value="benefit" {{ old('jenis') == 'benefit' ? 'selected' : '' }}>Benefit</option>
                                <option value="cost" {{ old('jenis') == 'cost' ? 'selected' : '' }}>Cost</option>
                            </select>
                            @if ($errors->has('jenis'))
                                <span class="text-danger">{{ $errors->first('jenis') }}</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="bobot" class="form-label">Bobot</label>
                            <input type="text" name="bobot" class="form-control" value="{{ old('bobot') }}">
                            @if ($errors->has('bobot'))
                                <span class="text-danger">{{ $errors->first('bobot') }}</span>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endsection
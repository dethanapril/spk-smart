@extends('layouts.app')
@section('content')
<div class="pagetitle">
    <h1>Data Siswa</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
        <li class="breadcrumb-item">Siswa</li>
        <li class="breadcrumb-item active">Data</li>
      </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
            {{-- <button type="button" class="btn btn-sm btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#basicModal">
                Import
            </button> --}}
            
            <div class="card recent-sales">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Daftar Siswa</h5>
                        <a class="btn btn-sm btn-primary" href="{{ route('siswas.create') }}"><i class="bi bi-plus"></i></a>
                    </div>
                    <!-- Table with stripped rows -->
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siswas as $siswa)
                                <tr>
                                    <td>{{ $siswa->nisn }}</td>
                                    <td>{{ $siswa->nama }}</td>
                                    <td>{{ $siswa->kelas }}</td>
                                    <td>{{ $siswa->jeniskelamin }}</td>
                                    <td>
                                        <a href="{{ route('siswas.show', $siswa->nisn) }}"><span class="badge bg-secondary"><i class="bi bi-eye"></i></span></a>
                                        <a href="{{ route('siswas.edit', $siswa->nisn) }}"><span class="badge bg-warning"><i class="bi bi-pencil-square"></i></span></a>
                                        <a href="#" onclick="event.preventDefault(); confirmDelete({{ $siswa->nisn }});" class="text-decoration-none">
                                            <span class="badge bg-danger">
                                                <i class="bi bi-trash"></i>
                                            </span>
                                        </a>
                                        <form id="delete-form-{{ $siswa->nisn }}" action="{{ route('siswas.destroy', $siswa->nisn) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
            <div class="modal fade" id="basicModal" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Import Data Siswa</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('siswas.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                                <input type="file" name="file" required>                        
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Import</button>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
        </div>
    </div>
</section>
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
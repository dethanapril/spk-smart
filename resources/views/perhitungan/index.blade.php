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
    </div>
    <section class="section dashboard">
        <div class="row">
            @if(isset($calculationData['periode_penilaian']) && !empty($calculationData['periode_penilaian']))
                <div class="alert alert-info" role="alert">
                    <strong>Periode Penilaian:</strong>
                    {{ implode(', ', $calculationData['periode_penilaian']->toArray()) }}
                </div>
            @endif
            @if($calculationData['has_errors'] && !empty($calculationData['error_messages']))
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Perhitungan Gagal!</h4>
                    <p>Terjadi kesalahan yang mencegah perhitungan dilanjutkan atau disimpan:</p>
                    <ul>
                        @foreach ($calculationData['error_messages'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @else

                {{-- Tampilkan Pesan Sukses Jika Ada --}}
                @if(!empty($calculationData['success_message']))
                    <div class="alert alert-success" role="alert">
                        {{ $calculationData['success_message'] }}
                    </div>
                @endif

                {{-- Card 0: Bobot Kriteria --}}
                <div class="card recent-sales">
                    <div class="card-body">
                        <h5 class="card-title">Tahap 0: Bobot Kriteria (Setelah Normalisasi Jika Perlu)</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kriteria</th>
                                    <th>Bobot Awal</th>
                                    <th>Bobot Normal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($calculationData['bobot_kriteria'] as $id => $bobotInfo)
                                    <tr>
                                        <td>{{ $id }}</td>
                                        <td style="text-align: left;">{{ $bobotInfo['nama'] }}</td>
                                        <td>{{ number_format($bobotInfo['bobot_awal'], 3) }}</td>
                                        <td>{{ number_format($bobotInfo['bobot_normal'], 3) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="table-light">
                                    <td colspan="3" style="text-align: right;"><strong>Total Bobot Normal:</strong></td>
                                    <td><strong>{{ number_format(collect($calculationData['bobot_kriteria'])->sum('bobot_normal'), 3) }}</strong></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                {{-- Card 1: Data Agregasi --}}
                <div class="card recent-sales">
                    <div class="card-body">
                        <h5 class="card-title">Tahap 1: Nilai Agregat (Total per Siswa per Kriteria)</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-sm datatable">
                                <thead>
                                <tr>
                                    <th>Siswa (NISN)</th>
                                    @foreach ($calculationData['kriterias'] as $kriteria)
                                        <th>{{ $kriteria->nama }} (C{{ $kriteria->id }})</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($calculationData['siswas'] as $siswa)
                                    <tr>
                                        <td>{{ $siswa->nama }} ({{ $siswa->nisn }})</td>
                                        @foreach ($calculationData['kriterias'] as $kriteria)
                                            <td>{{ $calculationData['data_agregasi'][$siswa->nisn][$kriteria->id] ?? '-' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Nilai Min/Max Kriteria --}}
                <div class="card recent-sales">
                    <div class="card-body">
                        <h5 class="card-title">Tahap 2: Nilai Minimum & Maksimum per Kriteria</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-sm datatable">
                                <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    <th>Nilai Minimum</th>
                                    <th>Nilai Maksimum</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($calculationData['kriterias'] as $kriteria)
                                    <tr>
                                        <td style="text-align: left;">{{ $kriteria->nama }} (C{{ $kriteria->id }})</td>
                                        <td>{{ $calculationData['min_max_values'][$kriteria->id]['min'] ?? 'N/A' }}</td>
                                        <td>{{ $calculationData['min_max_values'][$kriteria->id]['max'] ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                {{-- Card 3: Matriks Normalisasi (Utility) --}}
                <div class="card recent-sales">
                    <div class="card-body">
                        <h1 class="card-title">Tahap 3: Matriks Normalisasi (Nilai Utility 0-1)</h1>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-sm datatable">
                                <thead>
                                <tr>
                                    <th>Siswa (NISN)</th>
                                    @foreach ($calculationData['kriterias'] as $kriteria)
                                        <th>{{ $kriteria->nama }} (C{{ $kriteria->id }}) <br><small>({{ ucfirst($kriteria->jenis) }})</small></th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($calculationData['siswas'] as $siswa)
                                    <tr>
                                        <td>{{ $siswa->nama }} ({{ $siswa->nisn }})</td>
                                        @foreach ($calculationData['kriterias'] as $kriteria)
                                            {{-- Format nilai utility --}}
                                            <td>{{ number_format($calculationData['data_normalisasi'][$siswa->nisn][$kriteria->id] ?? 0, 4) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Card 4: Matriks Terbobot --}}
                <div class="card recent-sales">
                    <div class="card-body">
                        <h1 class="card-title">Tahap 4: Matriks Nilai Terbobot (Utility * Bobot)</h1>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-sm datatable">
                                <thead>
                                <tr>
                                    <th>Siswa (NISN)</th>
                                    @foreach ($calculationData['kriterias'] as $kriteria)
                                        <th>{{ $kriteria->nama }} (C{{ $kriteria->id }}) <br><small>(Bobot: {{ number_format($calculationData['bobot_kriteria'][$kriteria->id]['bobot_normal'] ?? 0, 3) }})</small></th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($calculationData['siswas'] as $siswa)
                                    <tr>
                                        <td>{{ $siswa->nama }} ({{ $siswa->nisn }})</td>
                                        @foreach ($calculationData['kriterias'] as $kriteria)
                                            {{-- Format nilai terbobot --}}
                                            <td>{{ number_format($calculationData['data_terbobot'][$siswa->nisn][$kriteria->id] ?? 0, 4) }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Card 5: Hasil Akhir & Ranking --}}
                <div class="card recent-sales">
                    <div class="card-header text-primary"><i class="fas fa-trophy me-1"></i></div>
                    <div class="card-body">
                        <h5 class="card-title">Tahap 5 & 6: Hasil Akhir dan Perankingan</h5>
                        <div class="table-responsive">
                            {{-- Urutkan berdasarkan ranking sebelum ditampilkan --}}
                            @php
                                $sortedHasil = collect($calculationData['hasil_final_ranking'])->sortBy('ranking')->all();
                            @endphp
                            <table class="table table-hover table-striped table-sm datatable">
                                <thead class="table-light">
                                <tr>
                                    <th>Ranking</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    {{-- <th>Kelas</th> --}}
                                    <th>Nilai Akhir SMART</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($sortedHasil as $nisn => $hasil)
                                    <tr>
                                        <td><strong>{{ $hasil['ranking'] }}</strong></td>
                                        <td>{{ $hasil['nisn'] }}</td>
                                        <td style="text-align: left;">{{ $hasil['nama'] }}</td>
                                        {{-- <td>{{ $hasil['kelas'] }}</td> --}}
                                        <td><strong>{{ number_format($hasil['nilai_total_smart'], 5) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            @if($calculationData['has_errors'])
                                                Hasil akhir tidak dapat ditampilkan karena terjadi error pada proses perhitungan.
                                            @else
                                                Belum ada hasil perhitungan yang tersimpan.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            @endif {{-- End else jika tidak ada error fatal --}}
        </div>
    </section>
@endsection
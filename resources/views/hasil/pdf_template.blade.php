<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judulLaporan ?? 'Laporan Hasil Seleksi' }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header-section {
            text-align: center;
            margin-bottom: 25px;
        }
        .header-section h1 {
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }
         .header-section p {
            font-size: 12px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px; /* Padding lebih kecil untuk PDF */
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        td.text-center, th.text-center {
            text-align: center;
        }
        td.text-end, th.text-end {
            text-align: right;
        }
        .footer-section {
            margin-top: 40px;
            font-size: 11px;
        }
        .signature-section {
            margin-top: 50px;
            width: 100%;
            /* page-break-inside: avoid; */ /* Coba hindari page break di area ttd */
        }
        .signature-section .signature-block {
            width: 30%; /* Sesuaikan lebar area ttd */
            float: right; /* Atau atur posisi lain */
            text-align: center;
        }
         .signature-section .signature-block .signature-space {
            height: 60px; /* Ruang untuk tanda tangan */
            margin-bottom: 5px;
            border-bottom: 1px solid #666; /* Opsional: garis bawah */
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>

    <div class="header-section">
        <h1>{{ $judulLaporan ?? 'Laporan Hasil Seleksi Siswa Berprestasi' }}</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 5%;">No</th>
                <th class="text-center" style="width: 15%;">NISN</th>
                <th>Nama Siswa</th>
                <th class="text-center" style="width: 10%;">Kelas</th>
                <th class="text-center" style="width: 15%;">Nilai SMART</th>
                <th class="text-center" style="width: 10%;">Ranking</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($allHasil as $index => $hasil)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $hasil->siswa->nisn ?? 'N/A' }}</td>
                    <td>{{ $hasil->siswa->nama ?? 'N/A' }}</td>
                    <td class="text-center">{{ $hasil->siswa->kelas ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($hasil->nilai_total_smart, 5) }}</td>
                    <td class="text-center">{{ $hasil->ranking }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Tidak ada data hasil perhitungan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-section">
        Dicetak pada: {{ $tanggalCetak ?? date('d M Y') }}
    </div>

    {{-- Bagian Tanda Tangan (Opsional) --}}
    <div class="signature-section clearfix">
        <div class="signature-block">
            Kupang, {{ $tanggalCetak ?? date('d M Y') }} <br>
            Mengetahui,<br>
            Kepala Sekolah
            <br><br>
            <div class="signature-space"></div>
            (___________________________) <br>
             NIP. [Isi NIP Kepala Sekolah]
        </div>
    </div>

</body>
</html>
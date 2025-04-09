<!DOCTYPE html>
<html>
<head>
    <title>Hasil Akhir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        p {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>10 Siswa dengan Skor Tertinggi</h1>
    <p><strong>Periode/Tahun: {{ $periode }}</strong></p>
    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hasil as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nisn }}</td>
                    <td>{{ $item->siswa->nama }}</td>
                    <td>{{ $item->siswa->kelas }}</td>
                    <td>{{ $item->nilai_akhir }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
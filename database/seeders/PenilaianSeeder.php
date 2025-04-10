<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Untuk timestamps

class PenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Annahme:
     * - Kriteria IDs: 1 = Nilai Raport, 2 = Presensi, 3 = Prestasi, 4 = Ekstrakulikuler
     * - SiswaSeeder sudah dijalankan sebelumnya dan data siswa ada di database atau kita gunakan list NISN yang sama.
     * - Data yang diberikan adalah untuk Semester 1. Semester 2-5 akan digenerate dengan variasi acak.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // --- Asumsi Mapping Kriteria ID ---
        $kriteriaMapping = [
            'Nilai Raport' => 1,
            'Presensi' => 2,
            'Prestasi' => 3,
            'Ekstrakulikuler' => 4,
        ];
        $kriteriaIds = array_values($kriteriaMapping); // [1, 2, 3, 4]

        // --- Ambil NISN Siswa (Sesuai urutan di SiswaSeeder) ---
        // Sebaiknya ambil langsung dari DB jika SiswaSeeder sudah jalan,
        // tapi untuk contoh ini kita list ulang NISN sesuai urutan data yang diberikan.
        $nisnSiswa = [
            '5080', '5081', '5082', '5083', '5084', '5085', '5086', '5087', '5088', '5089',
            '5090', '5091', '5092', '5093', '5094', '5095', '5096', '5097', '5098', '5099',
            '5100', '5101', '5102', '5103', '5104', '5105', '5106', '5107', '5108', '5109',
            '5110', '5111', '5112', '5113', '5114', '5115', '5116', '5117', '5118', '5119',
            '5120', '5121', '5122', '5123', '5124', '5125', '5126', '5127', '5128', '5129',
            '5130', '5131', '5132', '5133', '5134', '5135', '5136', '5137', '5138', '5139',
            '5140', '5141', '5142', '5143', '5144', '5145', '5146', '5147', '5148', '5149',
            '5150', '5151', '5152', '5153', '5154', '5155', '5156', '5157', '5158', '5159',
            '5160', '5161', '5162', '5163', '5164', '5165', '5166', '5167', '5168', '5169',
            '5170', '5171', '5172', '5173', '5174', '5175', '5176', '5177', '5178', '5179',
            '5180', '5181', '5182', '5183', '5184', '5185', '5186', '5187', '5188', '5189',
            '5190', '5191', '5192', '5193', '5194', '5195', '5196', '5197', '5198', '5199',
            '5200', '5201', '5202', '5203', '5204', '5205', '5206', '5207', '5208', '5209',
            '5210', '5211', '5212', '5213', '5214', '5215', '5216', '5217', '5218', '5219',
            '5220', '5221', '5222', '5223', '5224', '5225', '5226', '5227', '5228', '5229',
            '5230', '5231', '5232', '5233', '5234', '5235', '5236', '5237', '5238', '5239',
            '5240', '5241', '5242', '5243', '5244', '5245', '5246', '5247', '5248', '5249',
            '5250', '5251', '5252', '5253', '5254', '5255', '5256', '5257', '5258', '5259',
            '5260', '5261', '5262', '5263', '5264', '5265', '5266', '5267', '5268', '5269',
            '5270', '5271', '5272', '5273', '5274', '5275', '5276', '5277', '5278', '5279',
            '5280', '5281', '5282', '5283', '5284', '5285', '5286', '5287', '5288', '5289',
            '5290', '5291', '5292', '5293', '5294', '5295', '5296', '5297', '5298', '5299',
            '5300', '5301', '5302', '5303', '5304', '5305', '5306', '5307', '5308', '5309',
            '5310', '5311', '5312', '5313', '5314', '5315', '5316', '5317', '5318', '5319',
            '5320', '5321', '5322', '5323', '5324', '5325', '5326', '5327', '5328', '5329',
            '5330', '5331', '5332', '5333', '5334', '5335', '5336', '5337', '5338', '5339',
            '5340', '5341', '5342', '5343', '5344', '5345', '5346', '5347', '5348', '5349',
            '5350', '5351', '5352', '5353', '5354', '5355', '5356', '5357', '5358', '5359',
            '5360', '5361', '5362', '5363', '5364', '5365', '5366', '5367', '5368', '5369',
            '5370', '5371', '5372', '5373', '5374', '5375', '5376', '5377', '5378', '5379',
            '5380', '5381', '5382', '5383', '5384', '5385', '5386', '5387', '5388', '5389',
            '5390', '5391', '5392', '5393', '5394', '5395', '5396', '5397', '5398', '5399',
            '5400', '5401', '5402', '5403', '5404', '5405', '5406', '5407', '5408', '5409',
            '5410', '5411', '5412', '5413', '5414', '5415', '5416', '5417', '5418', '5419',
            '5420', '5421', '5422', '5423', '5424', '5425', '5426', '5427', '5428', '5429',
            '5430', '5431', '5432', '5433', '5434',
        ];
        $jumlahSiswa = count($nisnSiswa);

        // --- Data Penilaian Semester 1 (dari teks Anda) ---
        $dataSemester1Text = "
            79.07	0	0	 78.93
            78,93	2	0	 79.00
            80	0	0	79,87
            79,87	2	0	79,8
            79,93	0	0	79,8
            79,93	1	0	80,07
            78,8	6	0	 79.00
            79,73	0	0	79,6
            78.53	2	0	 78.93
            78,67	0	0	 78.87
            78	1	0	 78.00
            77,6	0	0	 77.33
            78.67	0	0	 79.00
            78.40	1	0	 78.87
            78,87	1	0	 79.00
            78.33	2	0	 78.33
            79,47	0	0	79,13
            79,4	0	0	 79.27
            79,6	5	0	79,47
            79,67	4	0	79,67
            78,87	2	0	78,73
            79.40	0	0	 79.33
            78.40	0	0	 78.47
            79,07	10	0	78,8
            79,8	2	0	79,93
            78,93	9	0	78,47
            79,4	0	0	79,33
            77,53	0	0	78,2
            79.47	0	0	 79.80
            79,8	0	0	79,67
            78,13	2	0	78,93
            78,47	1	0	 78.40
            80,47	0	0	 80.13
            79,07	0	0	 78.87
            79,27	0	0	79,13
            79	2	0	 79.07
            79,07	2	0	 79.20
            79	6	0	79
            79,27	2	0	79,4
            79,27	0	0	79
            78,07	1	0	 77.87
            78.87	1	0	 79.13
            79.20	0	0	 79.13
            77.33	1	0	 77.33
            80	3	0	80,07
            79.00	1	0	 79.33
            79.67	0	0	 79.47
            79.00	0	0	 78.80
            78.53	0	0	 78.33
            79,73	0	0	79,4
            79,6	17	0	79,73
            78,67	0	0	 78.80
            78,87	0	0	 78.87
            79,8	0	0	79,67
            79.20	0	0	 79.33
            80.80	0	0	 80.60
            79.53	0	0	 79.40
            76,47	1	0	 76.73
            78.33	0	0	 78.07
            79,8	0	0	79,47
            79,4	0	0	 79.33
            78	2	0	 77.80
            79,27	0	0	79,13
            79,53	0	0	79,53
            79,07	1	0	79,2
            79,53	0	0	79,4
            78.13	1	0	 77.80
            78.60	0	0	 78.73
            78,93	2	0	 79.07
            78,07	0	0	 78.13
            77,6	3	0	 77.27
            77,87	0	0	 78.27
            78,93	2	0	 79.00
            77,87	3	0	 78.00
            79,07	1	0	 79.13
            79,53	0	0	 79.53
            79,4	0	0	 79.40
            79,33	0	0	 79.53
            79,8	1	0	79,87
            80.60	2	0	 80.47
            79,8	0	0	79,4
            78,47	0	0	 78.60
            78,13	0	0	 77.87
            79	1	0	 79.13
            79,6	0	0	 79.87
            78,73	5	0	79,6
            78,87	1	0	 79.00
            78.67	0	0	 78.60
            77.60	6	0	 77.53
            77,6	0	0	 77.73
            79,8	0	0	79,4
            77,87	0	0	 77.93
            78,53	1	0	 78.53
            77,4	1	0	 77.27
            79.20	0	0	 79.00
            79.13	0	0	 79.00
            78.27	2	0	 78.27
            77,07	0	0	 77.27
            78,07	0	0	 77.67
            79,8	0	0	79,6
            78.87	0	0	 79.13
            78.87	0	0	 78.20
            79,73	0	0	79,6
            78.13	1	0	 78.33
            78,73	0	0	 78.80
            77.93	1	0	 77.80
            78,33	0	0	 78.13
            78,87	0	0	78,67
            79,4	0	0	79
            79,8	1	0	79,93
            78.93	1	0	 79.07
            76,47	4	0	 76.93
            79	1	0	78,8
            79,53	0	0	79,2
            78,47	0	0	 78.53
            79,33	0	0	79,2
            76,73	4	0	 76.80
            76,87	1	0	 77.07
            79,53	0	0	79,67
            79,4	3	0	79,6
            79,07	0	0	 78.73
            79.80	2	0	 79.27
            77,2	1	0	 78.00
            79,33	0	0	79,2
            79,67	4	0	79,8
            78.60	1	0	 78.80
            78,4	0	0	 78.20
            78.00	1	0	 77.60
            79,47	0	0	79,6
            78.47	0	0	 78.67
            79,47	6	0	79,33
            79,27	3	0	79,13
            78.60	2	0	 78.87
            77,87	5	0	 78.80
            79,73	1	0	79,73
            79,27	0	0	 79.20
            79,6	0	0	79,53
            79,33	1	0	 79.33
            79,67	0	0	79,53
            77.20	2	0	 77.20
            79,93	0	0	79,8
            78,6	1	0	 78.60
            78.73	2	0	 79.27
            78.00	2	0	 77.93
            79.13	2	0	 78.93
            80.47	1	0	 79.93
            79,8	0	0	79,67
            78,87	2	0	 79.07
            79,13	1	0	 79.27
            79,67	0	0	79,27
            79.73	2	0	 79.47
            79,73	0	0	79,53
            77,13	0	0	 77.47
            77,73	1	0	 77.40
            79.33	1	0	 79.33
            78.40	0	0	 78.00
            79,2	1	0	78,93
            78.53	0	0	 78.13
            78,4	4	0	78,13
            80	1	0	79,67
            79.87	0	0	 79.40
            79,07	2	0	78,73
            77.87	0	0	 77.87
            78,6	0	0	79,53
            79.27	0	0	 78.87
            77,73	1	0	 78.07
            78.33	0	0	 78.47
            79.07	0	0	 78.67
            76,93	3	0	 76.80
            80	0	0	79,93
            78.47	0	0	 78.47
            78.87	0	0	 79.13
            76,73	3	0	 77.13
            79,6	0	0	79,27
            79,33	8	0	79,2
            80	0	0	79,93
            78,27	0	0	 78.33
            79,53	0	0	79,73
            77,8	2	0	78,13
            79,73	1	0	79,87
            79.47	0	0	 79.33
            79,33	4	0	79,4
            78,87	1	0	 79.00
            78.87	0	0	 78.80
            78.07	2	0	 78.00
            78,33	0	0	 78.33
            78,33	0	0	 78.40
            79,33	0	0	79,2
            79,2	0	0	79,07
            79,67	0	0	79,4
            79,53	0	0	79,33
            77,93	0	0	 77.87
            78,33	0	0	 78.20
            79.80	0	0	 79.53
            78.67	0	0	 78.47
            79,27	0	0	79,33
            79,6	2	0	79,33
            76,73	0	0	 76.87
            79.60	0	0	 79.40
            79,8	0	0	79,53
            77,07	3	0	 77.33
            76,87	0	0	 77.13
            78,93	0	0	78,8
            78.73	1	0	 78.80
            79.67	0	0	 79.13
            79,47	0	0	 79.60
            79,4	1	0	79,47
            79,53	5	0	 79.47
            78.40	0	0	 78.13
            76,87	0	0	72    
            79,73	0	0	79,87
            77.20	1	0	 78.07
            79.53	0	0	 79.27
            78,33	2	0	79,07
            78,93	0	0	 79.00
            79,53	0	0	79,53
            79.13	2	0	 79.20
            79,93	2	0	80,07
            79,93	0	0	80,07
            79	16	0	78,87
            79,27	3	0	 79.47
            79.47	0	0	 79.40
            79,2	0	0	 79.40
            79,33	1	0	79,33
            79,8	0	0	79,93
            79,33	0	0	 79.47
            78.53	3	0	 78.20
            79,47	1	0	79,4
            79,53	0	0	79,47
            78.07	1	0	 77.73
            79.87	0	0	 79.67
            79,4	0	0	 79.53
            78.73	2	0	 78.67
            78.47	1	0	 78.87
            78,4	2	0	79,2
            79.73	0	0	 79.47
            79.87	0	0	 79.60
            78,13	3	0	 77.80
            79,4	2	0	79,53
            78,27	0	0	 78.13
            78,33	0	0	 78.40
            78,53	0	0	 78.60
            79,8	2	0	 79.80
            79.00	0	0	 78.87
            80.00	4	0	 79.80
            79,07	0	0	79,07
            79,4	0	0	79,4
            78.00	0	0	 77.60
            79,47	0	0	79,33
            79,53	0	0	 79.47
            77.87	0	0	 77.80
            77,8	0	0	77,8
            79,27	0	0	79,4
            77,87	5	0	78,13
            78,2	0	0	 78.40
            79,27	0	0	 79.40
            79,4	0	0	79,53
            79,13	0	0	 79.20
            79.47	3	0	 79.27
            78,73	0	0	 78.87
            78,13	0	0	 78.27
            76,4	3	0	 76.60
            77,8	0	0	 77.53
            79,33	4	0	79,47
            79,13	0	0	 79.20
            78,93	0	0	 79.07
            78,87	3	0	78,67
            77,8	1	0	 77.73
            79,47	1	0	79,13
            78.20	1	0	 78.00
            78.27	1	0	 78.27
            78.00	1	0	 77.93
            79,13	1	0	78,93
            79.33	0	0	 79.40
            78.60	0	0	 77.13
            79.27	0	0	 79.33
            79,27	0	0	79,13
            79,27	0	0	79,13
            78.20	1	0	 77.73
            78,27	0	0	 78.53
            79.07	1	0	 79.33
            80	0	0	79,67
            77,87	0	0	 78.07
            79,73	1	0	79,47
            79,93	0	0	80
            79.47	0	0	 79.27
            79,47	0	0	79,33
            78,33	0	0	 78.07
            78.27	0	0	 78.53
            79,67	0	0	79,47
            79,8	0	0	79,53
            79.67	0	0	 79.60
            79,4	2	0	79,53
            80,13	0	0	80,07
            80.93	0	0	 80.53
            78.07	0	0	 78.33
            77,87	2	0	 78.20
            79.47	0	0	 79.20
            79.53	1	0	 79.47
            79,33	0	0	79,27
            79,93	0	0	79,67
            79.27	0	0	 79.67
            78,87	2	0	78,8
            79,4	0	0	79,33
            79.67	2	0	 79.53
            78,13	0	0	 77.67
            78.47	18	0	 78.20
            78,33	1	0	 78.33
            79.80	4	0	 79.40
            79,67	0	0	 79.53
            79,53	1	0	79,67
            77	1	0	 77.33
            78,13	1	0	 77.93
            79,2	0	0	79
            79,07	0	0	 79.13
            79,53	0	0	79,4
            79,87	0	0	80
            79,27	2	0	79,2
            79.13	0	0	 79.53
            79,33	1	0	79,47
            78	0	0	 77.80
            78.07	0	0	 78.00
            79,87	0	0	79,67
            79,27	3	0	79,13
            77.40	1	0	 77.47
            79,4	0	0	79,53
            79,87	0	0	79,93
            79,6	0	0	79,47
            79,73	0	0	79,6
            77.87	5	0	 77.60
            78,47	1	0	78,47
            77,47	0	0	 77.73
            79,27	2	0	79,13
            78.87	1	0	 78.20
            79,6	0	0	79,47
            79.40	2	0	 79.20
            79,73	0	0	79,87
            77,47	1	0	 77.47
            80,47	0	0	80,47
            79.87	4	0	 79.87
            79.07	1	0	 78.27
            79,33	4	0	 79.47
            79	1	0	 79.20
            79,67	0	0	79,47
            79,47	0	0	79,33
            79,87	4	0	80
            79,6	0	0	79,47
            0	0	0	0
            80	0	0	79,87
            78,4	0	0	78,67
            78,33	2	0	79,2
            77,87	3	0	78,8
            78,73	6	0	78,8
            79	0	0	78,93
            77,87	5	0	77,73
        ";

        // --- Parsing Data Semester 1 ---
        $lines = explode("\n", trim($dataSemester1Text));
        $nilaiSemester1 = [];
        foreach ($lines as $index => $line) {
            if ($index >= $jumlahSiswa) break; // Hindari error jika data teks > jumlah siswa

            $parts = preg_split('/\s+/', trim($line)); // Split by one or more spaces
            if (count($parts) === 4) {
                $nisn = $nisnSiswa[$index];
                // Konversi koma ke titik dan pastikan float
                $nilaiRaport = (float) str_replace(',', '.', $parts[0]);
                $presensi = (int) $parts[1]; // Presensi biasanya integer
                $prestasi = (int) $parts[2]; // Prestasi biasanya integer/poin
                $ekskul = (float) str_replace(',', '.', $parts[3]);

                $nilaiSemester1[$nisn] = [
                    $kriteriaMapping['Nilai Raport'] => $nilaiRaport,
                    $kriteriaMapping['Presensi'] => $presensi,
                    $kriteriaMapping['Prestasi'] => $prestasi,
                    $kriteriaMapping['Ekstrakulikuler'] => $ekskul,
                ];
            } else {
                // Log warning jika format baris tidak sesuai
                \Log::warning("PenilaianSeeder: Format data semester 1 tidak sesuai pada baris " . ($index + 1) . ": '" . $line . "'");
                 // Beri nilai default atau lewati siswa ini
                $nisn = $nisnSiswa[$index];
                 $nilaiSemester1[$nisn] = [
                    $kriteriaMapping['Nilai Raport'] => 75.0, // Default value
                    $kriteriaMapping['Presensi'] => 5,      // Default value
                    $kriteriaMapping['Prestasi'] => 0,      // Default value
                    $kriteriaMapping['Ekstrakulikuler'] => 75.0, // Default value
                ];
            }
        }

        // --- Generate Data untuk Semua Semester & Kriteria ---
        $dataToInsert = [];
        foreach ($nisnSiswa as $nisn) {
            for ($semester = 1; $semester <= 5; $semester++) {
                foreach ($kriteriaIds as $kriteriaId) {
                    $nilai = null;

                    if ($semester == 1) {
                        // Ambil data semester 1 yang sudah diparsing
                        $nilai = $nilaiSemester1[$nisn][$kriteriaId] ?? null; // Ambil nilai atau null jika parsing gagal
                        if($nilai === null) {
                             // Jika parsing gagal total untuk siswa ini, set default
                             switch ($kriteriaId) {
                                case $kriteriaMapping['Nilai Raport']: $nilai = 75.0; break;
                                case $kriteriaMapping['Presensi']: $nilai = 5; break;
                                case $kriteriaMapping['Prestasi']: $nilai = 0; break;
                                case $kriteriaMapping['Ekstrakulikuler']: $nilai = 75.0; break;
                                default: $nilai = 0;
                            }
                            \Log::warning("PenilaianSeeder: Menggunakan nilai default untuk NISN {$nisn}, Kriteria {$kriteriaId}, Semester 1 karena parsing awal gagal.");
                        }

                    } else {
                        // Generate data acak untuk semester 2-5 berdasarkan nilai semester 1
                        $baseNilai = $nilaiSemester1[$nisn][$kriteriaId] ?? null; // Ambil basis nilai smt 1
                         if($baseNilai === null) {
                             // Jika basis nilai tidak ada (parsing smt 1 gagal), generate nilai default acak
                             switch ($kriteriaId) {
                                case $kriteriaMapping['Nilai Raport']: $baseNilai = rand(70, 80); break;
                                case $kriteriaMapping['Presensi']: $baseNilai = rand(0, 5); break;
                                case $kriteriaMapping['Prestasi']: $baseNilai = (rand(1, 10) == 1) ? rand(1, 3) : 0; break; // 10% chance
                                case $kriteriaMapping['Ekstrakulikuler']: $baseNilai = rand(70, 80); break;
                                default: $baseNilai = 0;
                            }
                         }

                        // Terapkan variasi acak
                        switch ($kriteriaId) {
                            case $kriteriaMapping['Nilai Raport']:
                            case $kriteriaMapping['Ekstrakulikuler']:
                                // Variasi float +/- 1.5, batas min 65, max 95
                                $variation = (rand(-150, 150)) / 100.0;
                                $nilai = round(max(65.0, min(95.0, $baseNilai + $variation)), 2);
                                break;
                            case $kriteriaMapping['Presensi']:
                                // Variasi integer +0, +1, atau +2 (lebih sering 0 atau 1)
                                $variation = [0, 0, 0, 1, 1, 2][rand(0, 5)];
                                $nilai = max(0, $baseNilai + $variation - rand(0,1)); // Ada kemungkinan berkurang sedikit juga
                                $nilai = min(20, $nilai); // Batas max presensi
                                break;
                            case $kriteriaMapping['Prestasi']:
                                // Peluang kecil untuk mendapat prestasi di semester lain
                                // Jika di smt 1 sudah 0, ada 5% kemungkinan dapat nilai 1-5 di smt lain
                                // Jika di smt 1 > 0, ada 30% kemungkinan dapat nilai lagi
                                $chance = ($baseNilai > 0) ? 3 : 1; // 3/10 vs 1/20
                                $nilai = (rand(1, 20) <= $chance) ? rand(1, 5) : 0;
                                break;
                            default:
                                $nilai = $baseNilai; // Jika ada kriteria lain
                        }
                    }

                    // Hanya tambahkan jika nilai tidak null
                    if ($nilai !== null) {
                        $dataToInsert[] = [
                            'siswa_nisn' => $nisn,
                            'kriteria_id' => $kriteriaId,
                            'semester' => $semester,
                            'nilai' => $nilai,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                } // End foreach kriteria
            } // End foreach semester
        } // End foreach siswa

        // --- Insert Data ke Database ---
        // Hapus data penilaian lama sebelum insert (opsional, hati-hati!)
        // DB::table('penilaians')->delete();

        // Insert semua data sekaligus (lebih efisien)
        // Chunk data jika terlalu besar (misal per 500 record)
        foreach (array_chunk($dataToInsert, 500) as $chunk) {
            DB::table('penilaians')->insert($chunk);
        }

        // Beri output ke console
        $this->command->info('PenilaianSeeder finished seeding ' . count($dataToInsert) . ' records.');
    }
}
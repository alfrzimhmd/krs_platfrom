<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 4 akun dosen (hanya jika belum ada)
        $dosenData = [
            [
                'name' => 'Dr. Ahmad Santoso, M.Kom',
                'email' => 'ahmad.santoso@example.com',
                'password' => 'password',  
                'nidn' => '0012345678',
                'nama_dosen' => 'Dr. Ahmad Santoso, M.Kom'
            ],
            [
                'name' => 'Prof. Dewi Kartika, Ph.D',
                'email' => 'dewi.kartika@example.com',
                'password' => 'password',
                'nidn' => '0012345679',
                'nama_dosen' => 'Prof. Dewi Kartika, Ph.D'
            ],
            [
                'name' => 'Dr. Budi Prasetyo, M.T',
                'email' => 'budi.prasetyo@example.com',
                'password' => 'password',
                'nidn' => '0012345680',
                'nama_dosen' => 'Dr. Budi Prasetyo, M.T'
            ],
            [
                'name' => 'Dr. Siti Nurhaliza, M.Pd',
                'email' => 'siti.nurhaliza@example.com',
                'password' => 'password',
                'nidn' => '0012345681',
                'nama_dosen' => 'Dr. Siti Nurhaliza, M.Pd'
            ],
        ];

        foreach ($dosenData as $data) {
            // Cek apakah user sudah ada berdasarkan email
            $user = User::where('email', $data['email'])->first();
            
            if (!$user) {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role' => 'dosen',
                ]);
                echo "✅ User dosen {$data['name']} berhasil dibuat\n";
            } else {
                echo "⚠️ User dosen {$data['email']} sudah ada, dilewati\n";
            }
            
            // Cek apakah dosen sudah ada berdasarkan nidn
            $dosen = Dosen::where('nidn', $data['nidn'])->first();
            if (!$dosen) {
                Dosen::create([
                    'user_id' => $user->id,
                    'nidn' => $data['nidn'],
                    'nama_dosen' => $data['nama_dosen'],
                ]);
                echo "✅ Data dosen {$data['nama_dosen']} berhasil dibuat\n";
            } else {
                echo "⚠️ Data dosen NIDN {$data['nidn']} sudah ada, dilewati\n";
            }
        }

        echo "\n--- Data Mata Kuliah ---\n";

        // Data mata kuliah Semester Ganjil
        $matakuliahGanjil = [
            ['kode_mk' => 'MK101', 'nama_mk' => 'Pemrograman Web', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK102', 'nama_mk' => 'Basis Data', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK103', 'nama_mk' => 'Matematika Diskrit', 'sks' => 2, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK104', 'nama_mk' => 'Sistem Operasi', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK105', 'nama_mk' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK106', 'nama_mk' => 'Pemrograman Mobile', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK107', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 'Ganjil'],
        ];

        // Data mata kuliah Semester Genap
        $matakuliahGenap = [
            ['kode_mk' => 'MK201', 'nama_mk' => 'Pemrograman Lanjut', 'sks' => 3, 'semester' => 'Genap'],
            ['kode_mk' => 'MK202', 'nama_mk' => 'Kecerdasan Buatan', 'sks' => 3, 'semester' => 'Genap'],
            ['kode_mk' => 'MK203', 'nama_mk' => 'Data Mining', 'sks' => 3, 'semester' => 'Genap'],
            ['kode_mk' => 'MK204', 'nama_mk' => 'Keamanan Siber', 'sks' => 2, 'semester' => 'Genap'],
            ['kode_mk' => 'MK205', 'nama_mk' => 'Pemrosesan Citra Digital', 'sks' => 3, 'semester' => 'Genap'],
            ['kode_mk' => 'MK206', 'nama_mk' => 'Manajemen Proyek TI', 'sks' => 2, 'semester' => 'Genap'],
            ['kode_mk' => 'MK207', 'nama_mk' => 'Cloud Computing', 'sks' => 3, 'semester' => 'Genap'],
        ];

        // Insert mata kuliah ganjil
        foreach ($matakuliahGanjil as $mk) {
            $exists = Matakuliah::where('kode_mk', $mk['kode_mk'])->first();
            if (!$exists) {
                Matakuliah::create($mk);
                echo "✅ Mata kuliah {$mk['kode_mk']} - {$mk['nama_mk']} (Ganjil) berhasil dibuat\n";
            } else {
                echo "⚠️ Mata kuliah {$mk['kode_mk']} sudah ada, dilewati\n";
            }
        }

        // Insert mata kuliah genap
        foreach ($matakuliahGenap as $mk) {
            $exists = Matakuliah::where('kode_mk', $mk['kode_mk'])->first();
            if (!$exists) {
                Matakuliah::create($mk);
                echo "✅ Mata kuliah {$mk['kode_mk']} - {$mk['nama_mk']} (Genap) berhasil dibuat\n";
            } else {
                echo "⚠️ Mata kuliah {$mk['kode_mk']} sudah ada, dilewati\n";
            }
        }

        echo "\n--- Seeder Selesai ---\n";
    }
}
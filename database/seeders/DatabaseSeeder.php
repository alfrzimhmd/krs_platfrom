<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n========== SEEDER MULAI ==========\n\n";

        // ========== SEEDER DOSEN ==========
        echo "--- SEEDER DOSEN ---\n";
        
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

        // ========== SEEDER MAHASISWA ==========
        echo "\n--- SEEDER MAHASISWA ---\n";

        $mahasiswaData = [
            [
                'nama' => 'Ahmad Fauzi',
                'nim' => '202401001',
                'email' => 'ahmad.fauzi@student.ac.id',
                'password' => 'password',
                'semester_saat_ini' => 'Ganjil',
                'dosen_id' => 1,
            ],
            [
                'nama' => 'Budi Santoso',
                'nim' => '202401002',
                'email' => 'budi.santoso@student.ac.id',
                'password' => 'password',
                'semester_saat_ini' => 'Ganjil',
                'dosen_id' => 1,
            ],
            [
                'nama' => 'Citra Dewi',
                'nim' => '202401003',
                'email' => 'citra.dewi@student.ac.id',
                'password' => 'password',
                'semester_saat_ini' => 'Genap',
                'dosen_id' => 2,
            ],
            [
                'nama' => 'Dian Permata',
                'nim' => '202401004',
                'email' => 'dian.permata@student.ac.id',
                'password' => 'password',
                'semester_saat_ini' => 'Genap',
                'dosen_id' => 2,
            ],
            [
                'nama' => 'Eka Prasetya',
                'nim' => '202401005',
                'email' => 'eka.prasetya@student.ac.id',
                'password' => 'password',
                'semester_saat_ini' => 'Ganjil',
                'dosen_id' => 3,
            ],
        ];

        foreach ($mahasiswaData as $data) {
            $mahasiswa = Mahasiswa::where('nim', $data['nim'])->first();
            
            if (!$mahasiswa) {
                Mahasiswa::create([
                    'nama' => $data['nama'],
                    'nim' => $data['nim'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'semester_saat_ini' => $data['semester_saat_ini'],
                    'dosen_id' => $data['dosen_id'],
                ]);
                echo "✅ Mahasiswa {$data['nama']} (NIM: {$data['nim']}) berhasil dibuat\n";
            } else {
                echo "⚠️ Mahasiswa NIM {$data['nim']} sudah ada, dilewati\n";
            }
        }

        // ========== SEEDER MATA KULIAH ==========
        echo "\n--- SEEDER MATA KULIAH ---\n";

        $matakuliahGanjil = [
            ['kode_mk' => 'MK101', 'nama_mk' => 'Pemrograman Web', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK102', 'nama_mk' => 'Basis Data', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK103', 'nama_mk' => 'Matematika Diskrit', 'sks' => 2, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK104', 'nama_mk' => 'Sistem Operasi', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK105', 'nama_mk' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK106', 'nama_mk' => 'Pemrograman Mobile', 'sks' => 3, 'semester' => 'Ganjil'],
            ['kode_mk' => 'MK107', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester' => 'Ganjil'],
        ];

        $matakuliahGenap = [
            ['kode_mk' => 'MK201', 'nama_mk' => 'Pemrograman Lanjut', 'sks' => 3, 'semester' => 'Genap'],
            ['kode_mk' => 'MK202', 'nama_mk' => 'Kecerdasan Buatan', 'sks' => 3, 'semester' => 'Genap'],
            ['kode_mk' => 'MK203', 'nama_mk' => 'Data Mining', 'sks' => 3, 'semester' => 'Genap'],
            ['kode_mk' => 'MK204', 'nama_mk' => 'Keamanan Siber', 'sks' => 2, 'semester' => 'Genap'],
            ['kode_mk' => 'MK205', 'nama_mk' => 'Pemrosesan Citra Digital', 'sks' => 3, 'semester' => 'Genap'],
            ['kode_mk' => 'MK206', 'nama_mk' => 'Manajemen Proyek TI', 'sks' => 2, 'semester' => 'Genap'],
            ['kode_mk' => 'MK207', 'nama_mk' => 'Cloud Computing', 'sks' => 3, 'semester' => 'Genap'],
        ];

        foreach ($matakuliahGanjil as $mk) {
            $exists = Matakuliah::where('kode_mk', $mk['kode_mk'])->first();
            if (!$exists) {
                Matakuliah::create($mk);
                echo "✅ Mata kuliah {$mk['kode_mk']} - {$mk['nama_mk']} (Ganjil) berhasil dibuat\n";
            } else {
                echo "⚠️ Mata kuliah {$mk['kode_mk']} sudah ada, dilewati\n";
            }
        }

        foreach ($matakuliahGenap as $mk) {
            $exists = Matakuliah::where('kode_mk', $mk['kode_mk'])->first();
            if (!$exists) {
                Matakuliah::create($mk);
                echo "✅ Mata kuliah {$mk['kode_mk']} - {$mk['nama_mk']} (Genap) berhasil dibuat\n";
            } else {
                echo "⚠️ Mata kuliah {$mk['kode_mk']} sudah ada, dilewati\n";
            }
        }

        echo "\n========== SEEDER SELESAI ==========\n";
    }
}
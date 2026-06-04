# 📚 SISKRS+ - Sistem Kartu Rencana Studi

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.4-blue?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?logo=mysql)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?logo=tailwindcss)
![Status](https://img.shields.io/badge/Status-Production%20Ready-green)

> Platform digital untuk pengajuan Kartu Rencana Studi (KRS) secara online yang memudahkan mahasiswa dan dosen dalam proses pengelolaan rencana studi.

---

## 📋 Daftar Isi

* [Tentang Project](#-tentang-project)
* [Role Pengguna](#-role-pengguna)
* [Fitur Unggulan](#-fitur-unggulan)
* [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
* [Struktur Database](#-struktur-database)
* [Instalasi](#-instalasi)
* [Konfigurasi](#-konfigurasi)
* [Menjalankan Aplikasi](#-menjalankan-aplikasi)
* [Akun Default](#-akun-default)
* [Mata Kuliah Seeder](#-mata-kuliah-seeder)
* [Struktur Folder](#-struktur-folder)
* [Tim Pengembang](#-tim-pengembang)
* [Lisensi](#-lisensi)

---

# 🎯 Tentang Project

**SISKRS+** merupakan aplikasi berbasis web yang digunakan untuk mengelola proses pengajuan **Kartu Rencana Studi (KRS)** secara digital.

Melalui sistem ini mahasiswa dapat:

* Mengajukan KRS secara online
* Memilih dosen pembimbing akademik
* Memilih mata kuliah berdasarkan semester
* Mengunggah bukti pembayaran UKT
* Memantau status persetujuan KRS

Sedangkan dosen dapat:

* Mengelola data mahasiswa
* Mengelola data mata kuliah
* Menyetujui atau menolak pengajuan KRS
* Memantau seluruh pengajuan mahasiswa bimbingan

---

# 👥 Role Pengguna

| Role              | Login                 | Deskripsi                       |
| ----------------- | --------------------- | ------------------------------- |
| **Mahasiswa**     | Nama + NIM + Semester | Mengisi dan mengajukan KRS      |
| **Dosen (Admin)** | Email + Password      | Mengelola KRS dan data akademik |

---

# ✨ Fitur Unggulan

## 🎓 Fitur Mahasiswa

| Fitur                    | Keterangan                              |
| ------------------------ | --------------------------------------- |
| Login Mahasiswa          | Menggunakan Nama, NIM, dan Semester     |
| Pilih Dosen PA           | Memilih dosen pembimbing akademik       |
| Pilih Mata Kuliah        | Mata kuliah tampil berdasarkan semester |
| Perhitungan SKS Otomatis | Total SKS dihitung otomatis             |
| Upload Bukti UKT         | Mendukung JPG, PNG, dan PDF             |
| Preview Thumbnail        | Menampilkan preview sebelum upload      |
| Status Pengajuan         | Menunggu, Disetujui, atau Ditolak       |
| Edit Pengajuan           | Selama status masih menunggu            |
| Dashboard Minimalis      | Fokus pada pengisian KRS                |

---

## 👨‍🏫 Fitur Dosen

| Fitur               | Keterangan                    |
| ------------------- | ----------------------------- |
| Login & Register    | Laravel Breeze Authentication |
| Registrasi NIDN     | Form register dilengkapi NIDN |
| Dashboard Statistik | Monitoring data akademik      |
| Kelola Mahasiswa    | Edit dan hapus mahasiswa      |
| Kelola KRS          | Approve atau reject pengajuan |
| Preview Bukti UKT   | Modal preview gambar          |
| CRUD Mata Kuliah    | Tambah, edit, dan hapus data  |
| Sidebar Modern      | UI modern dengan Tailwind CSS |

---

## 🎨 Fitur Umum

* Validasi Form
* Flash Message
* Modal Konfirmasi Hapus
* Responsive Design
* Dark Sidebar Layout
* Upload File Validation
* Dashboard Interaktif

---

# 🛠 Teknologi yang Digunakan

| Teknologi      | Versi  |
| -------------- | ------ |
| Laravel        | 12.x   |
| PHP            | 8.4+   |
| MySQL          | 8.0+   |
| Tailwind CSS   | 3.x    |
| Laravel Breeze | Latest |
| Font Awesome   | 6.x    |
| Vite           | Latest |

---

# 📊 Struktur Database

## Tabel Utama

| Tabel          | Deskripsi                                      |
| -------------- | ---------------------------------------------- |
| users          | Data akun dosen                                |
| dosens         | Data dosen                                     |
| mahasiswas     | Data mahasiswa                                 |
| matakuliahs    | Data mata kuliah                               |
| krs            | Data pengajuan KRS                             |
| krs_matakuliah | Relasi many-to-many antara KRS dan mata kuliah |

---

## Relasi Database

```text
users
 └── dosens
      └── mahasiswas
           └── krs
                └── krs_matakuliah
                     └── matakuliahs
```

---

## Detail Tabel

### users

| Field    |
| -------- |
| id       |
| name     |
| email    |
| password |
| role     |

### dosens

| Field      |
| ---------- |
| id         |
| user_id    |
| nidn       |
| nama_dosen |

### mahasiswas

| Field             |
| ----------------- |
| id                |
| nama              |
| nim               |
| semester_saat_ini |
| dosen_id          |

### matakuliahs

| Field    |
| -------- |
| id       |
| kode_mk  |
| nama_mk  |
| sks      |
| semester |

### krs

| Field          |
| -------------- |
| id             |
| mahasiswa_id   |
| semester       |
| total_sks      |
| status         |
| bukti_ukt_path |

### krs_matakuliah

| Field         |
| ------------- |
| id            |
| krs_id        |
| matakuliah_id |

---

# 💻 Instalasi

## Prasyarat

Pastikan perangkat telah terinstall:

* PHP 8.4+
* Composer
* MySQL 8.0+
* Node.js
* NPM

---

## Langkah Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/krs-platform.git
cd krs-platform
```

### 2. Install Dependency PHP

```bash
composer install
```

### 3. Install Dependency Frontend

```bash
npm install
```

### 4. Copy Environment

```bash
cp .env.example .env
```

### 5. Generate Key

```bash
php artisan key:generate
```

### 6. Konfigurasi Database

Edit file `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=krs_db
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Jalankan Migration & Seeder

```bash
php artisan migrate:fresh --seed
```

### 8. Storage Link

```bash
php artisan storage:link
```

### 9. Build Asset

```bash
npm run build
```

### 10. Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi melalui:

```text
http://localhost:8000
```

---

# ⚙️ Konfigurasi

## Contoh `.env`

```env
APP_NAME=SISKRS+
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=krs_db
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
```

---

# 🚀 Menjalankan Aplikasi

## Development

```bash
php artisan serve
```

## Production

```bash
php artisan optimize
php artisan serve --port=8000
```

## Clear Cache

```bash
php artisan optimize:clear
```

Atau:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

# 🔐 Akun Default

## Akun Dosen Seeder

| Nama                     | Email                                                           | Password |
| ------------------------ | --------------------------------------------------------------- | -------- |
| Dr. Ahmad Santoso, M.Kom | [ahmad.santoso@example.com](mailto:ahmad.santoso@example.com)   | password |
| Prof. Dewi Kartika, Ph.D | [dewi.kartika@example.com](mailto:dewi.kartika@example.com)     | password |
| Dr. Budi Prasetyo, M.T   | [budi.prasetyo@example.com](mailto:budi.prasetyo@example.com)   | password |
| Dr. Siti Nurhaliza, M.Pd | [siti.nurhaliza@example.com](mailto:siti.nurhaliza@example.com) | password |

---

## Akun Mahasiswa

Mahasiswa tidak perlu melakukan registrasi.

Cukup mengisi:

* Nama Lengkap
* NIM
* Semester (Ganjil / Genap)

Data mahasiswa akan dibuat secara otomatis saat login pertama kali.

---

# 📚 Mata Kuliah Seeder

## Semester Ganjil

| Kode  | Mata Kuliah              | SKS |
| ----- | ------------------------ | --- |
| MK101 | Pemrograman Web          | 3   |
| MK102 | Basis Data               | 3   |
| MK103 | Matematika Diskrit       | 2   |
| MK104 | Sistem Operasi           | 3   |
| MK105 | Jaringan Komputer        | 3   |
| MK106 | Pemrograman Mobile       | 3   |
| MK107 | Rekayasa Perangkat Lunak | 3   |

---

## Semester Genap

| Kode  | Mata Kuliah              | SKS |
| ----- | ------------------------ | --- |
| MK201 | Pemrograman Lanjut       | 3   |
| MK202 | Kecerdasan Buatan        | 3   |
| MK203 | Data Mining              | 3   |
| MK204 | Keamanan Siber           | 2   |
| MK205 | Pemrosesan Citra Digital | 3   |
| MK206 | Manajemen Proyek TI      | 2   |
| MK207 | Cloud Computing          | 3   |

---

# 📁 Struktur Folder

```text
krs_platform/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── RegisteredUserController.php
│   │   │   ├── DosenController.php
│   │   │   ├── KrsController.php
│   │   │   ├── MahasiswaAuthController.php
│   │   │   └── MatakuliahController.php
│   │   ├── Middleware/
│   │   │   └── MahasiswaAuth.php
│   │   └── Routes/
│   │       ├── web.php
│   │       └── auth.php
│   └── Models/
│       ├── User.php
│       ├── Dosen.php
│       ├── Mahasiswa.php
│       ├── Matakuliah.php
│       └── Krs.php
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── mahasiswa/
│       │   ├── login.blade.php
│       │   └── dashboard.blade.php
│       ├── dosen/
│       │   ├── dashboard.blade.php
│       │   ├── mahasiswa-index.blade.php
│       │   └── mahasiswa-edit.blade.php
│       ├── matakuliah/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── edit.blade.php
│       └── welcome.blade.php
├── public/
│   └── storage/ (symlink ke storage/app/public)
├── .env
├── .env.example
├── .gitignore
├── composer.json
├── package.json
└── README.md
```

---

# 🤝 Tim Pengembang

**Kelompok 2 – Sistem Pengajuan KRS**

| No | Nama                 |
| -- | -------------------- |
| 1  | Ariyana Dian Saputri |
| 2  | Muhammad Alfarizi    |
| 3  | Ramzy Alfatoni       |
| 4  | Muhtaji Ramadani     |

**Mata Kuliah:** Praktikum Pemrograman Web

---

# 📝 Lisensi

Project ini dibuat untuk kebutuhan akademik sebagai tugas proyek mata kuliah **Praktikum Pemrograman Web**.

---

# 🙏 Ucapan Terima Kasih

* Tim Dosen Praktikum Pemrograman Web
* Laravel Community
* Tailwind CSS Team
* Font Awesome Team

---

<div align="center">

### ❤️ Built with Laravel & Tailwind CSS

© 2025 SISKRS+ — All Rights Reserved

</div>

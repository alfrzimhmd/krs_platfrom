<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kartu Rencana Studi (KRS)</title>
    <style>
        * {
            font-family: 'Times New Roman', Times, serif;
        }
        body {
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 2px solid #0D9488;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #0D9488;
            margin: 0;
            font-size: 22px;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
        }
        .info-mahasiswa {
            margin-bottom: 12px;
            border: 1px solid #ddd;
            padding: 8px 12px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .info-mahasiswa table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-mahasiswa td {
            padding: 4px 2px;
            vertical-align: middle;
            line-height: 1.2;
        }

        .info-label {
            font-weight: bold;
            width: 110px;
            color: #000;
        }

        .info-value {
            color: #333;
        }

        .separator {
            width: 20px;
        }
        .title-section {
            background-color: #0D9488;
            color: white;
            padding: 8px;
            margin: 15px 0 10px 0;
            font-weight: bold;
            border-radius: 5px;
        }
        table.matkul {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.matkul th, table.matkul td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: center;
        }
        table.matkul th {
            background-color: #0D9488;
            color: white;
            font-weight: bold;
        }
        table.matkul td.text-left {
            text-align: left;
        }
        .total-sks {
            margin-top: 15px;
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            padding: 10px;
            background-color: #f0fdfa;
            border-radius: 5px;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .ttd-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        .ttd-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .ttd-space {
            height: 40px; 
        }

        .ttd-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto;
        }

        .ttd-label {
            margin-top: 5px;
            margin-bottom: 2px;
            font-size: 11px;
        }

        .no-break {
            page-break-inside: avoid;
        }

        .footer {
            page-break-inside: avoid;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .watermark {
            position: fixed;
            bottom: 50%;
            right: 0;
            left: 0;
            text-align: center;
            opacity: 0.05;
            font-size: 60px;
            transform: rotate(-45deg);
            pointer-events: none;
            z-index: 999;
        }
        .total-info {
            width: 100%;
            margin-top: 12px;
            padding: 8px 10px;
            background-color: #f0fdfa;
            border: 1px solid #d1fae5;
            border-radius: 5px;
            border-collapse: collapse;
            font-size: 13px;
            font-weight: bold;
        }

        .total-info td {
            padding: 0;
            border: none;
        }

        .status-krs {
            text-align: left;
            color: #059669;
        }

        .total-sks-value {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="watermark">SISKRS+</div>

    <div class="header">
        <h1>KARTU RENCANA STUDI (KRS)</h1>
        <p>UNIVERSITAS ISLAM NEGERI SALATIGA</p>
        <p>Semester {{ $krs->semester }} - Tahun Akademik {{ date('Y') }}</p>
    </div>

    <!-- Informasi Mahasiswa dengan layout 2 kolom -->
    <div class="info-mahasiswa">
        <table>
            <tr>
                <td class="info-label">Nama Mahasiswa</td>
                <td class="info-value">: {{ $mahasiswa->nama }}</td>
                <td class="separator"></td>
                <td class="info-label">NIM</td>
                <td class="info-value">: {{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td class="info-label">Program Studi</td>
                <td class="info-value">: Teknik Informatika</td>
                <td class="separator"></td>
                <td class="info-label">Jenjang</td>
                <td class="info-value">: S1</td>
            </tr>
            <tr>
                <td class="info-label">Semester</td>
                <td class="info-value">: {{ $krs->semester }} / {{ $mahasiswa->nomor_semester ?? '-' }}</td>
                <td class="separator"></td>
                <td class="info-label">Dosen Pembimbing PA</td>
                <td class="info-value">: {{ $mahasiswa->dosen->nama_dosen ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Daftar Mata Kuliah -->
    <div class="title-section">
        DAFTAR MATA KULIAH YANG DIAMBIL
    </div>

    <table class="matkul">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Kode MK</th>
                <th style="width: 55%;">Nama Mata Kuliah</th>
                <th style="width: 10%;">SKS</th>
                <th style="width: 15%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($krs->matakuliahs as $mk)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $mk->kode_mk }}</td>
                <td class="text-left">{{ $mk->nama_mk }}</td>
                <td>{{ $mk->sks }}</td>
                <td>Reguler</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="total-info">
        <tr>
            <td class="status-krs">
                Status : <strong>DISETUJUI</strong>
            </td>

            <td class="total-sks-value">
                Total SKS :
                <strong style="font-size: 15px; color: #0D9488;">
                    {{ $krs->total_sks }}
                </strong> SKS
            </td>
        </tr>
    </table>

    <!-- Bagian bawah agar tidak terpisah halaman -->
    <div class="no-break">

        <!-- Tanda Tangan -->
        <table class="ttd-table">
            <tr>
                <td>
                    <p>Mahasiswa,</p>
                </td>
                <td>
                    <p>Dosen Pembimbing Akademik,</p>
                </td>
            </tr>

            <!-- Ruang kosong untuk tanda tangan -->
            <tr>
                <td class="ttd-space"></td>
                <td class="ttd-space"></td>
            </tr>

            <!-- Garis tanda tangan -->
            <tr>
                <td>
                    <div class="ttd-line"></div>
                </td>
                <td>
                    <div class="ttd-line"></div>
                </td>
            </tr>

            <!-- Nama -->
            <tr>
                <td>
                    <p class="ttd-label">
                        {{ $mahasiswa->nama }}
                    </p>
                </td>
                <td>
                    <p class="ttd-label">
                        {{ $mahasiswa->dosen->nama_dosen ?? '____________________' }}
                    </p>
                </td>
            </tr>

            <!-- NIM dan NIDN -->
            <tr>
                <td>
                    <p class="ttd-label" style="font-size: 10px; color: #666;">
                        NIM: {{ $mahasiswa->nim }}
                    </p>
                </td>
                <td>
                    @if($mahasiswa->dosen)
                    <p class="ttd-label" style="font-size: 10px; color: #666;">
                        NIDN: {{ $mahasiswa->dosen->nidn }}
                    </p>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Informasi Tambahan -->
        <div style="margin-top: 20px; font-size: 10px; color: #666; border-top: 1px dashed #ccc; padding-top: 10px;">
            <p><strong>Catatan:</strong></p>
            <ul style="margin: 5px 0 0 20px;">
                <li>KRS ini telah disetujui oleh Dosen Pembimbing Akademik.</li>
                <li>Perubahan KRS hanya dapat dilakukan sebelum mendapatkan persetujuan.</li>
                <li>Dokumen ini dicetak secara elektronik oleh sistem SISKRS+ dan tidak memerlukan tanda tangan basah.</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Dokumen ini adalah bukti sah pengambilan KRS. |
                Dicetak pada: {{ $tanggal_cetak }} |
                Sistem Informasi KRS SISKRS+
            </p>
        </div>
</div>
</body>
</html>
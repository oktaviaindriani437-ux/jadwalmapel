<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ ($mode ?? 'subject') === 'admin' ? 'Jadwal Pelajaran Sekolah - Keseluruhan KBM (Admin)' : 'Jadwal Pelajaran Sekolah - Siswa & Guru' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.2cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #0f172a;
            background: #ffffff;
            margin: 0;
            padding: 20px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .no-print {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .print-btn {
            background: #0284c7;
            color: #ffffff;
            border: none;
            padding: 9px 18px;
            border-radius: 6px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
        }
        .print-btn:hover {
            background: #0369a1;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }

        /* Mode 1: Admin Official Kop Surat */
        .admin-kop {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px double #0f172a;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .admin-kop-text {
            text-align: center;
            flex: 1;
        }
        .admin-kop-text h4 {
            margin: 0;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #334155;
        }
        .admin-kop-text h2 {
            margin: 3px 0;
            font-size: 19px;
            font-weight: 800;
            color: #0f294a;
            letter-spacing: 0.5px;
        }
        .admin-kop-text p {
            margin: 0;
            font-size: 11px;
            color: #64748b;
        }
        .admin-meta-banner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 11.5px;
            margin-bottom: 16px;
            color: #475569;
        }

        /* Mode 2: Subject / Student Timetable Header */
        .subject-header {
            text-align: center;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 14px;
            margin-bottom: 18px;
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%);
            padding-top: 14px;
            border-radius: 8px 8px 0 0;
        }
        .subject-badge-top {
            display: inline-block;
            background: #0284c7;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .subject-header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            color: #0f294a;
            letter-spacing: -0.3px;
        }
        .subject-header p {
            margin: 4px 0 0;
            font-size: 12.5px;
            color: #475569;
            font-weight: 600;
        }
        .subject-info-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 12px;
            color: #0369a1;
            font-weight: 700;
            margin-bottom: 16px;
        }

        /* Table Styling */
        table.sheet-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table.sheet-table th, table.sheet-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 10px;
            text-align: left;
        }
        table.sheet-table th {
            background-color: #0f294a;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.3px;
        }
        table.sheet-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        table.sheet-table tbody tr:hover {
            background-color: #f1f5f9;
        }
        .badge-day {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-day-monday { background: #dbeafe; color: #1d4ed8; }
        .badge-day-tuesday { background: #d1fae5; color: #047857; }
        .badge-day-wednesday { background: #cffafe; color: #0e7490; }
        .badge-day-thursday { background: #fef3c7; color: #b45309; }
        .badge-day-friday { background: #ede9fe; color: #6d28d9; }

        /* Signatures (Khusus Mode Admin) */
        .footer-signatures {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .sign-box {
            text-align: center;
            width: 220px;
            font-size: 12px;
        }
        .sign-space {
            height: 60px;
        }
        .sign-name {
            font-weight: 800;
            text-decoration: underline;
        }
        .student-print-footer {
            margin-top: 18px;
            padding: 10px;
            border-top: 1px dashed #cbd5e1;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <div>
            <strong>{{ ($mode ?? 'subject') === 'admin' ? 'Pratinjau Cetak Jadwal Keseluruhan (Admin)' : 'Pratinjau Lembar Jadwal Pelajaran (Siswa / Guru)' }}</strong>
            <span style="display:block; font-size:12px; color:#64748b; margin-top:2px;">
                Gunakan pengaturan kertas <strong>Landscape (A4)</strong> pada dialog cetak peramban.
            </span>
        </div>
        <button type="button" class="print-btn" onclick="window.print()">
            Cetak Dokumen Ini
        </button>
    </div>

    @if (($mode ?? 'subject') === 'admin')
        <!-- TAMPILAN RESMI KHUSUS ADMIN KURIKULUM -->
        <div class="admin-kop">
            <div class="admin-kop-text">
                <h4>PEMERINTAH DAERAH PROVINSI JAWA BARAT &bull; DINAS PENDIDIKAN</h4>
                <h2>SMK NEGERI 1 (SKANIC)</h2>
                <p>Jl. Pendidikan No. 1 &bull; Website: https://skanic.sch.id &bull; Email: kurikulum@skanic.sch.id</p>
            </div>
        </div>
        <div class="admin-meta-banner">
            <span><strong>DOKUMEN INDUK:</strong> Jadwal Keseluruhan KBM Tahun Ajaran 2026/2027</span>
            <span>Dicetak Pada: <strong>{{ $generatedAt }} WIB</strong></span>
            <span>Otorisasi: <strong>Administrator Kurikulum</strong></span>
        </div>
    @else
        <!-- TAMPILAN LEMBAR JADWAL PELAJARAN (SISWA / MATA PELAJARAN / GURU) -->
        <div class="subject-header">
            <span class="subject-badge-top">SMK Negeri 1 &bull; Sistem Akademik Skanic</span>
            <h1>LEMBAR JADWAL MATA PELAJARAN SEKOLAH</h1>
            <p>Tahun Pelajaran 2026/2027 &bull; Semester Ganjil</p>
        </div>
        <div class="subject-info-bar">
            <span>Rombel / Kelas: <strong>{{ $className ?? 'Seluruh Kelas & Jurusan' }}</strong></span>
            <span>Total: <strong>{{ $schedules->count() }} Jam Pelajaran</strong></span>
            <span>Dicetak: {{ $generatedAt }}</span>
        </div>
    @endif

    <table class="sheet-table">
        <thead>
            <tr>
                <th style="width: 35px; text-align: center;">No</th>
                <th style="width: 80px;">Hari</th>
                <th style="width: 105px;">Waktu KBM</th>
                <th style="width: 130px;">Kelas & Jurusan</th>
                <th>Mata Pelajaran</th>
                <th>Guru Pengampu</th>
                <th style="width: 130px;">Ruangan / Lab</th>
            </tr>
        </thead>
        <tbody>
            @php
                $dayTranslations = [
                    'monday' => 'Senin',
                    'tuesday' => 'Selasa',
                    'wednesday' => 'Rabu',
                    'thursday' => 'Kamis',
                    'friday' => 'Jumat',
                    'saturday' => 'Sabtu',
                ];
            @endphp
            @forelse ($schedules as $index => $schedule)
                @php
                    $d = strtolower($schedule->day_of_week ?? $schedule->day);
                @endphp
                <tr>
                    <td style="text-align: center; color:#64748b;">{{ $index + 1 }}</td>
                    <td>
                        <span class="badge-day badge-day-{{ $d }}">
                            {{ $dayTranslations[$d] ?? ucfirst($d) }}
                        </span>
                    </td>
                    <td style="font-family: monospace; font-weight: bold; color:#0369a1;">
                        {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                    </td>
                    <td>
                        <strong>{{ $schedule->class?->name ?? '-' }}</strong>
                        @if ($schedule->class?->major)
                            <div style="font-size: 10.5px; color:#64748b;">{{ $schedule->class->major }}</div>
                        @endif
                    </td>
                    <td>
                        <strong style="color:#0f294a;">{{ $schedule->subject?->name ?? '-' }}</strong>
                        @if ($schedule->subject?->code)
                            <span style="font-size:10.5px; background:#f1f5f9; padding:1px 5px; border-radius:3px; color:#475569; border:1px solid #cbd5e1;">
                                {{ $schedule->subject->code }}
                            </span>
                        @endif
                    </td>
                    <td>{{ $schedule->teacher?->name ?? '-' }}</td>
                    <td>
                        <span style="font-weight: 600; color:#334155;">
                            {{ $schedule->roomModel?->name ?? $schedule->room ?? '-' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 24px; color:#64748b;">
                        Tidak ada data jadwal pelajaran untuk kriteria ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if (($mode ?? 'subject') === 'admin')
        <!-- Tanda Tangan Khusus Admin -->
        <div class="footer-signatures">
            <div class="sign-box">
                <p>Mengetahui,<br><strong>Kepala SMK Negeri 1</strong></p>
                <div class="sign-space"></div>
                <p class="sign-name">Drs. H. Administrator, M.Pd.</p>
                <p style="margin:2px 0 0; font-size:11px; color:#64748b;">NIP. 19750815 200003 1 002</p>
            </div>
            <div class="sign-box">
                <p>Koordinator Pengembang Kurikulum,<br><strong>SMK Negeri 1</strong></p>
                <div class="sign-space"></div>
                <p class="sign-name">Tim Kurikulum SMKN 1</p>
                <p style="margin:2px 0 0; font-size:11px; color:#64748b;">NIP. 19820412 200801 2 015</p>
            </div>
        </div>
    @else
        <!-- Catatan Ramah Siswa & Guru -->
        <div class="student-print-footer">
            <span>&bull; Simpan atau cetak lembar ini sebagai panduan belajar mingguan di SMKN 1.</span>
            <span>Portal Jadwal Skanic &bull; SMKN 1</span>
        </div>
    @endif
</body>
</html>

@extends('layouts.app')

@section('title', 'Dashboard | Jadwal Skanic')
@section('page-title', 'Dashboard Utama')

@section('content')
    <!-- Alert Success / Error setelah aksi edit jadwal -->
    @if ($message = Session::get('success'))
        <div class="alert-success-banner no-print">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ $message }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error-banner no-print">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <strong style="display:block; margin-bottom:4px;">Gagal Memproses Jadwal:</strong>
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Elegant Admin Header Banner (Rapi, Ringkas & Padat) -->
    <div class="no-print admin-welcome-banner">
        <div style="display:flex; align-items:center; justify-content:space-between; width:100%; flex-wrap:wrap; gap:12px;">
            <div>
                <div class="banner-badge">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Panel Manajemen Kurikulum SMKN 1
                </div>
                <h2 class="banner-title">
                    Jadwal Kegiatan Belajar Mengajar (KBM)
                </h2>
                <p class="banner-desc">
                    5 Program Keahlian: <strong>PPLG</strong>, <strong>BCF</strong>, <strong>TPFL</strong>, <strong>Teknik Otomotif</strong>, dan <strong>Animasi</strong> &bull; Seluruh penugasan ruang dan guru tervalidasi bebas konflik.
                </p>
            </div>
            <div class="banner-year-pill">
                T.A. 2026/2027 &bull; Semester Ganjil
            </div>
        </div>
    </div>

    <!-- Metric Cards Grid (4 Kolom Rapi, Proporsional & Dapat Diklik ke Menu Terkait) -->
    <div class="grid no-print">
        <a href="{{ route('admin.schedules') }}" class="metric-card" style="text-decoration:none; color:inherit;" title="Buka halaman Jadwal Pelajaran">
            <div class="metric-info">
                <span class="label">Total Jadwal</span>
                <div class="value">{{ $stats['schedules'] }}</div>
            </div>
            <div class="metric-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.teachers') }}" class="metric-card" style="text-decoration:none; color:inherit;" title="Buka halaman Data Guru">
            <div class="metric-info">
                <span class="label">Total Guru</span>
                <div class="value">{{ $stats['teachers'] }}</div>
            </div>
            <div class="metric-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.classes') }}" class="metric-card" style="text-decoration:none; color:inherit;" title="Buka halaman Data Kelas">
            <div class="metric-info">
                <span class="label">Total Kelas</span>
                <div class="value">{{ $stats['classes'] }}</div>
            </div>
            <div class="metric-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.subjects') }}" class="metric-card" style="text-decoration:none; color:inherit;" title="Buka halaman Mata Pelajaran">
            <div class="metric-info">
                <span class="label">Mata Pelajaran</span>
                <div class="value">{{ $stats['subjects'] }}</div>
            </div>
            <div class="metric-icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
        </a>
    </div>

    <!-- Card Jadwal Pelajaran Per Kelas (5 Jurusan Terpadu & Terstruktur) -->
    <div class="card schedule-main-card">
        <!-- Official Printable Letterhead (Hanya Tampil Saat Cetak / Print) -->
        <div class="print-only-header">
            <div class="kop-surat">
                <div class="kop-logo-box">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#072a4d" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div class="kop-teks">
                    <h3>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
                    <h4>DINAS PENDIDIKAN • CABANG DINAS WILAYAH</h4>
                    <h2>SMK NEGERI 1 (SKANIC)</h2>
                    <p>Jl. Pendidikan No. 1 • Website: https://skanic.sch.id • Email: info@skanic.sch.id</p>
                </div>
            </div>
            <div class="kop-garis"></div>
            <div class="print-doc-meta">
                <h3>JADWAL KESELURUHAN KEGIATAN BELAJAR MENGAJAR (KBM) PER KELAS</h3>
                <p id="printDocSubtitle">Tahun Pelajaran 2026/2027 • 5 Program Keahlian: PPLG, BCF, TPFL, Teknik Otomotif, dan Animasi</p>
                <div class="print-meta-badge">
                    <span>Dicetak Pada: <strong>{{ now()->translatedFormat('l, d F Y - H:i') }} WIB</strong></span>
                    <span>Operator: <strong>Administrator Kurikulum ({{ auth()->user()->name ?? 'Admin' }})</strong></span>
                </div>
            </div>
        </div>

        <div class="card-header no-print schedule-header-row">
            <div class="schedule-header-text">
                <h3 id="dashboardScheduleTitle" style="margin:0; font-size:16px; font-weight:800; color:#0f172a; display:flex; align-items:center; gap:8px;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#2563eb; flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Jadwal Pelajaran Per Kelas &bull; Jadwal Keseluruhan Pelajaran</span>
                </h3>
                <span id="dashboardScheduleSubtitle" style="font-size:12px; color:#64748b; font-weight:600; margin-top:3px; display:block;">
                    5 Jurusan Resmi: <strong>1. PPLG</strong> &bull; <strong>2. BCF</strong> &bull; <strong>3. TPFL</strong> &bull; <strong>4. Teknik Otomotif</strong> &bull; <strong>5. Animasi</strong> &bull; Menampilkan <strong id="adminVisibleCount" style="color:#2563eb;">{{ $allSchedules->count() }}</strong> jam KBM
                </span>
            </div>
            <div class="schedule-header-btn">
                <!-- Tombol Cetak Dokumen Resmi Admin (Satu Bagian Saja, Tidak Ada Duplikat) -->
                <button type="button" onclick="printScheduleReport()" class="btn btn-print-admin" title="Cetak jadwal ini ke printer atau simpan PDF resmi">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Jadwal (Admin)</span>
                </button>
            </div>
        </div>

        <!-- BILAH TAB 5 JURUSAN RESMI (Rata 6 Kolom Sempurna, Bebas Scrollbar & Bebas Teks Terpotong) -->
        <div class="majors-tabs-bar no-print">
            <button type="button" class="major-tab-btn active" data-major="PPLG" onclick="selectAdminMajor('PPLG')" title="Program Keahlian 1: PPLG">
                <span class="tab-num">1</span>
                <span class="tab-label">PPLG</span>
            </button>
            <button type="button" class="major-tab-btn" data-major="BCF" onclick="selectAdminMajor('BCF')" title="Program Keahlian 2: BCF">
                <span class="tab-num">2</span>
                <span class="tab-label">BCF</span>
            </button>
            <button type="button" class="major-tab-btn" data-major="TPFL" onclick="selectAdminMajor('TPFL')" title="Program Keahlian 3: TPFL">
                <span class="tab-num">3</span>
                <span class="tab-label">TPFL</span>
            </button>
            <button type="button" class="major-tab-btn" data-major="TO" onclick="selectAdminMajor('TO')" title="Program Keahlian 4: Teknik Otomotif">
                <span class="tab-num">4</span>
                <span class="tab-label">Teknik Otomotif</span>
            </button>
            <button type="button" class="major-tab-btn" data-major="Animasi" onclick="selectAdminMajor('Animasi')" title="Program Keahlian 5: Animasi">
                <span class="tab-num">5</span>
                <span class="tab-label">Animasi</span>
            </button>
            <button type="button" class="major-tab-btn" data-major="all" onclick="selectAdminMajor('all')" title="Tampilkan Semua Jurusan">
                <span class="tab-num">★</span>
                <span class="tab-label">Semua Jurusan</span>
            </button>
        </div>

        <!-- Filter Control Bar Terpadu (1 Baris Ringkas & Rapi) -->
        <div class="dashboard-filter-toolbar no-print">
            <div class="filter-unified-row">
                <!-- Day Pills dengan tombol Semua Hari -->
                <div class="day-pills-container" id="adminDashboardDayPills">
                    <button type="button" class="day-pill active" data-day="all" onclick="setAdminDayFilter('all')">
                        Semua Hari
                    </button>
                    <button type="button" class="day-pill" data-day="monday" onclick="setAdminDayFilter('monday')">
                        Senin
                    </button>
                    <button type="button" class="day-pill" data-day="tuesday" onclick="setAdminDayFilter('tuesday')">
                        Selasa
                    </button>
                    <button type="button" class="day-pill" data-day="wednesday" onclick="setAdminDayFilter('wednesday')">
                        Rabu
                    </button>
                    <button type="button" class="day-pill" data-day="thursday" onclick="setAdminDayFilter('thursday')">
                        Kamis
                    </button>
                    <button type="button" class="day-pill" data-day="friday" onclick="setAdminDayFilter('friday')">
                        Jumat
                    </button>
                </div>

                <!-- Pemilih Kelas Sesuai Jurusan & Pencarian (Rapi & Sejajar) -->
                <div class="filter-controls-right">
                    <div class="filter-class-dropdown-wrap">
                        <select id="adminClassSelect" class="custom-select-dashboard" onchange="selectAdminClass(this.value)">
                            <option value="all">Semua Kelas PPLG</option>
                            @foreach ($classesWithMajor as $cls)
                                <option
                                    value="{{ $cls->id }}"
                                    data-major="{{ $cls->major_key }}"
                                    style="{{ $cls->major_key === 'PPLG' ? '' : 'display:none;' }}"
                                >
                                    {{ $cls->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="search-box-wrap">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="text"
                            id="adminSearchInput"
                            placeholder="Cari mapel, guru, ruang..."
                            oninput="applyAdminScheduleFilters()"
                        >
                    </div>

                    <button type="button" class="btn-reset-filter" onclick="resetAdminFilters()" title="Reset semua filter">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- SUB-PILLS NAVIGASI KELAS CEPAT (Mencegah Halaman Panjang Kebawah & Menampilkan Kelas Secara Instan) -->
        <div class="class-quick-pills-bar no-print" id="adminClassQuickPillsBar">
            <div class="quick-pills-intro">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Pilih Tampilan Kelas:</span>
            </div>
            <div class="quick-pills-list" id="classQuickPillsList">
                <button type="button" class="quick-class-pill active" data-class-pill="all" onclick="selectQuickClassPill('all')">
                    ★ Tampilkan Semua Kelas (<span id="quickPillMajorLabel">PPLG</span>)
                </button>
                @foreach ($classesWithMajor as $cls)
                    <button
                        type="button"
                        class="quick-class-pill"
                        data-class-pill="{{ $cls->id }}"
                        data-major="{{ $cls->major_key }}"
                        onclick="selectQuickClassPill('{{ $cls->id }}')"
                        style="{{ $cls->major_key === 'PPLG' ? '' : 'display:none;' }}"
                    >
                        Kelas {{ $cls->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- LIST KARTU JADWAL PER KELAS (BERSIH, TERORGANISASI & TIDAK MENUMPUK) -->
        <div class="class-cards-container" id="adminClassCardsContainer">
            @php
                $dayConfigs = [
                    'monday' => 'Senin',
                    'tuesday' => 'Selasa',
                    'wednesday' => 'Rabu',
                    'thursday' => 'Kamis',
                    'friday' => 'Jumat',
                ];
                $todayDate = now()->format('Y-m-d');
                $todayDayKey = strtolower(now()->englishDayOfWeek);
                $majorDetails = [
                    'PPLG' => ['name' => 'Pengembangan Perangkat Lunak dan Gim', 'num' => 1, 'badge' => 'major-pplg'],
                    'BCF' => ['name' => 'Broadcasting dan Perfilman', 'num' => 2, 'badge' => 'major-bcf'],
                    'TPFL' => ['name' => 'Teknik Pengelasan dan Fabrikasi Logam', 'num' => 3, 'badge' => 'major-tpfl'],
                    'TO' => ['name' => 'Teknik Otomotif', 'num' => 4, 'badge' => 'major-to'],
                    'Animasi' => ['name' => 'Animasi', 'num' => 5, 'badge' => 'major-animasi'],
                ];
                $currentGroupMajor = null;
            @endphp

            @foreach ($classesWithMajor as $cls)
                @php
                    $clsSchedules = $schedulesByClass->get($cls->id, collect());
                    $mKey = $cls->major_key;
                    $mMeta = $majorDetails[$mKey] ?? ['name' => $cls->major ?? 'Umum', 'num' => '', 'badge' => 'major-umum'];
                @endphp

                <!-- Pembatas Seksi Program Keahlian / Jurusan -->
                @if ($currentGroupMajor !== $mKey)
                    @php $currentGroupMajor = $mKey; @endphp
                    <div class="major-section-divider no-print" data-section-major="{{ $mKey }}">
                        <div class="divider-badge-box">
                            <span class="divider-num">{{ $mMeta['num'] }}</span>
                            <span class="divider-title">PROGRAM KEAHLIAN {{ strtoupper($mKey) }} &bull; {{ $mMeta['name'] }}</span>
                        </div>
                    </div>
                @endif

                <div
                    class="class-schedule-box"
                    data-class-id="{{ $cls->id }}"
                    data-major="{{ $mKey }}"
                    data-class-name="{{ strtolower($cls->name) }}"
                    id="classBox_{{ $cls->id }}"
                >
                    <!-- Header Box Kelas -->
                    <div class="class-box-header">
                        <div class="class-box-title-group">
                            <div class="class-avatar-badge">{{ $cls->name }}</div>
                            <div>
                                <h4 class="class-box-title">Jadwal Kelas {{ $cls->name }}</h4>
                                <div class="class-box-subtitle">
                                    <span class="badge-major-pill {{ $mMeta['badge'] }}">
                                        {{ $mMeta['num'] ? $mMeta['num'].'. ' : '' }}{{ $mKey }} &bull; {{ $mMeta['name'] }}
                                    </span>
                                    <span class="class-total-kbm">
                                        <strong class="cls-visible-count">{{ $clsSchedules->count() }}</strong> Jam Pelajaran Terdaftar
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="class-box-actions no-print">
                            <button type="button" class="btn-print-single-class" onclick="printSingleClass('{{ $cls->id }}')" title="Cetak jadwal khusus kelas {{ $cls->name }}">
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak {{ $cls->name }}
                            </button>
                        </div>
                    </div>

                    <!-- Tempat Jadwal Kelas di Setiap Hari (Senin – Jumat) -->
                    <div class="admin-days-container">
                        @php
                            $clsByDay = $clsSchedules->groupBy(function($item) {
                                return strtolower($item->day_of_week ?? $item->day ?? '');
                            });
                        @endphp

                        @foreach ($dayConfigs as $dayKey => $dayName)
                            @php
                                $dayItems = $clsByDay->get($dayKey, collect());
                                $isTodayRow = ($dayKey === $todayDayKey);
                            @endphp
                            <div
                                class="admin-day-section {{ $isTodayRow ? 'is-today-sec' : '' }}"
                                data-day="{{ $dayKey }}"
                                data-class-id="{{ $cls->id }}"
                            >
                                <div class="admin-day-section-header">
                                    <div class="admin-day-title-wrap">
                                        <span class="day-badge day-badge-{{ $dayKey }}">Hari {{ $dayName }}</span>
                                        @if ($isTodayRow)
                                            <span class="badge-mini-today">Hari Ini</span>
                                        @endif
                                    </div>
                                    <span class="admin-day-count-badge">{{ $dayItems->count() }} Jam KBM</span>
                                </div>

                                @if ($dayItems->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="admin-schedule-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 50px; text-align: center;">No.</th>
                                                    <th style="width: 140px;">Waktu KBM</th>
                                                    <th style="min-width: 190px;">Mata Pelajaran</th>
                                                    <th style="min-width: 190px;">Guru Pengampu</th>
                                                    <th style="width: 160px;">Ruangan / Lab</th>
                                                    <th class="no-print" style="width: 80px; text-align: center;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dayItems as $schedule)
                                                    @php
                                                        $effectiveTeacher = $schedule->effectiveTeacherForDate($todayDate);
                                                        $isSubstituted = $effectiveTeacher->id !== $schedule->teacher_id;
                                                        $searchString = strtolower(($schedule->subject?->name ?? '') . ' ' . ($schedule->subject?->code ?? '') . ' ' . ($schedule->teacher?->name ?? '') . ' ' . ($effectiveTeacher?->name ?? '') . ' ' . ($schedule->roomModel?->name ?? $schedule->room ?? ''));
                                                    @endphp
                                                    <tr
                                                        class="dash-schedule-row"
                                                        data-day="{{ $dayKey }}"
                                                        data-class-id="{{ $cls->id }}"
                                                        data-major="{{ $mKey }}"
                                                        data-search="{{ $searchString }}"
                                                    >
                                                        <td class="col-number dash-cell-index" style="text-align: center;">{{ $loop->iteration }}</td>
                                                        <td>
                                                            <span class="badge badge-code time-pill-nowrap">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</span>
                                                        </td>
                                                        <td>
                                                            <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                                                <strong style="color:#0f172a; font-size:13.5px;">{{ $schedule->subject?->name ?? '-' }}</strong>
                                                                @if ($schedule->subject?->code)
                                                                    <span class="badge badge-code" style="font-size:10.5px;">{{ $schedule->subject->code }}</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($isSubstituted)
                                                                <div style="font-weight: 700; color: #1e40af;">{{ $effectiveTeacher->name }}</div>
                                                                <span class="badge badge-blue" style="font-size: 10px; margin-top: 2px;">Guru Inval Hari Ini</span>
                                                            @else
                                                                <span style="color: #1e293b; font-weight: 600;">{{ $schedule->teacher?->name ?? '-' }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-blue" style="font-weight: 700; white-space: nowrap; display: inline-block;">
                                                                {{ $schedule->roomModel?->name ?? $schedule->room ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td class="no-print" style="text-align: center;">
                                                            <button
                                                                type="button"
                                                                class="btn-edit-action"
                                                                onclick="openEditScheduleModal({{ json_encode([
                                                                    'id' => $schedule->id,
                                                                    'day' => $dayKey,
                                                                    'start_time' => substr($schedule->start_time, 0, 5),
                                                                    'end_time' => substr($schedule->end_time, 0, 5),
                                                                    'class_id' => $schedule->class_id,
                                                                    'subject_id' => $schedule->subject_id,
                                                                    'teacher_id' => $schedule->teacher_id,
                                                                    'room_id' => $schedule->room_id,
                                                                    'room' => $schedule->roomModel?->name ?? $schedule->room ?? '',
                                                                ]) }})"
                                                                title="Edit Jadwal Ini"
                                                            >
                                                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                                <span>Edit</span>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="admin-day-empty-row">
                                        Tidak ada jadwal KBM pada hari {{ $dayName }}.
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <div class="dash-empty-filter-row" style="display:none; padding: 20px; text-align: center; color: #64748b; font-style: italic;">
                            Tidak ada jadwal KBM pada kelas {{ $cls->name }} yang sesuai dengan filter yang dipilih.
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Global Empty State -->
            <div id="dashOverallEmptyBox" style="display:none; text-align:center; padding: 40px 20px; background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 12px; margin-top: 14px;">
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #94a3b8; margin: 0 auto 10px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h4 style="margin:0 0 4px; font-size:15px; font-weight:800; color:#334155;">Tidak Ada Jadwal Ditemukan</h4>
                <p style="margin:0 0 14px; font-size:12.5px; color:#64748b;">Silakan ubah filter hari, kelas, jurusan atau kata kunci pencarian Anda.</p>
                <button type="button" class="btn secondary" onclick="resetAdminFilters()" style="font-size:12.5px; padding:6px 14px;">
                    Reset Semua Filter
                </button>
            </div>
        </div>

        <!-- Official Printable Signatures (Hanya Tampil Saat Cetak / Print) -->
        <div class="print-only-footer">
            <div class="sign-column">
                <p>Mengetahui,<br><strong>Kepala SMK Negeri 1</strong></p>
                <div class="sign-space"></div>
                <p class="sign-name"><strong>Drs. H. Administrator, M.Pd.</strong><br><span>NIP. 19750815 200003 1 002</span></p>
            </div>
            <div class="sign-column">
                <p>Koordinator Pengembang Kurikulum,<br><strong>SMK Negeri 1</strong></p>
                <div class="sign-space"></div>
                <p class="sign-name"><strong>Tim Kurikulum SMKN 1</strong><br><span>NIP. 19820412 200801 2 015</span></p>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT JADWAL LANGSUNG DARI DASHBOARD (Akses Langsung Admin) -->
    <div id="editScheduleDashboardModal" class="dashboard-modal-backdrop" onclick="handleDashboardModalClick(event)">
        <div class="dashboard-modal-card">
            <div class="modal-card-header">
                <div class="modal-header-left">
                    <div class="modal-header-icon">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin:0; font-size:16px; font-weight:800; color:#0f172a;">Edit Jadwal Pelajaran</h4>
                        <span style="font-size:12px; color:#64748b;">Perbarui detail jam, kelas, mapel, guru, atau ruangan</span>
                    </div>
                </div>
                <button type="button" class="btn-modal-close" onclick="closeEditScheduleModal()" title="Tutup">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="editScheduleDashboardForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-card-body">
                    <div class="modal-form-grid">
                        <!-- Hari -->
                        <div class="form-group-item">
                            <label for="editModalDay">Hari Pelajaran</label>
                            <select id="editModalDay" name="day" required class="modal-input-select">
                                <option value="monday">Senin</option>
                                <option value="tuesday">Selasa</option>
                                <option value="wednesday">Rabu</option>
                                <option value="thursday">Kamis</option>
                                <option value="friday">Jumat</option>
                                <option value="saturday">Sabtu</option>
                            </select>
                        </div>

                        <!-- Ruangan -->
                        <div class="form-group-item">
                            <label for="editModalRoom">Ruangan / Lab</label>
                            <input
                                type="text"
                                id="editModalRoom"
                                name="room"
                                list="dashboardRoomList"
                                placeholder="Contoh: Lab Komputer PPLG"
                                class="modal-input-text"
                                required
                            >
                            <datalist id="dashboardRoomList">
                                @foreach ($rooms ?? [] as $rm)
                                    <option value="{{ $rm->name }}"></option>
                                @endforeach
                            </datalist>
                        </div>

                        <!-- Jam Mulai -->
                        <div class="form-group-item">
                            <label for="editModalStartTime">Jam Mulai</label>
                            <input type="time" id="editModalStartTime" name="start_time" required class="modal-input-text">
                        </div>

                        <!-- Jam Selesai -->
                        <div class="form-group-item">
                            <label for="editModalEndTime">Jam Selesai</label>
                            <input type="time" id="editModalEndTime" name="end_time" required class="modal-input-text">
                        </div>

                        <!-- Kelas -->
                        <div class="form-group-item full-span">
                            <label for="editModalClass">Kelas & Jurusan</label>
                            <select id="editModalClass" name="class_id" required class="modal-input-select">
                                @foreach ($classes as $cls)
                                    <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->major ?? 'Umum' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="form-group-item full-span">
                            <label for="editModalSubject">Mata Pelajaran</label>
                            <select id="editModalSubject" name="subject_id" required class="modal-input-select">
                                @foreach ($subjects as $sb)
                                    <option value="{{ $sb->id }}">{{ $sb->name }} ({{ $sb->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Guru Pengampu -->
                        <div class="form-group-item full-span">
                            <label for="editModalTeacher">Guru Pengampu</label>
                            <select id="editModalTeacher" name="teacher_id" required class="modal-input-select">
                                @foreach ($teachers as $tc)
                                    <option value="{{ $tc->id }}">{{ $tc->name }} ({{ $tc->nip }})</option>
                                @endforeach
                            </select>
                            <span style="font-size:11px; color:#64748b; margin-top:4px; display:block;">💡 Tips: Pastikan guru yang dipilih tidak sedang memiliki jadwal mengajar di kelas lain pada jam KBM yang sama agar bebas bentrok.</span>
                        </div>
                    </div>
                </div>

                <div class="modal-card-footer">
                    <button type="button" class="btn secondary" onclick="closeEditScheduleModal()" style="font-size:13px; padding:8px 16px;">
                        Batal
                    </button>
                    <button type="submit" class="btn primary" style="font-size:13px; padding:8px 20px;">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Styles & Animations -->
    <style>
        .alert-success-banner {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            color: #166534;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(34, 197, 94, 0.12);
        }

        .alert-error-banner {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            color: #991b1b;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.12);
        }

        .admin-welcome-banner {
            background: linear-gradient(135deg, #1e3a5f 0%, #264a78 60%, #2f5b93 100%);
            border-radius: 14px;
            padding: 15px 22px;
            color: #ffffff;
            margin-bottom: 18px;
            box-shadow: 0 4px 16px -4px rgba(30, 58, 95, 0.22);
        }

        .banner-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, 0.12);
            color: #dbeafe;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .banner-title {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.2px;
            margin: 0 0 3px;
            color: #ffffff;
        }

        .banner-desc {
            font-size: 12.5px;
            color: #dbeafe;
            margin: 0;
            line-height: 1.45;
            opacity: 0.92;
        }

        .banner-year-pill {
            font-size: 11.5px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #ffffff;
            padding: 5px 13px;
            border-radius: 99px;
            white-space: nowrap;
        }

        .schedule-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            gap: 16px;
            background: #ffffff;
            border-bottom: 1px solid #e5ebf3;
        }

        .schedule-header-text {
            flex: 1;
            min-width: 0;
        }

        .schedule-header-btn {
            flex-shrink: 0;
        }

        .btn-print-admin {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #2563eb;
            color: #ffffff !important;
            border: none;
            padding: 8px 16px;
            border-radius: 9px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(37, 99, 235, 0.24);
            transition: all 0.18s ease;
            white-space: nowrap;
        }

        .btn-print-admin:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
        }

        /* 5 JURUSAN TABS NAVIGATION (RATA 6 KOLOM - BEBAS SCROLLBAR) */
        .majors-tabs-bar {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 6px;
            padding: 10px 16px;
            background: #f8fafd;
            border-bottom: 1px solid #e5ebf3;
            overflow: hidden;
        }

        .major-tab-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 7px 4px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            background: #ffffff;
            color: #475569;
            border: 1.5px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.16s ease;
            text-align: center;
            min-width: 0;
            width: 100%;
        }

        .major-tab-btn:hover {
            border-color: #93c5fd;
            color: #2563eb;
            background: #eff6ff;
        }

        .major-tab-btn.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.22);
        }

        .major-tab-btn .tab-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 800;
            background: #eef2f7;
            color: #334155;
            flex-shrink: 0;
        }

        .major-tab-btn.active .tab-num {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
        }

        .major-tab-btn .tab-label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* TOOLBAR FILTER TERPADU (1 BARIS RINGKAS) */
        .dashboard-filter-toolbar {
            padding: 10px 16px;
            background: #ffffff;
            border-bottom: 1.5px solid #e2e8f0;
        }

        .filter-unified-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .day-pills-container {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
        }

        .day-pill {
            background: #ffffff;
            color: #475569;
            border: 1.5px solid #e2e8f0;
            border-radius: 7px;
            padding: 0 11px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .day-pill:hover {
            color: #2563eb;
            border-color: #bfdbfe;
            background: #eff6ff;
        }

        .day-pill.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 1px 4px rgba(37, 99, 235, 0.22);
        }

        .filter-controls-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .filter-class-dropdown-wrap {
            display: inline-flex;
            align-items: center;
        }

        .custom-select-dashboard {
            background: #ffffff;
            color: #0f172a;
            border: 1.5px solid #d5dfec;
            border-radius: 7px;
            padding: 0 26px 0 10px;
            font-size: 12px;
            font-weight: 700;
            outline: none;
            cursor: pointer;
            font-family: inherit;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            height: 34px;
            transition: all 0.2s ease;
        }

        .custom-select-dashboard:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.14);
        }

        .search-box-wrap {
            position: relative;
        }

        .search-box-wrap svg {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .search-box-wrap input {
            background: #ffffff;
            border: 1.5px solid #d5dfec;
            border-radius: 7px;
            padding: 0 10px 0 30px;
            font-size: 12px;
            font-weight: 500;
            color: #0f172a;
            outline: none;
            width: 195px;
            height: 34px;
            transition: all 0.2s ease;
        }

        .search-box-wrap input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.14);
            width: 220px;
        }

        .btn-reset-filter {
            background: #ffffff;
            border: 1.5px solid #d5dfec;
            color: #64748b;
            border-radius: 7px;
            padding: 0 11px;
            font-size: 11.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            height: 34px;
            transition: all 0.15s ease;
        }

        .btn-reset-filter:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #94a3b8;
        }

        /* SECTION DIVIDER PER JURUSAN */
        .major-section-divider {
            margin: 6px 0 -8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #1e3a5f 0%, #2a4d7c 100%);
            border-radius: 10px;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(30, 58, 95, 0.12);
        }

        .divider-badge-box {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .divider-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
        }

        .divider-title {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.3px;
        }

        /* SUB-PILLS NAVIGASI KELAS CEPAT (SEJAJAR, RINGKAS, BEBAS SCROLL PANJANG) */
        .class-quick-pills-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 18px;
            background: #ffffff;
            border-bottom: 1.5px solid #e2e8f0;
            flex-wrap: wrap;
        }

        .quick-pills-intro {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            flex-shrink: 0;
        }

        .quick-pills-intro svg {
            color: #2563eb;
        }

        .quick-pills-list {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .quick-class-pill {
            background: #f8fafc;
            color: #334155;
            border: 1.5px solid #d5dfec;
            border-radius: 7px;
            padding: 5px 13px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.16s ease;
            white-space: nowrap;
        }

        .quick-class-pill:hover {
            background: #eff6ff;
            color: #2563eb;
            border-color: #93c5fd;
        }

        .quick-class-pill.active {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 1px 4px rgba(37, 99, 235, 0.22);
        }

        /* CARD EDUKASI PANDUAN BEBAS BENTROK GURU & RUANGAN */
        .schedule-guide-card {
            margin: 16px 20px 0;
            background: #f8fafd;
            border: 1.5px solid #dbeafe;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(37, 99, 235, 0.05);
        }

        .guide-header-box {
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            background: #ffffff;
            border-bottom: 1px solid #e5ebf3;
        }

        .guide-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid #dbeafe;
        }

        .guide-text-wrap {
            flex: 1;
            min-width: 240px;
        }

        .guide-tag {
            font-size: 10px;
            font-weight: 800;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .guide-title {
            margin: 0 0 2px;
            font-size: 13.5px;
            font-weight: 800;
            color: #0f172a;
        }

        .guide-desc {
            margin: 0;
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
        }

        .btn-toggle-guide {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            padding: 5px 12px;
            border-radius: 7px;
            font-size: 11.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-toggle-guide:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .guide-collapse-body {
            padding: 14px 18px;
            background: #f8fafd;
        }

        .guide-cards-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        @media (max-width: 1000px) {
            .guide-cards-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .guide-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        .guide-card-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            padding: 12px 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .guide-card-step {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #2563eb;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .guide-card-body strong {
            display: block;
            font-size: 12px;
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 3px;
        }

        .guide-card-body p {
            margin: 0;
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
        }

        /* CLASS CARDS CONTAINER (PER KELAS) */
        .class-cards-container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .class-schedule-box {
            background: #ffffff;
            border: 1px solid #e5ebf3;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .class-schedule-box:hover {
            border-color: #cbd5e1;
            box-shadow: 0 6px 18px -4px rgba(15, 23, 42, 0.07);
        }

        .class-box-header {
            padding: 13px 18px;
            background: #fafbfd;
            border-bottom: 1px solid #e5ebf3;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .class-box-title-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .class-avatar-badge {
            padding: 7px 13px;
            border-radius: 9px;
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: #ffffff;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
            white-space: nowrap;
        }

        .class-box-title {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }

        .class-box-subtitle {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 3px;
            flex-wrap: wrap;
        }

        .class-total-kbm {
            font-size: 11.5px;
            color: #64748b;
            font-weight: 600;
        }

        .btn-print-single-class {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 13px;
            background: #ffffff;
            color: #2563eb;
            border: 1.5px solid #dbeafe;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .btn-print-single-class:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.22);
        }

        .btn-edit-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 5px 11px;
            border-radius: 7px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
            font-size: 11.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }

        .btn-edit-action:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        }

        /* BADGES JURUSAN */
        .badge-major-pill {
            font-size: 10.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .badge-major-pill.major-pplg { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .badge-major-pill.major-bcf { background: #fff1f2; color: #be123c; border: 1px solid #ffe4e6; }
        .badge-major-pill.major-tpfl { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
        .badge-major-pill.major-to { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
        .badge-major-pill.major-animasi { background: #f5f3ff; color: #6d28d9; border: 1px solid #ede9fe; }
        .badge-major-pill.major-umum { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* TABEL JADWAL KELAS (STANDAR RESMI SEKOLAH - LEGA, RAPI & BEBAS TERPOTONG) */
        .class-schedule-box .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border: none;
            border-radius: 0;
            box-shadow: none;
        }

        table.admin-schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            text-align: left;
            min-width: 720px;
        }

        table.admin-schedule-table thead th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 700;
            padding: 10px 14px;
            border-bottom: 1.5px solid #e2e8f0;
            letter-spacing: 0.04em;
            white-space: nowrap;
            font-size: 11px;
            text-transform: uppercase;
        }

        table.admin-schedule-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.14s ease;
        }

        table.admin-schedule-table tbody tr:nth-child(even) {
            background: #fafbfd;
        }

        table.admin-schedule-table tbody tr:hover {
            background: #f5f9ff;
        }

        table.admin-schedule-table tbody td {
            padding: 11px 14px;
            color: #334155;
            vertical-align: middle;
        }

        .col-number {
            font-weight: 700;
            color: #64748b;
        }

        .class-schedule-box .table-responsive {
            border: none;
            border-radius: 0;
            box-shadow: none;
        }

        .admin-day-section {
            border-bottom: 1.5px solid #e2e8f0;
            background: #ffffff;
            transition: background 0.15s ease;
        }

        .admin-day-section:last-child {
            border-bottom: none;
        }

        .admin-day-section-header {
            padding: 9px 16px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }

        .admin-day-section.is-today-sec .admin-day-section-header {
            background: #eff6ff;
            border-bottom-color: #bfdbfe;
        }

        .admin-day-title-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-day-count-badge {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 2px 7px;
            border-radius: 5px;
            white-space: nowrap;
        }

        .admin-day-empty-row {
            padding: 12px 18px;
            color: #94a3b8;
            font-size: 12px;
            font-style: italic;
            font-weight: 500;
        }

        .day-cell-stack {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 3px;
        }

        .time-pill-nowrap {
            white-space: nowrap;
            display: inline-block;
        }

        .day-badge {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .day-badge-monday { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
        .day-badge-tuesday { background: #ecfdf5; color: #047857; border: 1px solid #d1fae5; }
        .day-badge-wednesday { background: #f0f9ff; color: #0369a1; border: 1px solid #e0f2fe; }
        .day-badge-thursday { background: #fffbeb; color: #b45309; border: 1px solid #fef3c7; }
        .day-badge-friday { background: #f5f3ff; color: #6d28d9; border: 1px solid #ede9fe; }

        .badge-mini-today {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            padding: 1px 6px;
            border-radius: 5px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            white-space: nowrap;
        }

        .badge-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 600;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 2px 7px;
            border-radius: 5px;
            color: #334155;
            white-space: nowrap;
        }

        .badge-blue {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 11.5px;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }

        /* MODAL STYLES */
        .dashboard-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .dashboard-modal-backdrop.show {
            display: flex;
        }

        .dashboard-modal-card {
            background: #ffffff;
            border-radius: 14px;
            max-width: 620px;
            width: 100%;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.3);
            border: 1px solid #cbd5e1;
            overflow: hidden;
            animation: modalFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(12px) scale(0.97);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-card-header {
            padding: 16px 20px;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-header-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: #2563eb;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-modal-close {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 4px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-modal-close:hover {
            color: #0f172a;
            background: #e2e8f0;
        }

        .modal-card-body {
            padding: 20px;
            max-height: 72vh;
            overflow-y: auto;
        }

        .modal-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .form-group-item.full-span {
            grid-column: span 2;
        }

        .form-group-item label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .modal-input-text,
        .modal-input-select {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            color: #0f172a;
            background: #ffffff;
            outline: none;
            box-sizing: border-box;
            transition: border-color 0.15s ease;
        }

        .modal-input-text:focus,
        .modal-input-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.14);
        }

        .modal-card-footer {
            padding: 14px 20px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* RESPONSIVE DESIGN (TABLET & MOBILE) */
        @media (max-width: 960px) {
            .majors-tabs-bar {
                grid-template-columns: repeat(3, 1fr);
            }

            .schedule-header-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .schedule-header-btn {
                width: 100%;
            }

            .schedule-header-btn .btn-print-admin {
                width: 100%;
                justify-content: center;
            }

            .filter-unified-row {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .day-pills-container {
                overflow-x: auto;
                white-space: nowrap;
                padding-bottom: 3px;
                flex-wrap: nowrap;
                -webkit-overflow-scrolling: touch;
            }

            .filter-controls-right {
                width: 100%;
                justify-content: space-between;
            }

            .custom-select-dashboard {
                flex: 1;
            }

            .search-box-wrap {
                flex: 1;
            }

            .search-box-wrap input {
                width: 100% !important;
            }

            .class-cards-container.side-by-side-mode {
                grid-template-columns: 1fr;
            }

            .class-box-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .class-box-actions {
                width: 100%;
            }

            .btn-print-single-class {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 1080px) {
            .class-cards-container.side-by-side-mode {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .majors-tabs-bar {
                grid-template-columns: repeat(2, 1fr);
                gap: 5px;
                padding: 8px 10px;
            }

            .major-tab-btn {
                padding: 6px 3px;
                font-size: 11px;
            }

            .filter-controls-right {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
            }

            .custom-select-dashboard,
            .search-box-wrap,
            .btn-reset-filter {
                width: 100%;
            }

            .btn-reset-filter {
                justify-content: center;
            }

            .class-cards-container {
                padding: 10px;
                gap: 12px;
            }

            table.admin-schedule-table th,
            table.admin-schedule-table td {
                padding: 8px 8px;
                font-size: 11.5px;
            }

            .modal-form-grid {
                grid-template-columns: 1fr;
            }

            .form-group-item.full-span {
                grid-column: span 1;
            }
        }

        /* PRINT STYLING */
        .print-only-header,
        .print-only-footer {
            display: none;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 1.2cm 1.5cm;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 11.5px;
            }

            .sidebar,
            .topbar,
            .nav-section,
            .no-print,
            .btn,
            .btn-print-admin,
            .btn-manage-top,
            .dashboard-filter-toolbar,
            .majors-tabs-bar,
            .class-pills-bar,
            .major-section-divider,
            .badge-mini-today,
            .alert-success-banner,
            .alert-error-banner,
            .dashboard-modal-backdrop {
                display: none !important;
            }

            .app-shell {
                display: block !important;
            }

            .content {
                padding: 0 !important;
                margin: 0 !important;
            }

            .card.schedule-main-card {
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
                padding: 0 !important;
            }

            .class-cards-container,
            .class-cards-container.side-by-side-mode {
                display: flex !important;
                flex-direction: column !important;
                padding: 0 !important;
                gap: 20px !important;
            }

            .class-cards-container.side-by-side-mode .badge-major-pill {
                display: inline-block !important;
            }

            .class-cards-container.side-by-side-mode table.admin-schedule-table {
                display: table !important;
            }

            .class-cards-container.side-by-side-mode table.admin-schedule-table thead {
                display: table-header-group !important;
            }

            .class-cards-container.side-by-side-mode table.admin-schedule-table tbody {
                display: table-row-group !important;
            }

            .class-cards-container.side-by-side-mode table.admin-schedule-table tbody tr.dash-schedule-row {
                display: table-row !important;
                padding: 0 !important;
            }

            .class-cards-container.side-by-side-mode table.admin-schedule-table tbody tr.dash-schedule-row td {
                display: table-cell !important;
                padding: 7px 9px !important;
                border: 1px solid #444 !important;
            }

            .class-cards-container.side-by-side-mode table.admin-schedule-table tbody tr.dash-schedule-row td:nth-child(1) {
                display: table-cell !important;
            }

            .class-cards-container.side-by-side-mode table.admin-schedule-table tbody tr.dash-schedule-row td:nth-child(7) {
                display: none !important;
            }

            .class-schedule-box {
                border: 1.5px solid #000 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }

            .class-schedule-box.print-hide-box {
                display: none !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            .print-only-header {
                display: block !important;
                margin-bottom: 16px;
            }

            .kop-surat {
                display: flex;
                align-items: center;
                gap: 16px;
                text-align: center;
                justify-content: center;
            }

            .kop-teks h3 {
                font-size: 13px;
                margin: 0;
                font-weight: 700;
                color: #000;
            }

            .kop-teks h4 {
                font-size: 11px;
                margin: 2px 0;
                font-weight: 600;
                color: #333;
            }

            .kop-teks h2 {
                font-size: 18px;
                margin: 2px 0;
                font-weight: 900;
                color: #000;
            }

            .kop-teks p {
                font-size: 10px;
                margin: 2px 0 0;
                color: #555;
            }

            .kop-garis {
                border-top: 3px solid #000;
                border-bottom: 1px solid #000;
                height: 4px;
                margin: 10px 0 14px;
            }

            .print-doc-meta {
                text-align: center;
                margin-bottom: 14px;
            }

            .print-doc-meta h3 {
                font-size: 14px;
                margin: 0 0 4px;
                font-weight: 800;
                text-transform: uppercase;
            }

            .print-doc-meta p {
                font-size: 11px;
                color: #333;
                margin: 0 0 6px;
            }

            .print-meta-badge {
                display: flex;
                justify-content: space-between;
                font-size: 10px;
                color: #444;
                border-top: 1px dashed #ccc;
                border-bottom: 1px dashed #ccc;
                padding: 4px 6px;
                margin-top: 6px;
            }

            table.admin-schedule-table {
                width: 100% !important;
                border-collapse: collapse !important;
                border: 1.5px solid #000 !important;
                font-size: 11px !important;
            }

            table.admin-schedule-table th,
            table.admin-schedule-table td {
                border: 1px solid #444 !important;
                padding: 5px 8px !important;
                color: #000 !important;
                background: transparent !important;
            }

            table.admin-schedule-table thead th {
                background: #f1f5f9 !important;
                font-weight: 800 !important;
                text-align: center;
                color: #000 !important;
            }

            .day-badge,
            .badge-code,
            .badge-blue,
            .badge-major-pill {
                border: none !important;
                background: transparent !important;
                color: #000 !important;
                padding: 0 !important;
                font-weight: 700 !important;
            }

            .print-only-footer {
                display: flex !important;
                justify-content: space-between;
                margin-top: 36px;
                page-break-inside: avoid;
            }

            .sign-column {
                width: 250px;
                text-align: center;
                font-size: 11.5px;
            }

            .sign-space {
                height: 55px;
            }

            .sign-name strong {
                display: block;
                text-decoration: underline;
                font-size: 12px;
            }

            .sign-name span {
                font-size: 11px;
                color: #333;
            }
        }
    </style>

    <script>
        const dayMap = {
            'all': 'Semua Hari',
            'monday': 'Senin',
            'tuesday': 'Selasa',
            'wednesday': 'Rabu',
            'thursday': 'Kamis',
            'friday': 'Jumat'
        };

        let currentMajorFilter = 'PPLG';
        let currentClassFilter = 'all';
        let currentDayFilter = 'all';

        function selectAdminMajor(majorKey) {
            currentMajorFilter = majorKey;
            currentClassFilter = 'all';

            // Update major tab buttons
            document.querySelectorAll('.majors-tabs-bar .major-tab-btn').forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-major') === majorKey);
            });

            // Update sub-pills label & visibility
            const majorPillLabel = document.getElementById('quickPillMajorLabel');
            if (majorPillLabel) {
                majorPillLabel.textContent = (majorKey === 'all') ? 'Semua Jurusan' : majorKey;
            }

            document.querySelectorAll('#classQuickPillsList .quick-class-pill').forEach(pill => {
                const pillVal = pill.getAttribute('data-class-pill');
                if (pillVal === 'all') {
                    pill.classList.add('active');
                    pill.style.display = '';
                    return;
                }
                const pillMajor = pill.getAttribute('data-major');
                const isMatch = (majorKey === 'all' || pillMajor === majorKey);
                pill.style.display = isMatch ? '' : 'none';
                pill.classList.remove('active');
            });

            // Update class select dropdown options
            const classSelect = document.getElementById('adminClassSelect');
            if (classSelect) {
                Array.from(classSelect.options).forEach(opt => {
                    if (opt.value === 'all') {
                        opt.textContent = (majorKey === 'all') ? 'Semua Kelas (10 Kelas)' : `Semua Kelas ${majorKey}`;
                        opt.hidden = false;
                        opt.disabled = false;
                        opt.style.display = '';
                        return;
                    }
                    const optMajor = opt.getAttribute('data-major');
                    const shouldShow = (majorKey === 'all' || optMajor === majorKey);
                    opt.hidden = !shouldShow;
                    opt.disabled = !shouldShow;
                    opt.style.display = shouldShow ? '' : 'none';
                });
                classSelect.value = 'all';
            }

            applyAdminScheduleFilters();
        }

        function selectAdminClass(classId) {
            currentClassFilter = classId;
            const classSelect = document.getElementById('adminClassSelect');
            if (classSelect && classSelect.value !== String(classId)) {
                classSelect.value = classId;
            }

            // Sync quick class pills
            document.querySelectorAll('#classQuickPillsList .quick-class-pill').forEach(pill => {
                pill.classList.toggle('active', pill.getAttribute('data-class-pill') === String(classId));
            });

            applyAdminScheduleFilters();
        }

        function selectQuickClassPill(classId) {
            selectAdminClass(classId);
        }

        function toggleScheduleGuide() {
            const body = document.getElementById('guideCollapseBody');
            const text = document.getElementById('guideToggleText');
            const icon = document.getElementById('guideToggleIcon');
            if (!body) return;

            const isClosed = body.style.display === 'none';
            body.style.display = isClosed ? '' : 'none';
            if (text) text.textContent = isClosed ? 'Tutup Panduan' : 'Buka Panduan';
            if (icon) icon.style.transform = isClosed ? 'rotate(0deg)' : 'rotate(180deg)';
        }

        function setAdminDayFilter(dayKey) {
            currentDayFilter = dayKey;

            // Update day pills
            document.querySelectorAll('#adminDashboardDayPills .day-pill').forEach(pill => {
                pill.classList.toggle('active', pill.getAttribute('data-day') === dayKey);
            });

            applyAdminScheduleFilters();
        }

        function resetAdminFilters() {
            currentMajorFilter = 'PPLG';
            currentClassFilter = 'all';
            currentDayFilter = 'all';

            // Reset Major Tabs (Default: PPLG)
            document.querySelectorAll('.majors-tabs-bar .major-tab-btn').forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('data-major') === 'PPLG');
            });

            // Reset Class Select
            const classSelect = document.getElementById('adminClassSelect');
            if (classSelect) {
                Array.from(classSelect.options).forEach(opt => {
                    if (opt.value === 'all') {
                        opt.textContent = 'Semua Kelas PPLG';
                        opt.hidden = false;
                        opt.disabled = false;
                        opt.style.display = '';
                        return;
                    }
                    const isPplg = (opt.getAttribute('data-major') === 'PPLG');
                    opt.hidden = !isPplg;
                    opt.disabled = !isPplg;
                    opt.style.display = isPplg ? '' : 'none';
                });
                classSelect.value = 'all';
            }

            // Reset Sub-Class Pills
            const majorPillLabel = document.getElementById('quickPillMajorLabel');
            if (majorPillLabel) majorPillLabel.textContent = 'PPLG';

            document.querySelectorAll('#classQuickPillsList .quick-class-pill').forEach(pill => {
                const pillVal = pill.getAttribute('data-class-pill');
                if (pillVal === 'all') {
                    pill.classList.add('active');
                    pill.style.display = '';
                    return;
                }
                const isPplg = (pill.getAttribute('data-major') === 'PPLG');
                pill.style.display = isPplg ? '' : 'none';
                pill.classList.remove('active');
            });

            // Reset Day Pills
            document.querySelectorAll('#adminDashboardDayPills .day-pill').forEach(pill => {
                pill.classList.toggle('active', pill.getAttribute('data-day') === 'all');
            });

            // Reset Search Input
            const searchInput = document.getElementById('adminSearchInput');
            if (searchInput) searchInput.value = '';

            applyAdminScheduleFilters();
        }

        function applyAdminScheduleFilters() {
            const searchInput = document.getElementById('adminSearchInput');
            const searchKeyword = searchInput ? searchInput.value.toLowerCase().trim() : '';

            const classBoxes = document.querySelectorAll('.class-schedule-box');
            const overallEmptyBox = document.getElementById('dashOverallEmptyBox');
            const visibleCountEl = document.getElementById('adminVisibleCount');

            let totalVisibleSchedules = 0;
            let visibleClassBoxes = 0;

            classBoxes.forEach(box => {
                const boxMajor = box.getAttribute('data-major');
                const boxClassId = box.getAttribute('data-class-id');

                // Major & Class match check
                const matchesMajor = (currentMajorFilter === 'all' || boxMajor === currentMajorFilter);
                const matchesClass = (currentClassFilter === 'all' || boxClassId === String(currentClassFilter));

                if (!matchesMajor || !matchesClass) {
                    box.style.display = 'none';
                    return;
                }

                // Filter sections per hari di dalam class box ini
                const daySections = box.querySelectorAll('.admin-day-section');
                const emptyFilterRow = box.querySelector('.dash-empty-filter-row');
                const boxCountEl = box.querySelector('.cls-visible-count');

                let boxVisibleCount = 0;
                let dayMatchesCount = 0;

                daySections.forEach(daySec => {
                    const secDay = daySec.getAttribute('data-day');
                    const matchesDay = (currentDayFilter === 'all' || secDay === currentDayFilter);

                    if (!matchesDay) {
                        daySec.style.display = 'none';
                        return;
                    }

                    const rows = daySec.querySelectorAll('.dash-schedule-row');
                    let secMatchingRows = 0;

                    rows.forEach(row => {
                        const rowSearch = row.getAttribute('data-search') || '';
                        const matchesSearch = (searchKeyword === '' || rowSearch.includes(searchKeyword));

                        if (matchesSearch) {
                            secMatchingRows++;
                            boxVisibleCount++;
                            row.style.display = '';
                            const cellIndex = row.querySelector('.dash-cell-index');
                            if (cellIndex) cellIndex.textContent = secMatchingRows;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Bila ada pencarian kata kunci dan section ini punya rows tapi tidak ada yang cocok
                    if (searchKeyword && rows.length > 0 && secMatchingRows === 0) {
                        daySec.style.display = 'none';
                    } else {
                        daySec.style.display = '';
                        dayMatchesCount++;
                    }
                });

                if (boxCountEl) boxCountEl.textContent = boxVisibleCount;

                if (dayMatchesCount > 0) {
                    box.style.display = '';
                    visibleClassBoxes++;
                    totalVisibleSchedules += boxVisibleCount;
                    if (emptyFilterRow) emptyFilterRow.style.display = 'none';
                } else {
                    box.style.display = 'none';
                }
            });

            // Update major section dividers
            document.querySelectorAll('.major-section-divider').forEach(div => {
                const divMajor = div.getAttribute('data-section-major');
                if (currentMajorFilter === 'all') {
                    // Check if any matching box is visible
                    const boxes = document.querySelectorAll(`.class-schedule-box[data-major="${divMajor}"]`);
                    let anyVisible = false;
                    boxes.forEach(b => { if (b.style.display !== 'none') anyVisible = true; });
                    div.style.display = anyVisible ? '' : 'none';
                } else {
                    div.style.display = 'none';
                }
            });

            if (visibleCountEl) {
                visibleCountEl.textContent = totalVisibleSchedules;
            }

            if (overallEmptyBox) {
                overallEmptyBox.style.gridColumn = '1 / -1';
                overallEmptyBox.style.display = (visibleClassBoxes === 0 && totalVisibleSchedules === 0) ? '' : 'none';
            }
        }

        // ==========================================
        // Fitur Cetak Satu Kelas & Seluruh Jadwal
        // ==========================================
        function printSingleClass(classId) {
            document.querySelectorAll('.class-schedule-box').forEach(box => {
                if (box.getAttribute('data-class-id') === String(classId)) {
                    box.classList.remove('print-hide-box');
                } else {
                    box.classList.add('print-hide-box');
                }
            });

            const printSubtitle = document.getElementById('printDocSubtitle');
            const box = document.getElementById('classBox_' + classId);
            const prevSub = printSubtitle ? printSubtitle.textContent : '';
            if (printSubtitle && box) {
                const clsName = box.querySelector('.class-box-title')?.textContent || 'Kelas';
                printSubtitle.textContent = `Tahun Pelajaran 2026/2027 • Dokumen Resmi: ${clsName}`;
            }

            window.print();

            setTimeout(() => {
                document.querySelectorAll('.class-schedule-box').forEach(box => {
                    box.classList.remove('print-hide-box');
                });
                if (printSubtitle) printSubtitle.textContent = prevSub;
            }, 500);
        }

        function printScheduleReport() {
            document.querySelectorAll('.class-schedule-box').forEach(box => {
                box.classList.remove('print-hide-box');
            });
            window.print();
        }

        // ==========================================
        // Fitur Edit Jadwal Langsung dari Dashboard
        // ==========================================
        function openEditScheduleModal(data) {
            const modal = document.getElementById('editScheduleDashboardModal');
            const form = document.getElementById('editScheduleDashboardForm');

            if (!modal || !form) return;

            // Action URL: /admin/jadwal/{id}
            form.action = '{{ url("admin/jadwal") }}/' + data.id;

            // Fill fields
            const dayEl = document.getElementById('editModalDay');
            if (dayEl) dayEl.value = data.day;

            const startEl = document.getElementById('editModalStartTime');
            if (startEl) startEl.value = data.start_time;

            const endEl = document.getElementById('editModalEndTime');
            if (endEl) endEl.value = data.end_time;

            const classEl = document.getElementById('editModalClass');
            if (classEl) classEl.value = data.class_id;

            const subEl = document.getElementById('editModalSubject');
            if (subEl) subEl.value = data.subject_id;

            const teacherEl = document.getElementById('editModalTeacher');
            if (teacherEl) teacherEl.value = data.teacher_id;

            const roomEl = document.getElementById('editModalRoom');
            if (roomEl) roomEl.value = data.room;

            modal.classList.add('show');
        }

        function closeEditScheduleModal() {
            const modal = document.getElementById('editScheduleDashboardModal');
            if (modal) {
                modal.classList.remove('show');
            }
        }

        function handleDashboardModalClick(e) {
            if (e.target.id === 'editScheduleDashboardModal') {
                closeEditScheduleModal();
            }
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeEditScheduleModal();
            }
        });

        // Initialize on page load (Default: Tab 1. PPLG agar bersih dan tidak menumpuk)
        document.addEventListener('DOMContentLoaded', () => {
            selectAdminMajor('PPLG');
        });
    </script>
@endsection

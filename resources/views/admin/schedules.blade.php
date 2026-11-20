@extends('layouts.app')

@section('title', 'Jadwal Pelajaran | Jadwal Skanic')
@section('page-title', 'Jadwal Pelajaran')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert-success">
            {{ $message }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <strong style="display:block; margin-bottom:4px;">Gagal Memproses Jadwal:</strong>
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Top Action Bar -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:12px;">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-weight:800; font-size:12.5px; color:#334155; text-transform:uppercase;">Tahun Akademik:</span>
            <span class="badge badge-purple" style="font-size:12px; padding:3px 8px;">
                {{ $activeYear ? $activeYear->name . ' (' . ucfirst($activeYear->semester) . ')' : 'Aktif' }}
            </span>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <!-- Tombol Tambah Jadwal Baru -->
            <button type="button" class="btn" onclick="openScheduleModal()">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jadwal Baru
            </button>
        </div>
    </div>


    @php
        $todayKey = $todayDayKey ?? strtolower(now()->englishDayOfWeek);
        $validDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        $defaultDay = in_array($todayKey, $validDays) ? $todayKey : 'monday';
        $dayNamesIndo = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];
    @endphp

    <!-- Weekly Grid Timetable (Senin - Jumat) -->
    <div class="card">
        <div class="card-header schedules-filter-header">
            <div>
                <h3 style="margin:0;">Jadwal Mingguan (Senin - Jumat)</h3>
                <span id="gridScheduleCountText" style="font-size:12px; color:#64748b; font-weight:600;">Total: {{ $schedules->count() }} Jadwal Terdaftar</span>
            </div>
            <div class="schedules-filter-bar">
                <div class="sched-filter-group">
                    <label for="gridDayFilter">Hari:</label>
                    <select id="gridDayFilter" class="sched-select" onchange="filterGridDay(this.value)">
                        <option value="all">Semua Hari (Senin - Jumat)</option>
                        <option value="monday" {{ $defaultDay === 'monday' ? 'selected' : '' }}>Senin</option>
                        <option value="tuesday" {{ $defaultDay === 'tuesday' ? 'selected' : '' }}>Selasa</option>
                        <option value="wednesday" {{ $defaultDay === 'wednesday' ? 'selected' : '' }}>Rabu</option>
                        <option value="thursday" {{ $defaultDay === 'thursday' ? 'selected' : '' }}>Kamis</option>
                        <option value="friday" {{ $defaultDay === 'friday' ? 'selected' : '' }}>Jumat</option>
                    </select>
                    <button type="button" class="btn-today-pill" onclick="filterToToday()" title="Kembali ke jadwal hari ini">
                        <span class="today-dot"></span>
                        Hari Ini
                    </button>
                </div>
                <div class="sched-filter-group">
                    <label for="gridMajorFilter">Jurusan:</label>
                    <select id="gridMajorFilter" class="sched-select" onchange="filterGridMajor(this.value)">
                        <option value="all">Semua Jurusan</option>
                        @foreach ($majors as $mKey => $mLabel)
                            <option value="{{ $mKey }}">{{ $mKey }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sched-filter-group">
                    <label for="gridClassFilter">Kelas:</label>
                    <select id="gridClassFilter" class="sched-select" onchange="filterGridClass(this.value)">
                        <option value="all">Semua Kelas</option>
                        @foreach ($classes as $c)
                            <option value="{{ $c->id }}" data-major="{{ $c->major ?? '' }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body" style="padding:16px;">
            @php
                $days = [
                    'monday' => 'Senin',
                    'tuesday' => 'Selasa',
                    'wednesday' => 'Rabu',
                    'thursday' => 'Kamis',
                    'friday' => 'Jumat',
                ];
            @endphp

            <div id="weeklyScheduleGrid" class="weekly-schedule-grid">
                @foreach ($days as $dayKey => $dayName)
                    @php
                        $dayItems = $schedules->filter(fn ($s) => strtolower($s->day_of_week ?? $s->day) === $dayKey);
                        $isToday = ($dayKey === $todayKey);
                    @endphp
                    <div class="grid-day-column {{ $isToday ? 'is-today-col' : '' }}" data-day="{{ $dayKey }}">
                        <!-- Day Header with Quick Add Button -->
                        <div class="day-col-header {{ $isToday ? 'is-today-head' : '' }}">
                            <div style="display:flex; align-items:center; gap:6px;">
                                <span class="day-col-name">{{ $dayName }}</span>
                                <span class="day-col-count">{{ $dayItems->count() }}</span>
                            </div>
                            <button type="button" class="btn-day-quick-add" onclick="openScheduleModalWithDay('{{ $dayKey }}')" title="Tambah jadwal langsung di hari {{ $dayName }}">
                                + Tambah
                            </button>
                        </div>

                        <!-- Card List in Day (Kompak, Tidak Panjang Kebawah) -->
                        <div class="day-cards-wrapper">
                            @forelse ($dayItems as $item)
                                @php
                                    $clsNameUpper = strtoupper($item->class?->name ?? '');
                                    $clsMajorRaw = $item->class?->major ?? '';
                                    $shortMajor = '';
                                    if (str_contains($clsNameUpper, 'PPLG') || str_contains(strtoupper($clsMajorRaw), 'PPLG')) {
                                        $shortMajor = 'PPLG';
                                    } elseif (str_contains($clsNameUpper, 'BCF') || str_contains(strtoupper($clsMajorRaw), 'BCF')) {
                                        $shortMajor = 'BCF';
                                    } elseif (str_contains($clsNameUpper, 'TPFL') || str_contains(strtoupper($clsMajorRaw), 'TPFL')) {
                                        $shortMajor = 'TPFL';
                                    } elseif (str_contains($clsNameUpper, 'TO') || str_contains(strtoupper($clsMajorRaw), 'OTOMOTIF') || str_contains(strtoupper($clsMajorRaw), 'TO')) {
                                        $shortMajor = 'TO';
                                    } elseif (str_contains($clsNameUpper, 'ANIMASI') || str_contains(strtoupper($clsMajorRaw), 'ANIMASI')) {
                                        $shortMajor = 'Animasi';
                                    } else {
                                        $shortMajor = $clsMajorRaw;
                                    }
                                @endphp
                                <div class="grid-card sched-mini-card" data-class-id="{{ $item->class_id }}" data-major="{{ $shortMajor }}" data-major-full="{{ $clsMajorRaw }}">
                                    <div class="sched-card-time">
                                        {{ substr($item->start_time, 0, 5) }} - {{ substr($item->end_time, 0, 5) }}
                                    </div>
                                    <div class="sched-card-subject">
                                        {{ $item->subject?->name ?? '-' }}
                                    </div>
                                    <div class="sched-card-meta">
                                        <span>Kelas: <strong>{{ $item->class?->name ?? '-' }}</strong></span>
                                        @if ($shortMajor)
                                            <span class="badge badge-purple" style="font-size:10px; padding:1px 6px;">
                                                {{ $shortMajor }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="sched-card-teacher">
                                        Guru: {{ $item->teacher?->name ?? '-' }}
                                    </div>
                                    @if ($item->roomModel || $item->room)
                                        <div style="margin-top:4px;">
                                            <span class="badge badge-code" style="font-size:10px; padding:1.5px 6px;">
                                                {{ $item->roomModel?->name ?? $item->room }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Tombol Aksi Ubah & Hapus -->
                                    <div class="sched-card-actions">
                                        <button type="button" class="btn secondary" style="padding:3px 7px; font-size:11px; flex:1; justify-content:center;" onclick="openEditScheduleModal({{ json_encode([
                                            'id' => $item->id,
                                            'day' => strtolower($item->day_of_week ?? $item->day),
                                            'start_time' => substr($item->start_time, 0, 5),
                                            'end_time' => substr($item->end_time, 0, 5),
                                            'subject_id' => $item->subject_id,
                                            'teacher_id' => $item->teacher_id,
                                            'class_id' => $item->class_id,
                                            'room' => $item->roomModel?->name ?? $item->room ?? '',
                                            'academic_year_id' => $item->academic_year_id,
                                        ]) }})" title="Ubah Jadwal Ini">
                                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <form method="POST" action="{{ route('admin.schedules.destroy', $item->id) }}" class="inline" style="flex:1;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn danger" style="padding:3px 7px; font-size:11px; width:100%; justify-content:center;" onclick="return triggerDeleteEffect(event, this, 'Jadwal {{ addslashes($item->subject?->name ?? 'Pelajaran') }} (Kelas {{ addslashes($item->class?->name ?? '-') }})')" title="Hapus Jadwal Ini">
                                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="day-empty-box">
                                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" style="margin-bottom:4px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span style="font-size:11.5px; font-weight:600; color:#64748b;">Belum ada jadwal</span>
                                    <button type="button" onclick="openScheduleModalWithDay('{{ $dayKey }}')" class="btn-day-empty-add">
                                        + Tambah Pelajaran
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .schedules-filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .schedules-filter-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .sched-filter-group {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .sched-filter-group label {
            margin: 0;
            font-size: 11.5px;
            font-weight: 700;
            color: #475569;
            white-space: nowrap;
        }

        .sched-select {
            width: auto !important;
            padding: 6px 12px !important;
            font-size: 12px !important;
            font-weight: 700;
            border: 1.5px solid #d5dfec !important;
            border-radius: 8px !important;
            background: #ffffff;
            color: #0f172a;
            cursor: pointer;
            height: 32px;
        }

        .btn-today-pill {
            background: #eff6ff;
            color: #2563eb;
            border: 1.5px solid #dbeafe;
            border-radius: 8px;
            padding: 5px 11px;
            height: 32px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
            transition: all 0.16s ease;
        }

        .btn-today-pill:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        .btn-today-pill:hover .today-dot {
            background: #ffffff;
        }

        .today-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #2563eb;
            display: inline-block;
        }

        /* 5 KOLOM SEJAJAR RAPI (SENIN - JUMAT) & TIDAK PANJANG KEBAWAH */
        .weekly-schedule-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 10px;
            align-items: start;
        }

        .grid-day-column {
            background: #ffffff;
            border: 1.5px solid #e5ebf3;
            border-radius: 11px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        }

        .grid-day-column.is-today-col {
            border-color: #93c5fd;
            box-shadow: 0 2px 10px rgba(37, 99, 235, 0.1);
        }

        .day-col-header {
            background: #1e3a5f;
            color: #ffffff;
            padding: 9px 11px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 6px;
        }

        .day-col-header.is-today-head {
            background: #2563eb;
        }

        .day-col-name {
            font-weight: 800;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .day-col-count {
            font-size: 10.5px;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 1.5px 6px;
            border-radius: 99px;
            font-weight: 700;
        }

        .btn-day-quick-add {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.28);
            border-radius: 6px;
            padding: 3px 8px;
            font-size: 10.5px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.16s ease;
        }

        .btn-day-quick-add:hover {
            background: rgba(255, 255, 255, 0.28);
        }

        .day-cards-wrapper {
            padding: 9px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            background: #f8fafd;
            max-height: 440px;
            overflow-y: auto;
        }

        /* MODE 1 HARI: KARTU BERJEJER KE SAMPING (TIDAK PANJANG KEBAWAH) */
        .weekly-schedule-grid.single-day-mode {
            grid-template-columns: 1fr;
        }

        .weekly-schedule-grid.single-day-mode .day-cards-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(235px, 1fr));
            gap: 10px;
            max-height: none;
            overflow-y: visible;
            padding: 12px;
        }

        .weekly-schedule-grid.single-day-mode .day-empty-box {
            grid-column: 1 / -1;
        }

        .sched-mini-card {
            background: #ffffff;
            border: 1px solid #e5ebf3;
            border-left: 3.5px solid #2563eb;
            border-radius: 9px;
            padding: 9px 10px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
        }

        .sched-card-time {
            font-size: 11px;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 2px;
            font-family: 'JetBrains Mono', monospace;
        }

        .sched-card-subject {
            font-weight: 800;
            font-size: 12.5px;
            color: #0f172a;
            line-height: 1.3;
        }

        .sched-card-meta {
            font-size: 11.5px;
            color: #334155;
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 5px;
            flex-wrap: wrap;
        }

        .sched-card-teacher {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sched-card-actions {
            margin-top: 8px;
            padding-top: 7px;
            border-top: 1px dashed #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 6px;
        }

        .day-empty-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 8px;
            color: #94a3b8;
            text-align: center;
        }

        .btn-day-empty-add {
            margin-top: 7px;
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #dbeafe;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.16s ease;
        }

        .btn-day-empty-add:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }

        @media (max-width: 1180px) {
            .weekly-schedule-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 760px) {
            .weekly-schedule-grid {
                grid-template-columns: 1fr;
            }
            .day-cards-wrapper {
                max-height: none;
            }
        }
    </style>

    <!-- ============================================================
         RESPONSIVE MODAL POP-UP JADWAL (PRAKTIS & MUDAH DIISI)
         ============================================================ -->
    <div id="scheduleModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
        <div id="scheduleModalCard" style="background:#ffffff; width:min(100%, 540px); border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); overflow:hidden; max-height:92vh; display:flex; flex-direction:column;">
            <!-- Modal Header -->
            <div style="background:linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); color:#ffffff; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 id="scheduleModalTitle" style="margin:0; font-size:15px; font-weight:700; color:#ffffff;">Tambah Jadwal Pelajaran</h3>
                </div>
                <button type="button" onclick="closeScheduleModal()" style="background:rgba(255,255,255,0.15); border:none; color:#ffffff; font-size:18px; line-height:1; cursor:pointer; width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'" title="Tutup Modal">&times;</button>
            </div>

            <!-- Modal Form -->
            <form id="scheduleModalForm" method="POST" action="{{ route('admin.schedules.store') }}" style="padding:20px; overflow-y:auto; flex:1;">
                @csrf
                <div id="scheduleMethodSpoof"></div>

                <div style="display:flex; flex-direction:column; gap:14px;">
                    <!-- Hari Mengajar -->
                    <div>
                        <label for="modalScheduleDay">Hari Mengajar <span style="color:#dc2626;">*</span></label>
                        <select id="modalScheduleDay" name="day" required autofocus>
                            <option value="">-- Pilih Hari --</option>
                            <option value="monday">Senin</option>
                            <option value="tuesday">Selasa</option>
                            <option value="wednesday">Rabu</option>
                            <option value="thursday">Kamis</option>
                            <option value="friday">Jumat</option>
                        </select>
                    </div>

                    <!-- Jam Mulai & Jam Selesai -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div>
                            <label for="modalScheduleStart">Jam Mulai <span style="color:#dc2626;">*</span></label>
                            <input type="time" id="modalScheduleStart" name="start_time" required>
                        </div>
                        <div>
                            <label for="modalScheduleEnd">Jam Selesai <span style="color:#dc2626;">*</span></label>
                            <input type="time" id="modalScheduleEnd" name="end_time" required>
                        </div>
                    </div>

                    <!-- Mata Pelajaran -->
                    <div>
                        <label for="modalScheduleSubject">Mata Pelajaran <span style="color:#dc2626;">*</span></label>
                        <select id="modalScheduleSubject" name="subject_id" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">
                                    {{ $subject->name }} {{ $subject->code ? '('.$subject->code.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Guru Pengajar -->
                    <div>
                        <label for="modalScheduleTeacher">Guru Pengajar <span style="color:#dc2626;">*</span></label>
                        <select id="modalScheduleTeacher" name="teacher_id" required>
                            <option value="">-- Pilih Guru Pengajar --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->name }} {{ $teacher->nip ? '(NIP: '.$teacher->nip.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <span style="font-size:11px; color:#64748b; margin-top:4px; display:block;">💡 Tips: 1 Guru hanya dapat mengajar di 1 kelas/ruangan pada jam KBM yang sama agar tidak bentrok fisik.</span>
                    </div>

                    <!-- Kelas & Ruangan -->
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <div>
                            <label for="modalScheduleClass">Rombel Kelas <span style="color:#dc2626;">*</span></label>
                            <select id="modalScheduleClass" name="class_id" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">
                                        {{ $class->name }} {{ $class->major ? '('.$class->major.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="modalScheduleRoom">Ruangan Belajar</label>
                            <input type="text" id="modalScheduleRoom" name="room" list="roomsDatalist" placeholder="Misal: Ruang 10, Lab Komputer..." autocomplete="off">
                            <datalist id="roomsDatalist">
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->name }}">{{ $room->name }}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <!-- Tahun Akademik Hidden/Default -->
                    @if ($activeYear)
                        <input type="hidden" name="academic_year_id" value="{{ $activeYear->id }}">
                    @endif
                </div>

                <!-- Modal Actions -->
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:16px; border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn secondary" onclick="closeScheduleModal()" style="padding:9px 16px;">
                        Batal
                    </button>
                    <button type="submit" id="scheduleModalSubmitBtn" class="btn" style="padding:9px 20px;">
                        Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Animations & Script Logic -->
    <style>
        @keyframes modalBackdropFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalSpringPop {
            0% {
                opacity: 0;
                transform: scale(0.92) translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-active #scheduleModalBackdrop {
            display: flex !important;
            animation: modalBackdropFadeIn 0.18s ease-out;
        }

        .modal-active #scheduleModalCard {
            animation: modalSpringPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>

    <script>
        const scheduleBackdrop = document.getElementById('scheduleModalBackdrop');
        const scheduleModalForm = document.getElementById('scheduleModalForm');
        const scheduleModalTitle = document.getElementById('scheduleModalTitle');
        const scheduleModalSubmitBtn = document.getElementById('scheduleModalSubmitBtn');
        const scheduleMethodSpoof = document.getElementById('scheduleMethodSpoof');

        const inputDay = document.getElementById('modalScheduleDay');
        const inputStart = document.getElementById('modalScheduleStart');
        const inputEnd = document.getElementById('modalScheduleEnd');
        const inputSubject = document.getElementById('modalScheduleSubject');
        const inputTeacher = document.getElementById('modalScheduleTeacher');
        const inputClass = document.getElementById('modalScheduleClass');
        const inputRoom = document.getElementById('modalScheduleRoom');

        // Buka Modal Tambah Baru
        function openScheduleModal() {
            scheduleModalTitle.innerText = 'Tambah Jadwal Pelajaran';
            scheduleModalSubmitBtn.innerText = 'Simpan Jadwal';
            scheduleModalSubmitBtn.disabled = false;
            scheduleModalForm.action = "{{ route('admin.schedules.store') }}";
            scheduleMethodSpoof.innerHTML = '';

            inputDay.value = (currentGridDay && currentGridDay !== 'all') ? currentGridDay : '{{ $defaultDay }}';
            inputStart.value = '07:30';
            inputEnd.value = '09:00';
            inputSubject.value = '';
            inputTeacher.value = '';
            inputClass.value = '';
            inputRoom.value = '';

            document.body.classList.add('modal-active');
            scheduleBackdrop.style.display = 'flex';
            setTimeout(() => inputDay.focus(), 100);
        }

        // Buka Modal Tambah Baru dengan Hari yang Sudah Otomatis Dipilih
        function openScheduleModalWithDay(dayKey) {
            openScheduleModal();
            if (inputDay) {
                inputDay.value = dayKey;
            }
            const dayNames = {
                'monday': 'Senin',
                'tuesday': 'Selasa',
                'wednesday': 'Rabu',
                'thursday': 'Kamis',
                'friday': 'Jumat'
            };
            const name = dayNames[dayKey] || dayKey;
            scheduleModalTitle.innerText = 'Tambah Jadwal di Hari ' + name;
        }

        // Buka Modal Ubah / Edit
        function openEditScheduleModal(schedule) {
            scheduleModalTitle.innerText = 'Ubah Jadwal Pelajaran';
            scheduleModalSubmitBtn.innerText = 'Perbarui Jadwal';
            scheduleModalSubmitBtn.disabled = false;
            scheduleModalForm.action = "/admin/jadwal/" + schedule.id;
            scheduleMethodSpoof.innerHTML = '@method("PUT")';

            inputDay.value = schedule.day || '';
            inputStart.value = schedule.start_time || '';
            inputEnd.value = schedule.end_time || '';
            inputSubject.value = schedule.subject_id || '';
            inputTeacher.value = schedule.teacher_id || '';
            inputClass.value = schedule.class_id || '';
            inputRoom.value = schedule.room || '';

            document.body.classList.add('modal-active');
            scheduleBackdrop.style.display = 'flex';
            setTimeout(() => inputStart.focus(), 100);
        }

        // Tutup Modal
        function closeScheduleModal() {
            document.body.classList.remove('modal-active');
            scheduleBackdrop.style.display = 'none';
        }

        scheduleBackdrop.addEventListener('click', (e) => {
            if (e.target === scheduleBackdrop) {
                closeScheduleModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && scheduleBackdrop.style.display === 'flex') {
                closeScheduleModal();
            }
        });

        // Anti double-click protection
        scheduleModalForm.addEventListener('submit', () => {
            if (scheduleModalSubmitBtn) {
                scheduleModalSubmitBtn.disabled = true;
                scheduleModalSubmitBtn.innerText = 'Menyimpan...';
            }
        });

        // Combined Filter (Hari, Jurusan, Kelas)
        let currentGridClass = 'all';
        let currentGridDay = '{{ $defaultDay }}';
        let currentGridMajor = 'all';
        const todayKey = '{{ $todayKey }}';

        function filterGridClass(classId) {
            currentGridClass = classId;
            applyCombinedGridFilter();
        }

        function filterGridDay(dayKey) {
            currentGridDay = dayKey;
            const daySelect = document.getElementById('gridDayFilter');
            if (daySelect && daySelect.value !== dayKey) {
                daySelect.value = dayKey;
            }
            applyCombinedGridFilter();
        }

        function filterGridMajor(majorKey) {
            currentGridMajor = majorKey;
            applyCombinedGridFilter();
        }

        function filterToToday() {
            const validDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            const targetDay = validDays.includes(todayKey) ? todayKey : 'monday';
            filterGridDay(targetDay);
        }

        function applyCombinedGridFilter() {
            const gridContainer = document.getElementById('weeklyScheduleGrid');
            if (gridContainer) {
                gridContainer.classList.toggle('single-day-mode', currentGridDay !== 'all');
            }

            const columns = document.querySelectorAll('.grid-day-column');
            let totalVisibleCards = 0;

            columns.forEach(col => {
                const colDay = col.getAttribute('data-day');
                const isDayMatch = (currentGridDay === 'all' || colDay === currentGridDay);

                if (isDayMatch) {
                    col.style.display = 'flex';
                    col.style.opacity = '1';
                    if (currentGridDay === 'all') {
                        col.style.borderColor = (colDay === todayKey) ? '#93c5fd' : '#e5ebf3';
                        col.style.boxShadow = (colDay === todayKey) ? '0 2px 10px rgba(37, 99, 235, 0.12)' : '0 1px 3px rgba(15, 23, 42, 0.04)';
                    } else {
                        col.style.borderColor = '#93c5fd';
                        col.style.boxShadow = '0 4px 14px rgba(37, 99, 235, 0.1)';
                    }
                } else {
                    col.style.display = 'none';
                }

                // Filter cards inside column by class and major
                const cards = col.querySelectorAll('.grid-card');
                let colVisibleCount = 0;
                cards.forEach(card => {
                    const cardClass = card.getAttribute('data-class-id');
                    const cardMajor = (card.getAttribute('data-major') || '').trim().toLowerCase();
                    const cardMajorFull = (card.getAttribute('data-major-full') || '').trim().toLowerCase();
                    const targetMajor = currentGridMajor.toLowerCase();

                    const isClassMatch = (currentGridClass === 'all' || cardClass === currentGridClass);
                    const isMajorMatch = (
                        currentGridMajor === 'all'
                        || cardMajor === targetMajor
                        || cardMajorFull.includes(targetMajor)
                    );

                    if (isClassMatch && isMajorMatch && isDayMatch) {
                        card.style.display = 'block';
                        colVisibleCount++;
                        totalVisibleCards++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                const countBadge = col.querySelector('.day-col-count');
                if (countBadge) {
                    countBadge.textContent = colVisibleCount;
                }
            });

            const countText = document.getElementById('gridScheduleCountText');
            if (countText) {
                const dayNames = {
                    'monday': 'Senin',
                    'tuesday': 'Selasa',
                    'wednesday': 'Rabu',
                    'thursday': 'Kamis',
                    'friday': 'Jumat'
                };
                if (currentGridDay === 'all') {
                    countText.textContent = `Menampilkan ${totalVisibleCards} Jadwal untuk Semua Hari (Senin - Jumat)`;
                } else {
                    const displayDay = dayNames[currentGridDay] || currentGridDay;
                    countText.textContent = `Menampilkan ${totalVisibleCards} Jadwal untuk hari ${displayDay}`;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const dayFilterEl = document.getElementById('gridDayFilter');
            if (dayFilterEl && dayFilterEl.value) {
                currentGridDay = dayFilterEl.value;
            }
            applyCombinedGridFilter();
        });
    </script>
@endsection

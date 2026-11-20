@extends('layouts.app')

@section('title', 'Dashboard Siswa | Jadwal Skanic')
@section('page-title', 'Dashboard Siswa')

@section('content')
    <!-- Simple & Elegant Schedule Welcome Banner (Tanpa Data Pribadi Siswa) -->
    <div class="schedule-welcome-banner">
        <div class="welcome-badge">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Jadwal Mata Pelajaran
        </div>
        <h2>Jadwal Pelajaran Kelas {{ $student?->class?->name ?? 'Saya' }}</h2>
        <p>Pantau kegiatan belajar mengajar harian, jam pelajaran, penempatan ruang belajar, serta guru pengampu mata pelajaran secara tepat waktu.</p>
    </div>

    <!-- Card Jadwal Pelajaran Interaktif dengan Filter Hari -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 id="dashboardScheduleTitle" style="margin:0; font-size:16px; font-weight:800; color:#0f172a; display:flex; align-items:center; gap:8px;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#0284c7;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pelajaran Hari Ini
                </h3>
                <span id="dashboardScheduleSubtitle" style="font-size:12px; color:#64748b; font-weight:600; margin-top:2px; display:block;">
                    Hari Ini: {{ now()->translatedFormat('l, d F Y') }}
                </span>
            </div>
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <span id="dashboardScheduleBadge" class="badge badge-purple" style="font-size:12.5px; padding:5px 12px; font-weight:700;">
                    Kelas: {{ $student?->class?->name ?? 'Kelas Saya' }}
                </span>
                @if ($student?->jurusan ?? $student?->class?->major)
                    <span class="badge badge-blue" style="font-size:12px; padding:4px 10px; font-weight:700;">
                        {{ $student?->jurusan ?? $student?->class?->major }}
                    </span>
                @endif
                <a href="{{ route('student.schedule') }}" class="btn secondary" style="font-size:12.5px; padding:6px 14px; display:inline-flex; align-items:center; gap:4px;">
                    Lihat Lengkap &rarr;
                </a>
            </div>
        </div>

        <!-- Filter Control Bar (Dropdown & Animated Day Pills) -->
        <div class="dashboard-filter-toolbar">
            <div class="filter-dropdown-group">
                <label for="dashboardDaySelect" class="filter-label">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    PILIH HARI:
                </label>
                <div class="custom-select-wrapper">
                    <select id="dashboardDaySelect" class="day-filter-dropdown" onchange="filterDashboardSchedule(this.value)">
                        <option value="all">Semua Hari (Senin - Jumat)</option>
                        <option value="monday">Senin</option>
                        <option value="tuesday">Selasa</option>
                        <option value="wednesday">Rabu</option>
                        <option value="thursday">Kamis</option>
                        <option value="friday">Jumat</option>
                    </select>
                </div>
            </div>

            <!-- Quick Day Pills -->
            <div class="day-pills-container" id="dashboardDayPills">
                <button type="button" class="day-pill" data-day="all" onclick="filterDashboardSchedule('all')">
                    Semua Hari
                </button>
                <button type="button" class="day-pill" data-day="monday" onclick="filterDashboardSchedule('monday')">
                    Senin
                </button>
                <button type="button" class="day-pill" data-day="tuesday" onclick="filterDashboardSchedule('tuesday')">
                    Selasa
                </button>
                <button type="button" class="day-pill" data-day="wednesday" onclick="filterDashboardSchedule('wednesday')">
                    Rabu
                </button>
                <button type="button" class="day-pill" data-day="thursday" onclick="filterDashboardSchedule('thursday')">
                    Kamis
                </button>
                <button type="button" class="day-pill" data-day="friday" onclick="filterDashboardSchedule('friday')">
                    Jumat
                </button>
            </div>
        </div>

        <div class="card-body" style="padding-top:10px;">
            <div class="table-responsive">
                <table id="dashboardScheduleTable">
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">No.</th>
                            <th style="width:110px;">Hari</th>
                            <th style="width:140px;">Jam</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengajar</th>
                            <th>Ruangan</th>
                        </tr>
                    </thead>
                    <tbody id="dashboardScheduleTbody">
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
                            $itemsToDisplay = ($allSchedules && $allSchedules->count() > 0) ? $allSchedules : $todaySchedules;
                        @endphp
                        @forelse ($itemsToDisplay as $schedule)
                            @php
                                $dayKey = strtolower($schedule->day_of_week ?? $schedule->day);
                                $dayName = $dayConfigs[$dayKey] ?? ucfirst($dayKey);
                                $effectiveTeacher = $schedule->effectiveTeacherForDate($todayDate);
                                $isSubstituted = $effectiveTeacher->id !== $schedule->teacher_id;
                                $isTodayRow = ($dayKey === $todayDayKey);
                            @endphp
                            <tr class="dash-schedule-row" data-day="{{ $dayKey }}" data-is-today="{{ $isTodayRow ? '1' : '0' }}">
                                <td class="col-number dash-cell-index" style="text-align:center;">{{ $loop->iteration }}</td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <span class="day-badge day-badge-{{ $dayKey }}">{{ $dayName }}</span>
                                        @if ($isTodayRow)
                                            <span class="badge-mini-today">Hari Ini</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-code">
                                        {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <strong style="color:#0f172a; font-size:13.5px;">{{ $schedule->subject?->name ?? '-' }}</strong>
                                        @if ($schedule->subject?->code)
                                            <span class="badge badge-code" style="font-size:11px;">{{ $schedule->subject->code }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($isSubstituted)
                                        <div style="font-weight:700; color:#1e40af;">{{ $effectiveTeacher->name }}</div>
                                        <span class="badge badge-blue" style="font-size:10px; margin-top:2px;">Guru Inval Hari Ini</span>
                                    @else
                                        <span style="color:#1e293b; font-weight:600;">{{ $schedule->teacher?->name ?? '-' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-blue" style="font-weight:700;">
                                        {{ $schedule->roomModel?->name ?? $schedule->room ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr id="dashInitialEmpty">
                                <td colspan="6" style="text-align:center; padding:36px; color:#64748b; font-weight:600;">
                                    Belum ada jadwal pelajaran untuk kelas Anda.
                                </td>
                            </tr>
                        @endforelse

                        <!-- Empty state row for filtered out days -->
                        <tr id="dashFilteredEmptyRow" style="display:none;">
                            <td colspan="6" style="padding:40px 20px; text-align:center; background:#f8fafc;">
                                <div class="empty-state-box">
                                    <div class="empty-icon-circle">
                                        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h4 id="dashEmptyTitle" style="margin:10px 0 4px; font-size:15px; font-weight:800; color:#0f172a;">
                                        Tidak Ada Pelajaran
                                    </h4>
                                    <p id="dashEmptySubtitle" style="margin:0; font-size:12.5px; color:#64748b;">
                                        Tidak ada jadwal pelajaran terdaftar pada hari ini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Styles & Animations -->
    <style>
        .schedule-welcome-banner {
            background: linear-gradient(135deg, #072a4d 0%, #0a3a6b 60%, #0284c7 100%);
            border-radius: 14px;
            padding: 22px 26px;
            color: #ffffff;
            margin-bottom: 22px;
            box-shadow: 0 4px 18px -2px rgba(2, 132, 199, 0.15);
            position: relative;
            overflow: hidden;
        }

        .schedule-welcome-banner .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(4px);
            color: #e0f2fe;
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .schedule-welcome-banner h2 {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.3px;
            margin-bottom: 6px;
            color: #ffffff;
        }

        .schedule-welcome-banner p {
            font-size: 13px;
            color: #bae6fd;
            margin: 0;
            max-width: 720px;
            line-height: 1.5;
        }

        .dashboard-filter-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 12px 20px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .filter-dropdown-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-label {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 0;
            font-size: 11.5px;
            font-weight: 800;
            color: #475569;
            letter-spacing: 0.5px;
        }

        .custom-select-wrapper {
            position: relative;
        }

        .day-filter-dropdown {
            background: #ffffff;
            color: #0f172a;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 7px 34px 7px 12px;
            font-size: 13px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            outline: none;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%230284c7' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .day-filter-dropdown:hover {
            border-color: #0284c7;
        }

        .day-filter-dropdown:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.2);
        }

        .day-pills-container {
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .day-pill {
            background: #ffffff;
            color: #475569;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .day-pill:hover {
            color: #0284c7;
            border-color: #bae6fd;
            background: #f0f9ff;
            transform: translateY(-1px);
        }

        .day-pill.active {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            color: #ffffff;
            border-color: #0284c7;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);
            transform: translateY(-1px);
        }

        .day-badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .day-badge-monday { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .day-badge-tuesday { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .day-badge-wednesday { background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4; }
        .day-badge-thursday { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .day-badge-friday { background: #faf5ff; color: #7e22ce; border: 1px solid #e9d5ff; }
        .day-badge-saturday { background: #fdf2f8; color: #9d174d; border: 1px solid #fbcfe8; }

        .badge-mini-today {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            font-size: 9.5px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            box-shadow: 0 1px 4px rgba(16, 185, 129, 0.25);
        }

        @keyframes rowStaggerIn {
            0% {
                opacity: 0;
                transform: translateY(12px) scale(0.99);
            }
            70% {
                transform: translateY(-1px) scale(1.002);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .row-animating {
            animation: rowStaggerIn 0.32s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .dash-schedule-row {
            transition: background 0.15s ease;
        }

        .dash-schedule-row:hover {
            background: #f0f9ff;
        }

        .empty-state-box {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            animation: emptyBoxPop 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes emptyBoxPop {
            0% {
                opacity: 0;
                transform: scale(0.92) translateY(8px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .empty-icon-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #f0f9ff;
            color: #0284c7;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #bae6fd;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.15);
        }
    </style>

    <script>
        const dashDayMap = {
            'all': 'Semua Hari',
            'today': 'Hari Ini',
            'monday': 'Senin',
            'tuesday': 'Selasa',
            'wednesday': 'Rabu',
            'thursday': 'Kamis',
            'friday': 'Jumat'
        };

        const currentDayKey = '{{ $todayDayKey }}';

        function filterDashboardSchedule(selectedDay) {
            const dropdown = document.getElementById('dashboardDaySelect');
            if (dropdown && dropdown.value !== selectedDay) {
                dropdown.value = selectedDay;
            }

            // Sync pills
            document.querySelectorAll('#dashboardDayPills .day-pill').forEach(pill => {
                if (pill.getAttribute('data-day') === selectedDay) {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            });

            // Target day key
            const effectiveTarget = (selectedDay === 'today') ? currentDayKey : selectedDay;

            // Filter rows
            const rows = document.querySelectorAll('.dash-schedule-row');
            const emptyRow = document.getElementById('dashFilteredEmptyRow');
            const subtitle = document.getElementById('dashboardScheduleSubtitle');
            const emptyTitle = document.getElementById('dashEmptyTitle');
            const emptySubtitle = document.getElementById('dashEmptySubtitle');

            let visibleCount = 0;

            rows.forEach((row) => {
                const rowDay = row.getAttribute('data-day');
                const isMatch = (effectiveTarget === 'all' || rowDay === effectiveTarget);

                if (isMatch) {
                    visibleCount++;
                    row.style.display = '';
                    row.classList.remove('row-animating');
                    void row.offsetWidth;
                    row.classList.add('row-animating');
                    row.style.animationDelay = (visibleCount * 0.035) + 's';

                    const indexCell = row.querySelector('.dash-cell-index');
                    if (indexCell) {
                        indexCell.textContent = visibleCount;
                    }
                } else {
                    row.style.display = 'none';
                    row.classList.remove('row-animating');
                }
            });

            // Empty state check
            if (visibleCount === 0 && rows.length > 0) {
                if (emptyRow) emptyRow.style.display = '';
                const displayDayName = dashDayMap[selectedDay] || selectedDay;
                if (emptyTitle) {
                    emptyTitle.textContent = `Tidak Ada Pelajaran ${displayDayName}`;
                }
                if (emptySubtitle) {
                    emptySubtitle.textContent = `Tidak ada jadwal pelajaran terdaftar pada hari ${displayDayName} untuk kelas Anda.`;
                }
            } else {
                if (emptyRow) emptyRow.style.display = 'none';
            }

            // Update subtitle
            if (subtitle) {
                    subtitle.textContent = `Menampilkan jadwal hari ${dashDayMap[selectedDay] || selectedDay} (${visibleCount} pelajaran)`;
            }
        }

        // Initialize to current day (if Monday-Friday) or 'monday' by default
        document.addEventListener('DOMContentLoaded', () => {
            const validDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            const initialDay = validDays.includes(currentDayKey) ? currentDayKey : 'monday';
            filterDashboardSchedule(initialDay);
        });
    </script>
@endsection

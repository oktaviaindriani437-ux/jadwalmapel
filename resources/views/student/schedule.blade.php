@extends('layouts.app')

@section('title', 'Jadwal Saya | Jadwal Skanic')
@section('page-title', 'Jadwal Pelajaran Kelas')

@section('content')
    <div class="card">
        <!-- Header -->
        <div class="card-header">
            <div>
                <h3 style="margin:0; font-size:16px; font-weight:800; color:#0f172a; display:flex; align-items:center; gap:8px;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#0284c7;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Jadwal Mingguan
                </h3>
                <span id="scheduleStatusText" style="font-size:12px; color:#64748b; font-weight:600; margin-top:2px; display:block;">
                    Memuat jadwal pelajaran untuk kelas Anda
                </span>
            </div>
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <span id="activeScheduleCount" class="badge badge-purple" style="font-size:12.5px; padding:5px 12px; font-weight:700; transition:all 0.25s ease;">
                    Kelas: {{ $student->class?->name ?? 'Kelas Saya' }}
                </span>
                @if ($student->jurusan ?? $student->class?->major)
                    <span class="badge badge-blue" style="font-size:12.5px; padding:5px 12px; font-weight:700;">
                        Jurusan: {{ $student->jurusan ?? $student->class?->major }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Filter Control Bar (Dropdown & Animated Day Pills) -->
        <div class="schedule-filter-toolbar">
            <div class="filter-dropdown-group">
                <label for="dayFilterSelect" class="filter-label">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    PILIH HARI:
                </label>
                <div class="custom-select-wrapper">
                    <select id="dayFilterSelect" class="day-filter-dropdown" onchange="filterSchedule(this.value)">
                        <option value="all">Semua Hari (Senin - Jumat)</option>
                        <option value="monday">Senin</option>
                        <option value="tuesday">Selasa</option>
                        <option value="wednesday">Rabu</option>
                        <option value="thursday">Kamis</option>
                        <option value="friday">Jumat</option>
                    </select>
                </div>
                <button type="button" class="btn-quick-today" onclick="filterToToday()" title="Langsung lihat pelajaran hari ini">
                    <span class="pulse-dot"></span>
                    Hari Ini
                </button>
            </div>

            <!-- Quick Day Pills -->
            <div class="day-pills-container" id="dayPillsList">
                <button type="button" class="day-pill active" data-day="all" onclick="filterSchedule('all')">
                    Semua
                </button>
                <button type="button" class="day-pill" data-day="monday" onclick="filterSchedule('monday')">
                    Senin
                </button>
                <button type="button" class="day-pill" data-day="tuesday" onclick="filterSchedule('tuesday')">
                    Selasa
                </button>
                <button type="button" class="day-pill" data-day="wednesday" onclick="filterSchedule('wednesday')">
                    Rabu
                </button>
                <button type="button" class="day-pill" data-day="thursday" onclick="filterSchedule('thursday')">
                    Kamis
                </button>
                <button type="button" class="day-pill" data-day="friday" onclick="filterSchedule('friday')">
                    Jumat
                </button>
            </div>
        </div>

        <!-- Tabel Jadwal -->
        <div class="card-body" style="padding-top:10px;">
            <div class="table-responsive">
                <table id="studentScheduleTable">
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
                    <tbody id="studentScheduleTbody">
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
                        @endphp
                        @forelse ($schedules as $schedule)
                            @php
                                $dayKey = strtolower($schedule->day_of_week ?? $schedule->day);
                                $dayName = $dayConfigs[$dayKey] ?? ucfirst($dayKey);
                                $effectiveTeacher = $schedule->effectiveTeacherForDate($todayDate);
                                $isSubstituted = $effectiveTeacher->id !== $schedule->teacher_id;
                                $isTodayRow = ($dayKey === $todayDayKey);
                            @endphp
                            <tr class="schedule-item-row" data-day="{{ $dayKey }}" data-is-today="{{ $isTodayRow ? '1' : '0' }}">
                                <td class="col-number cell-index" style="text-align:center;">{{ $loop->iteration }}</td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <span class="day-badge day-badge-{{ $dayKey }}">{{ $dayName }}</span>
                                        @if ($isTodayRow)
                                            <span class="badge-mini-today">Hari Ini</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-code" style="letter-spacing:0.3px;">
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
                            <tr id="initialEmptyRow">
                                <td colspan="6" style="text-align:center; padding:36px; color:#64748b; font-weight:600;">
                                    Belum ada jadwal pelajaran untuk kelas ini.
                                </td>
                            </tr>
                        @endforelse

                        <!-- Empty State Dynamic Container (Shown when a filtered day has no rows) -->
                        <tr id="filteredEmptyRow" style="display:none;">
                            <td colspan="6" style="padding:40px 20px; text-align:center; background:#f8fafc;">
                                <div class="empty-state-box">
                                    <div class="empty-icon-circle">
                                        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h4 id="emptyDayTitle" style="margin:10px 0 4px; font-size:15px; font-weight:800; color:#0f172a;">
                                        Tidak Ada Pelajaran
                                    </h4>
                                    <p id="emptyDaySubtitle" style="margin:0; font-size:12.5px; color:#64748b;">
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

    <!-- STYLES & ANIMATIONS -->
    <style>
        .schedule-filter-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 12px 18px;
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
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

        .btn-quick-today {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f9ff;
            color: #0284c7;
            border: 1.5px solid #bae6fd;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-quick-today:hover {
            background: #0284c7;
            color: #ffffff;
            border-color: #0284c7;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.3);
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulseGreen 1.6s infinite cubic-bezier(0.66, 0, 0, 1);
        }

        @keyframes pulseGreen {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
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

        /* ROW ANIMATION & EFFECT */
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

        .schedule-item-row {
            transition: background 0.15s ease, transform 0.15s ease;
        }

        .schedule-item-row:hover {
            background: #f0f9ff;
        }

        /* HIGHLIGHT PULSE EFFECT FOR TODAY */
        .schedule-item-row[data-is-today="1"] {
            position: relative;
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

        @keyframes countBump {
            0% { transform: scale(1); }
            50% { transform: scale(1.12); }
            100% { transform: scale(1); }
        }

        .count-bumped {
            animation: countBump 0.25s ease-out;
        }
    </style>

    <!-- INTERACTIVE JAVASCRIPT LOGIC -->
    <script>
        const dayMap = {
            'all': 'Semua Hari',
            'monday': 'Senin',
            'tuesday': 'Selasa',
            'wednesday': 'Rabu',
            'thursday': 'Kamis',
            'friday': 'Jumat'
        };

        const todayKey = '{{ $todayDayKey }}';

        function filterSchedule(selectedDay) {
            const dropdown = document.getElementById('dayFilterSelect');
            if (dropdown && dropdown.value !== selectedDay) {
                dropdown.value = selectedDay;
            }

            // Sync pills
            document.querySelectorAll('#dayPillsList .day-pill').forEach(pill => {
                if (pill.getAttribute('data-day') === selectedDay) {
                    pill.classList.add('active');
                } else {
                    pill.classList.remove('active');
                }
            });

            // Filter rows
            const rows = document.querySelectorAll('.schedule-item-row');
            const emptyFilteredRow = document.getElementById('filteredEmptyRow');
            const statusText = document.getElementById('scheduleStatusText');
            const countBadge = document.getElementById('activeScheduleCount');
            const emptyDayTitle = document.getElementById('emptyDayTitle');
            const emptyDaySubtitle = document.getElementById('emptyDaySubtitle');

            let visibleCount = 0;

            rows.forEach((row, index) => {
                const rowDay = row.getAttribute('data-day');
                const isMatch = (selectedDay === 'all' || rowDay === selectedDay);

                if (isMatch) {
                    visibleCount++;
                    row.style.display = '';
                    row.classList.remove('row-animating');
                    // Trigger reflow for animation restart
                    void row.offsetWidth;
                    row.classList.add('row-animating');
                    row.style.animationDelay = (visibleCount * 0.035) + 's';

                    // Update index number
                    const indexCell = row.querySelector('.cell-index');
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
                if (emptyFilteredRow) emptyFilteredRow.style.display = '';
                if (emptyDayTitle) {
                    emptyDayTitle.textContent = `Tidak Ada Pelajaran Hari ${dayMap[selectedDay] || selectedDay}`;
                }
                if (emptyDaySubtitle) {
                    emptyDaySubtitle.textContent = `Tidak ada jadwal pelajaran yang tercatat pada hari ${dayMap[selectedDay] || selectedDay} untuk kelas Anda.`;
                }
            } else {
                if (emptyFilteredRow) emptyFilteredRow.style.display = 'none';
            }

            // Status message
            if (statusText) {
                if (selectedDay === 'all') {
                    statusText.textContent = `Menampilkan seluruh ${visibleCount} jadwal pelajaran mingguan`;
                } else {
                    statusText.textContent = `Menampilkan jadwal hari ${dayMap[selectedDay] || selectedDay} (${visibleCount} pelajaran)`;
                }
            }

            // Bump count badge
            if (countBadge) {
                countBadge.classList.remove('count-bumped');
                void countBadge.offsetWidth;
                countBadge.classList.add('count-bumped');
            }
        }

        function filterToToday() {
            if (dayMap[todayKey]) {
                filterSchedule(todayKey);
            } else {
                filterSchedule('monday');
            }
        }

        // Initialize with default or today
        document.addEventListener('DOMContentLoaded', () => {
            filterSchedule('all');
        });
    </script>
@endsection

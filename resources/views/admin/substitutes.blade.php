@extends('layouts.app')

@section('title', 'Guru Inval | Jadwal Skanic')
@section('page-title', 'Manajemen Guru Pengganti (Inval)')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert-success">
            {{ $message }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <strong style="display:block; margin-bottom:4px;">Gagal Memproses Data Guru Inval:</strong>
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Riwayat & Daftar Guru Inval (Pop-up Pattern Sesuai Permintaan) -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3>Daftar Guru Pengganti (Inval)</h3>
                <span style="font-size:12px; color:#64748b; font-weight:700;">Total: {{ $logs->count() }} Penugasan</span>
            </div>
            <!-- Tombol Trigger Modal Pop-up -->
            <button type="button" class="btn" onclick="openSubstituteModal()">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                + Tugaskan Guru Inval
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">No.</th>
                            <th style="width:120px;">Tanggal</th>
                            <th>Jadwal / Jam</th>
                            <th>Kelas</th>
                            <th>Guru Asli</th>
                            <th>Guru Pengganti (Inval)</th>
                            <th>Alasan</th>
                            <th style="width:110px; text-align:center;">Status</th>
                            <th style="width:190px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="col-number">{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge badge-code">{{ $log->date->translatedFormat('d M Y') }}</span>
                                </td>
                                <td>
                                    <strong>{{ $log->schedule?->subject?->name ?? '-' }}</strong>
                                    <div style="font-size:11.5px; color:#64748b; font-family:'JetBrains Mono', monospace;">
                                        {{ substr($log->schedule?->start_time ?? '', 0, 5) }} - {{ substr($log->schedule?->end_time ?? '', 0, 5) }}
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $log->schedule?->class?->name ?? '-' }}</strong>
                                </td>
                                <td>{{ $log->originalTeacher?->name ?? '-' }}</td>
                                <td>
                                    <strong style="color:#1d4ed8;">{{ $log->substituteTeacher?->name ?? '-' }}</strong>
                                </td>
                                <td>
                                    <span style="font-size:12.5px; color:#475569;">{{ $log->notes ?? '-' }}</span>
                                </td>
                                <td style="text-align:center;">
                                    @if ($log->status === 'approved')
                                        <span class="badge badge-green">Disetujui</span>
                                    @elseif ($log->status === 'pending')
                                        <span class="badge badge-amber">Menunggu</span>
                                    @else
                                        <span class="badge" style="background:#fee2e2; color:#991b1b; border-color:#fca5a5;">Ditolak</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:flex; justify-content:center; align-items:center; gap:4px; flex-wrap:wrap;">
                                        @if ($log->status !== 'approved')
                                            <form method="POST" action="{{ route('admin.substitutes.update-status', $log->id) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn success" style="padding:4px 7px; font-size:11px;" title="Setujui Penugasan">
                                                    ACC
                                                </button>
                                            </form>
                                        @endif

                                        @if ($log->status !== 'rejected')
                                            <form method="POST" action="{{ route('admin.substitutes.update-status', $log->id) }}" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn secondary" style="padding:4px 7px; font-size:11px;" title="Tolak Penugasan">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Tombol Ubah / Edit Modal -->
                                        <button type="button" class="btn secondary" style="padding:4px 7px; font-size:11px;" onclick="openEditSubstituteModal({{ json_encode([
                                            'id' => $log->id,
                                            'schedule_id' => $log->schedule_id,
                                            'date' => $log->date->format('Y-m-d'),
                                            'substitute_teacher_id' => $log->substitute_teacher_id,
                                            'status' => $log->status,
                                            'notes' => $log->notes ?? '',
                                        ]) }})" title="Ubah Penugasan Inval">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <!-- Tombol Hapus dengan Animated Effect -->
                                        <form method="POST" action="{{ route('admin.substitutes.destroy', $log->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn danger" style="padding:4px 7px; font-size:11px;" onclick="return triggerDeleteEffect(event, this, 'Penugasan Guru Inval {{ addslashes($log->substituteTeacher?->name ?? "Guru") }}')" title="Hapus Penugasan">
                                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center; padding:36px; color:#64748b; font-weight:600;">
                                    Belum ada catatan guru pengganti (inval). Klik "+ Tugaskan Guru Inval" untuk menambah penugasan baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================
         RESPONSIVE MODAL POP-UP GURU INVAL
         ============================================================ -->
    <div id="substituteModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
        <div id="substituteModalCard" style="background:#ffffff; width:min(100%, 540px); border-radius:14px; border:1px solid #e2e8f0; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); overflow:hidden; max-height:92vh; display:flex; flex-direction:column;">
            <!-- Modal Header -->
            <div style="background:linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color:#ffffff; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3 id="substituteModalTitle" style="margin:0; font-size:15px; font-weight:700; color:#ffffff;">Tugaskan Guru Inval Baru</h3>
                </div>
                <button type="button" onclick="closeSubstituteModal()" style="background:rgba(255,255,255,0.15); border:none; color:#ffffff; font-size:18px; line-height:1; cursor:pointer; width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'" title="Tutup Modal">&times;</button>
            </div>

            <!-- Modal Form -->
            <form id="substituteModalForm" method="POST" action="{{ route('admin.substitutes.store') }}" style="padding:20px; overflow-y:auto; flex:1;">
                @csrf
                <div id="substituteMethodSpoof"></div>

                <div style="display:flex; flex-direction:column; gap:14px;">
                    <!-- Pilih Jadwal Mengajar -->
                    <div>
                        <label for="modalScheduleId">Pilih Jadwal Mengajar & Guru Berhalangan <span style="color:#dc2626;">*</span></label>
                        <select id="modalScheduleId" name="schedule_id" required autofocus>
                            <option value="">-- Pilih Jadwal & Guru yang Berhalangan --</option>
                            @foreach ($schedules as $s)
                                <option value="{{ $s->id }}" {{ old('schedule_id') == $s->id ? 'selected' : '' }}>
                                    [{{ ucfirst($s->day_of_week ?? $s->day) }} {{ substr($s->start_time, 0, 5) }}-{{ substr($s->end_time, 0, 5) }}] {{ $s->subject?->name }} (Kelas {{ $s->class?->name }}) - Guru: {{ $s->teacher?->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <!-- Tanggal Penggantian -->
                        <div>
                            <label for="modalDate">Tanggal Penggantian <span style="color:#dc2626;">*</span></label>
                            <input type="date" id="modalDate" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="modalStatus">Status Penugasan <span style="color:#dc2626;">*</span></label>
                            <select id="modalStatus" name="status" required>
                                <option value="approved">Langsung Setujui (Approved)</option>
                                <option value="pending">Menunggu Persetujuan (Pending)</option>
                                <option value="rejected">Ditolak (Rejected)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Guru Pengganti (Inval) -->
                    <div>
                        <label for="modalSubstituteTeacherId">Guru Pengganti (Inval) <span style="color:#dc2626;">*</span></label>
                        <select id="modalSubstituteTeacherId" name="substitute_teacher_id" required>
                            <option value="">-- Pilih Guru Pengganti --</option>
                            @foreach ($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('substitute_teacher_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} ({{ $t->nip ?? 'Tanpa NIP' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Keterangan / Alasan -->
                    <div>
                        <label for="modalNotes">Keterangan / Alasan Berhalangan</label>
                        <input type="text" id="modalNotes" name="notes" placeholder="Misal: Sakit, Tugas dinas luar sekolah, Cuti..." value="{{ old('notes') }}">
                    </div>
                </div>

                <!-- Modal Actions -->
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:16px; border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn secondary" onclick="closeSubstituteModal()" style="padding:9px 16px;">
                        Batal
                    </button>
                    <button type="submit" id="substituteModalSubmitBtn" class="btn" style="padding:9px 20px;">
                        Simpan Penugasan
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

        .modal-active #substituteModalBackdrop {
            display: flex !important;
            animation: modalBackdropFadeIn 0.18s ease-out;
        }

        .modal-active #substituteModalCard {
            animation: modalSpringPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>

    <script>
        const substituteBackdrop = document.getElementById('substituteModalBackdrop');
        const substituteModalForm = document.getElementById('substituteModalForm');
        const substituteModalTitle = document.getElementById('substituteModalTitle');
        const substituteModalSubmitBtn = document.getElementById('substituteModalSubmitBtn');
        const substituteMethodSpoof = document.getElementById('substituteMethodSpoof');

        const inputScheduleId = document.getElementById('modalScheduleId');
        const inputDate = document.getElementById('modalDate');
        const inputStatus = document.getElementById('modalStatus');
        const inputSubTeacherId = document.getElementById('modalSubstituteTeacherId');
        const inputNotes = document.getElementById('modalNotes');

        // Buka Modal Tambah Baru
        function openSubstituteModal() {
            substituteModalTitle.innerText = 'Tugaskan Guru Pengganti (Inval) Baru';
            substituteModalSubmitBtn.innerText = 'Simpan Penugasan';
            substituteModalForm.action = "{{ route('admin.substitutes.store') }}";
            substituteMethodSpoof.innerHTML = '';

            inputScheduleId.value = '';
            inputDate.value = "{{ now()->format('Y-m-d') }}";
            inputStatus.value = 'approved';
            inputSubTeacherId.value = '';
            inputNotes.value = '';

            document.body.classList.add('modal-active');
            substituteBackdrop.style.display = 'flex';
            setTimeout(() => inputScheduleId.focus(), 100);
        }

        // Buka Modal Ubah / Edit
        function openEditSubstituteModal(substitute) {
            substituteModalTitle.innerText = 'Ubah Penugasan Guru Inval';
            substituteModalSubmitBtn.innerText = 'Perbarui Penugasan';
            substituteModalForm.action = "/admin/guru-inval/" + substitute.id;
            substituteMethodSpoof.innerHTML = '@method("PUT")';

            inputScheduleId.value = substitute.schedule_id || '';
            inputDate.value = substitute.date || "{{ now()->format('Y-m-d') }}";
            inputStatus.value = substitute.status || 'approved';
            inputSubTeacherId.value = substitute.substitute_teacher_id || '';
            inputNotes.value = substitute.notes || '';

            document.body.classList.add('modal-active');
            substituteBackdrop.style.display = 'flex';
            setTimeout(() => inputScheduleId.focus(), 100);
        }

        // Tutup Modal
        function closeSubstituteModal() {
            document.body.classList.remove('modal-active');
            substituteBackdrop.style.display = 'none';
        }

        substituteBackdrop.addEventListener('click', (e) => {
            if (e.target === substituteBackdrop) {
                closeSubstituteModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && substituteBackdrop.style.display === 'flex') {
                closeSubstituteModal();
            }
        });
    </script>
@endsection

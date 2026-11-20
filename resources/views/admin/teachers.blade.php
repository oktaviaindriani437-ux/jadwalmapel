@extends('layouts.app')

@section('title', 'Guru | Jadwal Skanic')
@section('page-title', 'Data Guru Pengajar')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert-success">
            {{ $message }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <strong style="display:block; margin-bottom:4px;">Terjadi Kesalahan:</strong>
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Top Action Bar -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
        <div>
            <h3 style="margin:0; font-size:16px; font-weight:800; color:#0f172a;">Kelola Tenaga Pendidik</h3>
            <span style="font-size:12px; color:#64748b; font-weight:600;">Daftar guru aktif, kode NIP, dan kontak komunikasi</span>
        </div>
        <button type="button" class="btn" onclick="openTeacherModal()" id="btnOpenCreateTeacher" style="padding:10px 18px; font-size:13.5px;">
            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Guru Pengajar
        </button>
    </div>

    <!-- Tabel Daftar Guru -->
    <div class="card">
        <div class="card-header">
            <h3>Daftar Guru</h3>
            <span style="font-size:12px; color:#64748b; font-weight:700;">Total: {{ $teachers->count() }} Guru</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">No.</th>
                            <th>Nama Lengkap Guru</th>
                            <th style="width:160px; text-align:center;">NIP / Kode</th>
                            <th style="width:160px;">No. Kontak</th>
                            <th style="width:140px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teachers as $teacher)
                            <tr>
                                <td class="col-number">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $teacher->name }}</strong>
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge badge-code">{{ $teacher->nip }}</span>
                                </td>
                                <td>{{ $teacher->phone ?? '-' }}</td>
                                <td style="text-align:center;">
                                    <div style="display:flex; justify-content:center; gap:6px;">
                                        <!-- Tombol Ubah / Edit -->
                                        <button type="button" class="btn secondary" style="padding:5px 9px; font-size:12px;" onclick="openEditTeacherModal({{ json_encode([
                                            'id' => $teacher->id,
                                            'name' => $teacher->name,
                                            'nip' => $teacher->nip,
                                            'phone' => $teacher->phone ?? '',
                                        ]) }})" title="Ubah Data Guru">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form method="POST" action="{{ route('admin.teachers.destroy', $teacher->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn danger" style="padding:5px 9px; font-size:12px;" onclick="return triggerDeleteEffect(event, this, 'Guru {{ addslashes($teacher->name) }}')">
                                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                <td colspan="5" style="text-align:center; padding:36px; color:#64748b; font-weight:600;">
                                    Belum ada data guru pengajar. Klik tombol "Tambah Guru Pengajar" di atas untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================
         RESPONSIVE MODAL POP-UP (DATA GURU)
         ============================================================ -->
    <div id="teacherModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
        <div id="teacherModalCard" style="background:#ffffff; width:min(100%, 480px); border:1px solid #e2e8f0; border-radius:14px; box-shadow:0 20px 45px -10px rgba(15,23,42,0.25); overflow:hidden;">
            <!-- Modal Header -->
            <div style="background:linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); color:#ffffff; padding:16px 20px; display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 id="teacherModalTitle" style="margin:0; font-size:15px; font-weight:800; color:#ffffff;">Tambah Guru Pengajar</h3>
                </div>
                <button type="button" onclick="closeTeacherModal()" style="background:transparent; border:none; color:#ffffff; font-size:22px; line-height:1; cursor:pointer; padding:0 4px; transition:transform 0.15s ease;" onmouseover="this.style.transform='rotate(90deg)'" onmouseout="this.style.transform='rotate(0deg)'" title="Tutup Modal">&times;</button>
            </div>

            <!-- Modal Form -->
            <form id="teacherModalForm" method="POST" action="{{ route('admin.teachers.store') }}" style="padding:22px;">
                @csrf
                <div id="teacherMethodSpoof"></div>

                <div style="display:flex; flex-direction:column; gap:16px;">
                    <!-- Nama Lengkap & Gelar -->
                    <div>
                        <label for="modalTeacherName">Nama Lengkap Guru <span style="color:#dc2626;">*</span></label>
                        <input type="text" id="modalTeacherName" name="name" placeholder="Misal: Drs. Budi Santoso, M.Pd / Bu Mutia" required autofocus>
                    </div>

                    <!-- NIP / Kode Guru -->
                    <div>
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                            <label for="modalTeacherNip" style="margin:0;">NIP / Kode Guru</label>
                            <button type="button" onclick="generateAutoNip()" style="background:#eff6ff; color:#2563eb; border:1px solid #dbeafe; border-radius:6px; padding:3px 9px; font-size:11px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:4px;" title="Klik untuk membuat kode unik baru">
                                ⚡ Buat Kode Otomatis
                            </button>
                        </div>
                        <input type="text" id="modalTeacherNip" name="nip" placeholder="Misal: MTK01 / G-006 / 19800101..." oninput="checkNipAvailability(this.value)">
                        <div id="nipDuplicateWarning" style="display:none; color:#dc2626; font-size:11.5px; font-weight:700; margin-top:5px; background:#fef2f2; border:1px solid #fecaca; border-radius:6px; padding:6px 10px;">
                            ⚠️ Kode NIP ini sudah digunakan oleh guru lain!
                        </div>
                        <span style="font-size:11.5px; color:#64748b; margin-top:4px; display:block;">
                            Bisa diisi NIP resmi, kode inisial pengajar, atau gunakan kode otomatis (bebas bentrok).
                        </span>
                    </div>

                    <!-- No. HP / Kontak -->
                    <div>
                        <label for="modalTeacherPhone">No. WhatsApp / Kontak (Opsional)</label>
                        <input type="text" id="modalTeacherPhone" name="phone" placeholder="Misal: 081234567890">
                    </div>
                </div>

                <!-- Modal Actions -->
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:22px; padding-top:16px; border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn secondary" onclick="closeTeacherModal()" style="padding:9px 16px;">
                        Batal
                    </button>
                    <button type="submit" id="teacherModalSubmitBtn" class="btn" style="padding:9px 20px;">
                        Simpan Guru
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

        .modal-active #teacherModalBackdrop {
            display: flex !important;
            animation: modalBackdropFadeIn 0.18s ease-out;
        }

        .modal-active #teacherModalCard {
            animation: modalSpringPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>

    <script>
        const teacherBackdrop = document.getElementById('teacherModalBackdrop');
        const teacherModalForm = document.getElementById('teacherModalForm');
        const teacherModalTitle = document.getElementById('teacherModalTitle');
        const teacherModalSubmitBtn = document.getElementById('teacherModalSubmitBtn');
        const teacherMethodSpoof = document.getElementById('teacherMethodSpoof');

        const inputTeacherName = document.getElementById('modalTeacherName');
        const inputTeacherNip = document.getElementById('modalTeacherNip');
        const inputTeacherPhone = document.getElementById('modalTeacherPhone');
        const nipWarningEl = document.getElementById('nipDuplicateWarning');

        const existingNips = @json($existingNips ?? []);
        let currentEditingTeacherId = null;

        function checkNipAvailability(val) {
            const trimmed = (val || '').trim();
            if (!trimmed) {
                if (nipWarningEl) nipWarningEl.style.display = 'none';
                if (teacherModalSubmitBtn) teacherModalSubmitBtn.disabled = false;
                return;
            }

            const match = existingNips[trimmed];
            if (match && match.id !== currentEditingTeacherId) {
                if (nipWarningEl) {
                    nipWarningEl.style.display = 'block';
                    nipWarningEl.innerHTML = `⚠️ Kode NIP "<strong>${trimmed}</strong>" sudah digunakan oleh guru: <strong>${match.name}</strong>. Silakan ubah atau klik tombol "⚡ Buat Kode Otomatis" di atas.`;
                }
                if (teacherModalSubmitBtn) teacherModalSubmitBtn.disabled = true;
            } else {
                if (nipWarningEl) nipWarningEl.style.display = 'none';
                if (teacherModalSubmitBtn) teacherModalSubmitBtn.disabled = false;
            }
        }

        function generateAutoNip() {
            let nextIndex = Object.keys(existingNips).length + 1;
            let candidate = 'G-' + String(nextIndex).padStart(3, '0');
            while (existingNips[candidate]) {
                nextIndex++;
                candidate = 'G-' + String(nextIndex).padStart(3, '0');
            }
            inputTeacherNip.value = candidate;
            checkNipAvailability(candidate);
        }

        function openTeacherModal() {
            currentEditingTeacherId = null;
            teacherModalTitle.innerText = 'Tambah Guru Pengajar Baru';
            teacherModalSubmitBtn.innerText = 'Simpan Guru';
            teacherModalSubmitBtn.disabled = false;
            teacherModalForm.action = "{{ route('admin.teachers.store') }}";
            teacherMethodSpoof.innerHTML = '';

            inputTeacherName.value = '';
            inputTeacherPhone.value = '';
            if (nipWarningEl) nipWarningEl.style.display = 'none';

            // Pre-fill next unique code automatically so user doesn't hit duplicate error
            generateAutoNip();

            document.body.classList.add('modal-active');
            teacherBackdrop.style.display = 'flex';
            setTimeout(() => inputTeacherName.focus(), 100);
        }

        function openEditTeacherModal(teacher) {
            currentEditingTeacherId = teacher.id;
            teacherModalTitle.innerText = 'Ubah Data Guru Pengajar';
            teacherModalSubmitBtn.innerText = 'Perbarui Guru';
            teacherModalSubmitBtn.disabled = false;
            teacherModalForm.action = "/admin/guru/" + teacher.id;
            teacherMethodSpoof.innerHTML = '@method("PUT")';

            inputTeacherName.value = teacher.name || '';
            inputTeacherNip.value = teacher.nip || '';
            inputTeacherPhone.value = teacher.phone || '';

            checkNipAvailability(teacher.nip || '');

            document.body.classList.add('modal-active');
            teacherBackdrop.style.display = 'flex';
            setTimeout(() => inputTeacherName.focus(), 100);
        }

        function closeTeacherModal() {
            document.body.classList.remove('modal-active');
            teacherBackdrop.style.display = 'none';
        }

        teacherBackdrop.addEventListener('click', (e) => {
            if (e.target === teacherBackdrop) {
                closeTeacherModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && teacherBackdrop.style.display === 'flex') {
                closeTeacherModal();
            }
        });

        // Anti double-click protection
        teacherModalForm.addEventListener('submit', () => {
            if (teacherModalSubmitBtn) {
                teacherModalSubmitBtn.disabled = true;
                teacherModalSubmitBtn.innerText = 'Menyimpan...';
            }
        });
    </script>
@endsection

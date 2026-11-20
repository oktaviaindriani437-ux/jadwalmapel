@extends('layouts.app')

@section('title', 'Mata Pelajaran | Jadwal Skanic')
@section('page-title', 'Mata Pelajaran')

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
            <h3 style="margin:0; font-size:16px; font-weight:800; color:#0f172a;">Kelola Kurikulum Pelajaran</h3>
            <span style="font-size:12px; color:#64748b; font-weight:600;">Atur mata pelajaran dan penugasan guru pengampu</span>
        </div>
        <button type="button" class="btn" onclick="openSubjectModal()" id="btnOpenCreateSubject" style="padding:10px 18px; font-size:13.5px;">
            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Mata Pelajaran
        </button>
    </div>

    <!-- Tabel Daftar Pelajaran -->
    <div class="card">
        <div class="card-header">
            <h3>Daftar Pelajaran</h3>
            <span style="font-size: 12px; color: #64748b; font-weight: 700;">Total: {{ $subjects->count() }} Mata Pelajaran</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">No.</th>
                            <th>Mata Pelajaran</th>
                            <th style="width:130px; text-align:center;">Kode</th>
                            <th>Guru Pengampu</th>
                            <th>Keterangan</th>
                            <th style="width:140px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr id="subject-row-{{ $subject->id }}">
                                <td class="col-number">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $subject->name }}</strong>
                                </td>
                                <td style="text-align:center;">
                                    @if ($subject->code)
                                        <span class="badge badge-code">{{ $subject->code }}</span>
                                    @else
                                        <span style="color:#94a3b8;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($subject->teacher)
                                        <strong style="color:#0f172a;">{{ $subject->teacher->name }}</strong>
                                        @if ($subject->teacher->nip)
                                            <span style="display:block; font-size:11px; color:#64748b; font-family:'JetBrains Mono', monospace;">
                                                NIP: {{ $subject->teacher->nip }}
                                            </span>
                                        @endif
                                    @else
                                        <span style="color:#94a3b8; font-style:italic;">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td><span style="color:#475569; font-size:13px;">{{ $subject->description ?? '-' }}</span></td>
                                <td style="text-align:center;">
                                    <div style="display:flex; justify-content:center; gap:6px;">
                                        <!-- Tombol Ubah / Edit -->
                                        <button type="button" class="btn secondary" style="padding:5px 9px; font-size:12px;" onclick="openEditSubjectModal({{ json_encode([
                                            'id' => $subject->id,
                                            'name' => $subject->name,
                                            'teacher_name' => $subject->teacher?->name ?? '',
                                            'code' => $subject->code ?? '',
                                            'description' => $subject->description ?? '',
                                        ]) }})" title="Ubah Mata Pelajaran">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form method="POST" action="{{ route('admin.subjects.destroy', $subject->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn danger" style="padding:5px 9px; font-size:12px;" onclick="return triggerDeleteEffect(event, this, 'Mata Pelajaran {{ addslashes($subject->name) }}')">
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
                                <td colspan="6" style="text-align:center; padding:36px; color:#64748b; font-weight:600;">
                                    Belum ada mata pelajaran. Klik tombol "+ Tambah Mata Pelajaran" di atas untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================
         RESPONSIVE MODAL POP-UP (ANTI AI SLOP, ZERO GRADIENT)
         ============================================================ -->
    <div id="subjectModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
        <div id="subjectModalCard" style="background:#ffffff; width:min(100%, 480px); border:1px solid #e2e8f0; border-radius:14px; box-shadow:0 20px 45px -10px rgba(15,23,42,0.25); overflow:hidden;">
            <!-- Modal Header -->
            <div style="background:linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); color:#ffffff; padding:16px 20px; display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 id="subjectModalTitle" style="margin:0; font-size:15px; font-weight:800; color:#ffffff;">Tambah Mata Pelajaran</h3>
                </div>
                <button type="button" onclick="closeSubjectModal()" style="background:transparent; border:none; color:#ffffff; font-size:22px; line-height:1; cursor:pointer; padding:0 4px; transition:transform 0.15s ease;" onmouseover="this.style.transform='rotate(90deg)'" onmouseout="this.style.transform='rotate(0deg)'" title="Tutup Modal">&times;</button>
            </div>

            <!-- Modal Form -->
            <form id="subjectModalForm" method="POST" action="{{ route('admin.subjects.store') }}" style="padding:22px;">
                @csrf
                <div id="subjectMethodSpoof"></div>

                <div style="display:flex; flex-direction:column; gap:16px;">
                    <!-- Nama Pelajaran -->
                    <div>
                        <label for="modalSubjectName">Nama Mata Pelajaran <span style="color:#dc2626;">*</span></label>
                        <input type="text" id="modalSubjectName" name="name" placeholder="Misal: Bahasa Inggris, Pemrograman Web" required autofocus>
                    </div>

                    <!-- Guru Pengampu (Bisa Diketik Langsung / Dipilih) -->
                    <div>
                        <label for="modalTeacherName">Guru Pengampu (Bisa Ketik Langsung)</label>
                        <input type="text" id="modalTeacherName" name="teacher_name" list="teachersDatalist" placeholder="Ketik nama guru atau pilih..." autocomplete="off">
                        <datalist id="teachersDatalist">
                            @foreach ($teachers as $t)
                                <option value="{{ $t->name }}">{{ $t->nip ? 'NIP: '.$t->nip : '' }}</option>
                            @endforeach
                        </datalist>
                        <span style="font-size:11.5px; color:#64748b; margin-top:4px; display:block;">
                            Ketik nama guru langsung (otomatis tersambung) atau pilih dari daftar yang ada.
                        </span>
                    </div>

                    <!-- Keterangan Singkat -->
                    <div>
                        <label for="modalSubjectDesc">Keterangan Singkat</label>
                        <input type="text" id="modalSubjectDesc" name="description" placeholder="Keterangan singkat pelajaran atau materi...">
                    </div>

                    <!-- Kode Pelajaran -->
                    <div>
                        <label for="modalSubjectCode">Kode Pelajaran (Opsional)</label>
                        <input type="text" id="modalSubjectCode" name="code" placeholder="Misal: ING-10 (Kosongkan untuk dibuat otomatis)">
                    </div>
                </div>

                <!-- Modal Actions -->
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:22px; padding-top:16px; border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn secondary" onclick="closeSubjectModal()" style="padding:9px 16px;">
                        Batal
                    </button>
                    <button type="submit" id="modalSubmitBtn" class="btn" style="padding:9px 20px;">
                        Simpan Pelajaran
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

        .modal-active #subjectModalBackdrop {
            display: flex !important;
            animation: modalBackdropFadeIn 0.18s ease-out;
        }

        .modal-active #subjectModalCard {
            animation: modalSpringPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>

    <script>
        const backdrop = document.getElementById('subjectModalBackdrop');
        const modalForm = document.getElementById('subjectModalForm');
        const modalTitle = document.getElementById('subjectModalTitle');
        const modalSubmitBtn = document.getElementById('modalSubmitBtn');
        const methodSpoof = document.getElementById('subjectMethodSpoof');

        const inputName = document.getElementById('modalSubjectName');
        const inputTeacher = document.getElementById('modalTeacherName');
        const inputDesc = document.getElementById('modalSubjectDesc');
        const inputCode = document.getElementById('modalSubjectCode');

        // Buka Modal Tambah Baru
        function openSubjectModal() {
            modalTitle.innerText = 'Tambah Mata Pelajaran Baru';
            modalSubmitBtn.innerText = 'Simpan Pelajaran';
            modalForm.action = "{{ route('admin.subjects.store') }}";
            methodSpoof.innerHTML = '';

            inputName.value = '';
            inputTeacher.value = '';
            inputDesc.value = '';
            inputCode.value = '';

            document.body.classList.add('modal-active');
            backdrop.style.display = 'flex';
            setTimeout(() => inputName.focus(), 100);
        }

        // Buka Modal Edit / Ubah
        function openEditSubjectModal(subject) {
            modalTitle.innerText = 'Ubah Mata Pelajaran';
            modalSubmitBtn.innerText = 'Perbarui Pelajaran';
            modalForm.action = "/admin/mata-pelajaran/" + subject.id;
            methodSpoof.innerHTML = '@method("PUT")';

            inputName.value = subject.name || '';
            inputTeacher.value = subject.teacher_name || '';
            inputDesc.value = subject.description || '';
            inputCode.value = subject.code || '';

            document.body.classList.add('modal-active');
            backdrop.style.display = 'flex';
            setTimeout(() => inputName.focus(), 100);
        }

        // Tutup Modal
        function closeSubjectModal() {
            document.body.classList.remove('modal-active');
            backdrop.style.display = 'none';
        }

        // Tutup jika klik backdrop di luar card
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                closeSubjectModal();
            }
        });

        // Tutup jika tekan tombol ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && backdrop.style.display === 'flex') {
                closeSubjectModal();
            }
        });
    </script>
@endsection

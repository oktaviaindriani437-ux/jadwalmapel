@extends('layouts.app')

@section('title', 'Siswa | Jadwal Skanic')
@section('page-title', 'Data Siswa')

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
            <h3 style="margin:0; font-size:16px; font-weight:800; color:#0f172a;">Kelola Peserta Didik</h3>
            <span style="font-size:12px; color:#64748b; font-weight:600;">Data siswa, nomor induk (NIS), rombel kelas, konsentrasi jurusan, dan akun portal</span>
        </div>
        <button type="button" class="btn" onclick="openStudentModal()" id="btnOpenCreateStudent" style="padding:10px 18px; font-size:13.5px;">
            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Siswa Baru
        </button>
    </div>

    <!-- Tabel Daftar Siswa -->
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h3>Daftar Siswa</h3>
                <span id="studentTableStatusCount" style="font-size:12px; color:#64748b; font-weight:700;">Total: {{ $students->count() }} Siswa</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <label for="studentMajorFilter" style="margin:0; font-size:12px; font-weight:800; color:#1e293b;">Filter Jurusan:</label>
                <select id="studentMajorFilter" onchange="filterStudentTable(this.value)" style="width:auto; padding:7px 14px; font-size:12.5px; font-weight:700; border:1.5px solid #bae6fd; border-radius:8px; background:#fff; cursor:pointer; color:#0c4a6e; outline:none;">
                    <option value="all">Semua Jurusan</option>
                    @foreach ($majors as $code => $majorLabel)
                        <option value="{{ $majorLabel }}">{{ $code }} - {{ $majorLabel }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">No.</th>
                            <th>Nama Siswa</th>
                            <th style="width:130px; text-align:center;">NIS</th>
                            <th style="width:130px;">Username</th>
                            <th>Kelas</th>
                            <th>Jurusan / Keahlian</th>
                            <th style="width:140px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        @forelse ($students as $student)
                            <tr class="student-item-row" data-major="{{ $student->jurusan ?? '' }}" data-class-id="{{ $student->class_id }}">
                                <td class="col-number student-cell-index">{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $student->user?->name ?? '-' }}</strong>
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge badge-code">{{ $student->nis }}</span>
                                </td>
                                <td><span style="color:#64748b; font-size:13px; font-family:'JetBrains Mono', monospace;">{{ $student->user?->username ?? '-' }}</span></td>
                                <td>
                                    <strong>{{ $student->class?->name ?? '-' }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-purple" style="font-weight:700;">
                                        {{ $student->jurusan ?? '-' }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:flex; justify-content:center; gap:6px;">
                                        <!-- Tombol Ubah / Edit -->
                                        <button type="button" class="btn secondary" style="padding:5px 9px; font-size:12px;" onclick="openEditStudentModal({{ json_encode([
                                            'id' => $student->id,
                                            'name' => $student->user?->name ?? '',
                                            'username' => $student->user?->username ?? '',
                                            'nis' => $student->nis,
                                            'class_id' => $student->class_id,
                                            'jurusan' => $student->jurusan ?? '',
                                        ]) }})" title="Ubah Data Siswa">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn danger" style="padding:5px 9px; font-size:12px;" onclick="return triggerDeleteEffect(event, this, 'Siswa {{ addslashes($student->user?->name ?? $student->nis) }}')">
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
                                <td colspan="7" style="text-align:center; padding:36px; color:#64748b; font-weight:600;">
                                    Belum ada data siswa. Klik tombol "Tambah Siswa Baru" di atas untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse

                        <!-- Filtered empty row -->
                        <tr id="studentFilteredEmptyRow" style="display:none;">
                            <td colspan="7" style="text-align:center; padding:36px; color:#64748b; font-weight:600; background:#f8fafc;">
                                Tidak ada siswa yang terdaftar pada jurusan yang dipilih.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================
         RESPONSIVE MODAL POP-UP (DATA SISWA)
         ============================================================ -->
    <div id="studentModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
        <div id="studentModalCard" style="background:#ffffff; width:min(100%, 520px); border:1px solid #e2e8f0; border-radius:14px; box-shadow:0 20px 45px -10px rgba(15,23,42,0.25); overflow:hidden; max-height:92vh; display:flex; flex-direction:column;">
            <!-- Modal Header -->
            <div style="background:linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color:#ffffff; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; flex-shrink:0;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 id="studentModalTitle" style="margin:0; font-size:15px; font-weight:800; color:#ffffff;">Tambah Siswa Baru</h3>
                </div>
                <button type="button" onclick="closeStudentModal()" style="background:transparent; border:none; color:#ffffff; font-size:22px; line-height:1; cursor:pointer; padding:0 4px; transition:transform 0.15s ease;" onmouseover="this.style.transform='rotate(90deg)'" onmouseout="this.style.transform='rotate(0deg)'" title="Tutup Modal">&times;</button>
            </div>

            <!-- Modal Form -->
            <form id="studentModalForm" method="POST" action="{{ route('admin.students.store') }}" style="padding:22px; overflow-y:auto; flex:1;">
                @csrf
                <div id="studentMethodSpoof"></div>

                <div style="display:flex; flex-direction:column; gap:14px;">
                    <!-- Nama Siswa -->
                    <div>
                        <label for="modalStudentName">Nama Lengkap Siswa <span style="color:#dc2626;">*</span></label>
                        <input type="text" id="modalStudentName" name="name" placeholder="Misal: Muhammad Rizki" required autofocus>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <!-- Username -->
                        <div>
                            <label for="modalStudentUsername">Username Login <span style="color:#dc2626;">*</span></label>
                            <input type="text" id="modalStudentUsername" name="username" placeholder="Username login" required>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="modalStudentPassword">Password <span id="pwdRequiredStar" style="color:#dc2626;">*</span></label>
                            <input type="password" id="modalStudentPassword" name="password" placeholder="Min. 6 karakter" required>
                            <span id="pwdHelpText" style="display:none; font-size:11px; color:#64748b; margin-top:2px;">Kosongkan jika tidak ingin mengubah password.</span>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                        <!-- NIS -->
                        <div>
                            <label for="modalStudentNis">Nomor Induk (NIS) <span style="color:#dc2626;">*</span></label>
                            <input type="text" id="modalStudentNis" name="nis" placeholder="Misal: 2024001" required>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label for="modalStudentClassId">Rombel Kelas <span style="color:#dc2626;">*</span></label>
                            <select id="modalStudentClassId" name="class_id" required onchange="handleStudentClassChange(this)" style="width:100%; padding:9px 12px; font-size:13px; font-weight:700; border:1.5px solid #cbd5e1; border-radius:8px; background:#fff; cursor:pointer;">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($classes as $c)
                                    <option value="{{ $c->id }}" data-major="{{ $c->major ?? '' }}">{{ $c->name }} ({{ $c->major ?? 'Umum' }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Jurusan (Opsi Dropdown Baku & Otomatis) -->
                    <div>
                        <label for="modalStudentJurusan">Jurusan / Konsentrasi Keahlian <span style="color:#dc2626;">*</span></label>
                        <select id="modalStudentJurusan" name="jurusan" required style="width:100%; padding:9px 12px; font-size:13px; font-weight:700; border:1.5px solid #cbd5e1; border-radius:8px; background:#fff; cursor:pointer;">
                            <option value="">-- Pilih Jurusan / Keahlian --</option>
                            @foreach ($majors as $code => $majorLabel)
                                <option value="{{ $majorLabel }}">{{ $code }} - {{ $majorLabel }}</option>
                            @endforeach
                        </select>
                        <span style="font-size:11.5px; color:#64748b; margin-top:4px; display:block;">
                            Otomatis terpilih sesuai kelas yang Anda pilih, atau dapat Anda sesuaikan secara manual.
                        </span>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:16px; border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn secondary" onclick="closeStudentModal()" style="padding:9px 16px;">
                        Batal
                    </button>
                    <button type="submit" id="studentModalSubmitBtn" class="btn" style="padding:9px 20px;">
                        Simpan Siswa
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

        .modal-active #studentModalBackdrop {
            display: flex !important;
            animation: modalBackdropFadeIn 0.18s ease-out;
        }

        .modal-active #studentModalCard {
            animation: modalSpringPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>

    <script>
        const studentBackdrop = document.getElementById('studentModalBackdrop');
        const studentModalForm = document.getElementById('studentModalForm');
        const studentModalTitle = document.getElementById('studentModalTitle');
        const studentModalSubmitBtn = document.getElementById('studentModalSubmitBtn');
        const studentMethodSpoof = document.getElementById('studentMethodSpoof');

        const inputStudentName = document.getElementById('modalStudentName');
        const inputStudentUsername = document.getElementById('modalStudentUsername');
        const inputStudentPassword = document.getElementById('modalStudentPassword');
        const inputStudentNis = document.getElementById('modalStudentNis');
        const inputStudentClassId = document.getElementById('modalStudentClassId');
        const inputStudentJurusan = document.getElementById('modalStudentJurusan');

        const pwdRequiredStar = document.getElementById('pwdRequiredStar');
        const pwdHelpText = document.getElementById('pwdHelpText');

        function openStudentModal() {
            studentModalTitle.innerText = 'Tambah Siswa Baru';
            studentModalSubmitBtn.innerText = 'Simpan Siswa';
            studentModalSubmitBtn.disabled = false;
            studentModalForm.action = "{{ route('admin.students.store') }}";
            studentMethodSpoof.innerHTML = '';

            inputStudentName.value = '';
            inputStudentUsername.value = '';
            inputStudentPassword.value = '';
            inputStudentPassword.required = true;
            pwdRequiredStar.style.display = 'inline';
            pwdHelpText.style.display = 'none';

            inputStudentNis.value = '';
            inputStudentClassId.value = '';
            inputStudentJurusan.value = '';

            document.body.classList.add('modal-active');
            studentBackdrop.style.display = 'flex';
            setTimeout(() => inputStudentName.focus(), 100);
        }

        function openEditStudentModal(student) {
            studentModalTitle.innerText = 'Ubah Data Siswa';
            studentModalSubmitBtn.innerText = 'Perbarui Siswa';
            studentModalSubmitBtn.disabled = false;
            studentModalForm.action = "/admin/siswa/" + student.id;
            studentMethodSpoof.innerHTML = '@method("PUT")';

            inputStudentName.value = student.name || '';
            inputStudentUsername.value = student.username || '';
            inputStudentPassword.value = '';
            inputStudentPassword.required = false;
            pwdRequiredStar.style.display = 'none';
            pwdHelpText.style.display = 'block';

            inputStudentNis.value = student.nis || '';
            inputStudentClassId.value = student.class_id || '';
            inputStudentJurusan.value = student.jurusan || '';

            document.body.classList.add('modal-active');
            studentBackdrop.style.display = 'flex';
            setTimeout(() => inputStudentName.focus(), 100);
        }

        function closeStudentModal() {
            document.body.classList.remove('modal-active');
            studentBackdrop.style.display = 'none';
        }

        function handleStudentClassChange(selectEl) {
            const opt = selectEl.options[selectEl.selectedIndex];
            const major = opt ? opt.getAttribute('data-major') : '';
            if (major && inputStudentJurusan) {
                inputStudentJurusan.value = major;
            }
        }

        studentBackdrop.addEventListener('click', (e) => {
            if (e.target === studentBackdrop) {
                closeStudentModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && studentBackdrop.style.display === 'flex') {
                closeStudentModal();
            }
        });

        studentModalForm.addEventListener('submit', () => {
            if (studentModalSubmitBtn) {
                studentModalSubmitBtn.disabled = true;
                studentModalSubmitBtn.innerText = 'Menyimpan...';
            }
        });

        function filterStudentTable(selectedMajor) {
            const rows = document.querySelectorAll('.student-item-row');
            const emptyRow = document.getElementById('studentFilteredEmptyRow');
            const statusCount = document.getElementById('studentTableStatusCount');
            let visibleCount = 0;

            rows.forEach(row => {
                const major = row.getAttribute('data-major');
                const isMatch = (selectedMajor === 'all' || major === selectedMajor);

                if (isMatch) {
                    visibleCount++;
                    row.style.display = '';
                    const cell = row.querySelector('.student-cell-index');
                    if (cell) cell.textContent = visibleCount;
                } else {
                    row.style.display = 'none';
                }
            });

            if (emptyRow) {
                emptyRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
            }

            if (statusCount) {
                statusCount.textContent = selectedMajor === 'all'
                    ? `Total: ${visibleCount} Siswa`
                    : `Menampilkan: ${visibleCount} Siswa (${selectedMajor})`;
            }
        }
    </script>
@endsection

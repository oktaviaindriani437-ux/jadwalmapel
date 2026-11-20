@extends('layouts.app')

@section('title', 'Kelas | Jadwal Skanic')
@section('page-title', 'Data Rombel Kelas')

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
            <h3 style="margin:0; font-size:16px; font-weight:800; color:#0f172a;">Kelola Rombongan Belajar</h3>
            <span style="font-size:12px; color:#64748b; font-weight:600;">Daftar kelas aktif, konsentrasi keahlian per jurusan, dan jumlah peserta didik</span>
        </div>
        <button type="button" class="btn" onclick="openClassModal()" id="btnOpenCreateClass" style="padding:10px 18px; font-size:13.5px;">
            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kelas / Rombel
        </button>
    </div>

    <!-- Tabel Daftar Kelas -->
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <div>
                <h3>Daftar Kelas</h3>
                <span id="classTableStatusCount" style="font-size:12px; color:#64748b; font-weight:700;">Total: {{ $classes->count() }} Kelas</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <label for="classMajorFilter" style="margin:0; font-size:12px; font-weight:800; color:#1e293b;">Filter Jurusan:</label>
                <select id="classMajorFilter" onchange="filterClassTable(this.value)" style="width:auto; padding:7px 12px; font-size:12.5px; font-weight:700; border:1.5px solid #cbd5e1; border-radius:8px; background:#fff; cursor:pointer;">
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
                            <th>Nama Rombel Kelas</th>
                            <th>Jurusan / Keahlian</th>
                            <th style="width:160px; text-align:center;">Jadwal Terdaftar</th>
                            <th style="width:140px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="classTableBody">
                        @forelse ($classes as $class)
                            <tr class="class-item-row" data-major="{{ $class->major ?? '' }}">
                                <td class="col-number class-cell-index">{{ $loop->iteration }}</td>
                                <td>
                                    <strong style="color:#0f172a; font-size:13.5px;">{{ $class->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge badge-purple" style="font-weight:700;">
                                        {{ $class->major ?? 'Umum' }}
                                    </span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge badge-blue">{{ $class->schedules->count() }} Jadwal</span>
                                </td>
                                <td style="text-align:center;">
                                    <div style="display:flex; justify-content:center; gap:6px;">
                                        <!-- Tombol Ubah / Edit -->
                                        <button type="button" class="btn secondary" style="padding:5px 9px; font-size:12px;" onclick="openEditClassModal({{ json_encode([
                                            'id' => $class->id,
                                            'name' => $class->name,
                                            'major' => $class->major ?? '',
                                        ]) }})" title="Ubah Data Kelas">
                                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Ubah
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form method="POST" action="{{ route('admin.classes.destroy', $class->id) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn danger" style="padding:5px 9px; font-size:12px;" onclick="return triggerDeleteEffect(event, this, 'Kelas {{ addslashes($class->name) }}')">
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
                                    Belum ada data rombel kelas. Klik tombol "Tambah Kelas / Rombel" di atas untuk menambahkan.
                                </td>
                            </tr>
                        @endforelse

                        <!-- Filtered empty row -->
                        <tr id="classFilteredEmptyRow" style="display:none;">
                            <td colspan="5" style="text-align:center; padding:36px; color:#64748b; font-weight:600; background:#f8fafc;">
                                Tidak ada kelas yang terdaftar pada jurusan yang dipilih.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================
         RESPONSIVE MODAL POP-UP (DATA KELAS)
         ============================================================ -->
    <div id="classModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px); z-index:9999; align-items:center; justify-content:center; padding:16px;">
        <div id="classModalCard" style="background:#ffffff; width:min(100%, 500px); border:1px solid #e2e8f0; border-radius:14px; box-shadow:0 20px 45px -10px rgba(15,23,42,0.25); overflow:hidden;">
            <!-- Modal Header -->
            <div style="background:linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); color:#ffffff; padding:16px 20px; display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 id="classModalTitle" style="margin:0; font-size:15px; font-weight:800; color:#ffffff;">Tambah Kelas Baru</h3>
                </div>
                <button type="button" onclick="closeClassModal()" style="background:transparent; border:none; color:#ffffff; font-size:22px; line-height:1; cursor:pointer; padding:0 4px; transition:transform 0.15s ease;" onmouseover="this.style.transform='rotate(90deg)'" onmouseout="this.style.transform='rotate(0deg)'" title="Tutup Modal">&times;</button>
            </div>

            <!-- Modal Form -->
            <form id="classModalForm" method="POST" action="{{ route('admin.classes.store') }}" style="padding:22px;">
                @csrf
                <div id="classMethodSpoof"></div>

                <div style="display:flex; flex-direction:column; gap:16px;">
                    <!-- Nama Rombel Kelas -->
                    <div>
                        <label for="modalClassName">Nama Rombel Kelas <span style="color:#dc2626;">*</span></label>
                        <input type="text" id="modalClassName" name="name" placeholder="Misal: XI PPLG 3, XI TO, XI ANIMASI" required autofocus>
                    </div>

                    <!-- Konsentrasi Keahlian / Jurusan (Opsi Dropdown Baku) -->
                    <div>
                        <label for="modalClassMajor">Konsentrasi Keahlian / Jurusan <span style="color:#dc2626;">*</span></label>
                        <select id="modalClassMajor" name="major" required style="width:100%; padding:9px 12px; font-size:13px; font-weight:700; border:1.5px solid #cbd5e1; border-radius:8px; background:#fff; cursor:pointer;">
                            <option value="">-- Pilih Jurusan / Keahlian --</option>
                            @foreach ($majors as $code => $majorLabel)
                                <option value="{{ $majorLabel }}">{{ $code }} - {{ $majorLabel }}</option>
                            @endforeach
                        </select>
                        <span style="font-size:11.5px; color:#64748b; margin-top:4px; display:block;">
                            Pilih jurusan resmi untuk kelas ini agar otomatis tersinkron ke jadwal pelajaran.
                        </span>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:22px; padding-top:16px; border-top:1px solid #e2e8f0;">
                    <button type="button" class="btn secondary" onclick="closeClassModal()" style="padding:9px 16px;">
                        Batal
                    </button>
                    <button type="submit" id="classModalSubmitBtn" class="btn" style="padding:9px 20px;">
                        Simpan Kelas
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

        .modal-active #classModalBackdrop {
            display: flex !important;
            animation: modalBackdropFadeIn 0.18s ease-out;
        }

        .modal-active #classModalCard {
            animation: modalSpringPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>

    <script>
        const classBackdrop = document.getElementById('classModalBackdrop');
        const classModalForm = document.getElementById('classModalForm');
        const classModalTitle = document.getElementById('classModalTitle');
        const classModalSubmitBtn = document.getElementById('classModalSubmitBtn');
        const classMethodSpoof = document.getElementById('classMethodSpoof');

        const inputClassName = document.getElementById('modalClassName');
        const inputClassMajor = document.getElementById('modalClassMajor');

        function openClassModal() {
            classModalTitle.innerText = 'Tambah Kelas Baru';
            classModalSubmitBtn.innerText = 'Simpan Kelas';
            classModalSubmitBtn.disabled = false;
            classModalForm.action = "{{ route('admin.classes.store') }}";
            classMethodSpoof.innerHTML = '';

            inputClassName.value = '';
            inputClassMajor.value = '';

            document.body.classList.add('modal-active');
            classBackdrop.style.display = 'flex';
            setTimeout(() => inputClassName.focus(), 100);
        }

        function openEditClassModal(classItem) {
            classModalTitle.innerText = 'Ubah Data Kelas / Rombel';
            classModalSubmitBtn.innerText = 'Perbarui Kelas';
            classModalSubmitBtn.disabled = false;
            classModalForm.action = "/admin/kelas/" + classItem.id;
            classMethodSpoof.innerHTML = '@method("PUT")';

            inputClassName.value = classItem.name || '';
            inputClassMajor.value = classItem.major || '';

            document.body.classList.add('modal-active');
            classBackdrop.style.display = 'flex';
            setTimeout(() => inputClassName.focus(), 100);
        }

        function closeClassModal() {
            document.body.classList.remove('modal-active');
            classBackdrop.style.display = 'none';
        }

        classBackdrop.addEventListener('click', (e) => {
            if (e.target === classBackdrop) {
                closeClassModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && classBackdrop.style.display === 'flex') {
                closeClassModal();
            }
        });

        classModalForm.addEventListener('submit', () => {
            if (classModalSubmitBtn) {
                classModalSubmitBtn.disabled = true;
                classModalSubmitBtn.innerText = 'Menyimpan...';
            }
        });

        function filterClassTable(selectedMajor) {
            const rows = document.querySelectorAll('.class-item-row');
            const emptyRow = document.getElementById('classFilteredEmptyRow');
            const statusCount = document.getElementById('classTableStatusCount');
            let visibleCount = 0;

            rows.forEach(row => {
                const major = row.getAttribute('data-major');
                const isMatch = (selectedMajor === 'all' || major === selectedMajor);

                if (isMatch) {
                    visibleCount++;
                    row.style.display = '';
                    const cell = row.querySelector('.class-cell-index');
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
                    ? `Total: ${visibleCount} Kelas`
                    : `Menampilkan: ${visibleCount} Kelas (${selectedMajor})`;
            }
        }
    </script>
@endsection

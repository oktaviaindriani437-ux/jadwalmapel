@extends('layouts.app')

@section('title', 'Profil Siswa | Jadwal Skanic')
@section('page-title', 'Profil Saya')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert-success">
            {{ $message }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <strong style="display:block; margin-bottom:4px;">Gagal Memperbarui Profil Siswa:</strong>
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:20px; align-items:start;">
        <!-- Card 1: Data Akademik & Identitas Siswa -->
        <div class="card">
            <div class="card-header">
                <h3>Profil Saya & Data Akademik</h3>
                <span class="badge badge-green">Siswa Aktif</span>
            </div>
            <div class="card-body">
                <div style="display:flex; align-items:center; gap:16px; margin-bottom:20px; padding-bottom:18px; border-bottom:2px solid #e2e8f0;">
                    <div class="avatar" style="width:60px; height:60px; font-size:24px; border:2px solid #bae6fd; flex-shrink:0;">
                        {{ strtoupper(substr($student?->user?->name ?? auth()->user()->name ?? 'S', 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="margin:0; font-size:18px; font-weight:800; color:#0f172a;">{{ $student?->user?->name ?? auth()->user()->name }}</h3>
                        <div style="display:flex; align-items:center; gap:8px; margin-top:5px; flex-wrap:wrap;">
                            <span class="badge badge-code">NIS: {{ $student?->nis ?? '-' }}</span>
                            <span class="badge badge-blue">Kelas {{ $student?->class?->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="margin-bottom:18px;">
                    <table>
                        <tbody>
                            <tr>
                                <th style="width:160px; background:#f8fafc; color:#334155; font-size:12px;">Nama Lengkap</th>
                                <td><strong>{{ $student?->user?->name ?? auth()->user()->name }}</strong></td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Username Akun</th>
                                <td><span class="badge badge-code">{{ $student?->user?->username ?? auth()->user()->username }}</span></td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Nomor Induk (NIS)</th>
                                <td><span class="badge badge-code">{{ $student?->nis ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Rombel Kelas</th>
                                <td><strong style="color:#1d4ed8;">{{ $student?->class?->name ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Konsentrasi / Jurusan</th>
                                <td>{{ $student?->jurusan ?? 'Belum ditentukan' }}</td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Status Pembelajaran</th>
                                <td><span class="badge badge-green">Aktif Mengikuti Pembelajaran</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <label style="margin-bottom:8px;">Fitur & Akses Pembelajaran Siswa:</label>
                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                        <span class="badge badge-blue">Jadwal Kelas Saya</span>
                        <span class="badge badge-blue">Pelajaran Hari Ini</span>
                        <span class="badge badge-blue">Daftar Mata Pelajaran</span>
                        <span class="badge badge-blue">Dewan Guru Pengampu</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Pengaturan Keamanan Kata Sandi Siswa -->
        <div class="card">
            <div class="card-header">
                <h3>Keamanan Akun Siswa</h3>
                <span style="font-size:12px; color:#64748b; font-weight:700;">Ganti Kata Sandi</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div style="display:flex; flex-direction:column; gap:14px;">
                        <div>
                            <label style="color:#0f172a; margin-bottom:8px;">Perbarui Kata Sandi Akun</label>
                            <span style="font-size:12px; color:#64748b; display:block; margin-bottom:14px;">
                                Jaga keamanan akun Anda dengan mengganti password secara berkala. Gunakan minimal 6 karakter.
                            </span>

                            <div style="display:flex; flex-direction:column; gap:12px;">
                                <div>
                                    <label for="studentNewPassword">Kata Sandi Baru <span style="color:#dc2626;">*</span></label>
                                    <input type="password" id="studentNewPassword" name="password" placeholder="Minimal 6 karakter" required>
                                </div>

                                <div>
                                    <label for="studentConfirmPassword">Konfirmasi Kata Sandi Baru <span style="color:#dc2626;">*</span></label>
                                    <input type="password" id="studentConfirmPassword" name="password_confirmation" placeholder="Ulangi kata sandi baru" required>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top:10px;">
                            <button type="submit" class="btn" style="width:100%; padding:10px 16px;">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Simpan Kata Sandi Baru
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

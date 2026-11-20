@extends('layouts.app')

@section('title', 'Profil Admin | Jadwal Skanic')
@section('page-title', 'Profil Akun')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert-success">
            {{ $message }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <strong style="display:block; margin-bottom:4px;">Gagal Memperbarui Profil:</strong>
            <ul style="margin:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:20px; align-items:start;">
        <!-- Card 1: Informasi Akun & Identitas -->
        <div class="card">
            <div class="card-header">
                <h3>Profil Akun Administrator</h3>
                <span class="badge badge-purple">Superadmin</span>
            </div>
            <div class="card-body">
                <div style="display:flex; align-items:center; gap:16px; margin-bottom:20px; padding-bottom:18px; border-bottom:2px solid #e2e8f0;">
                    <div class="avatar" style="width:60px; height:60px; font-size:24px; border:2px solid #bae6fd; flex-shrink:0;">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h3 style="margin:0; font-size:18px; font-weight:800; color:#0f172a;">{{ $user->name }}</h3>
                        <div style="display:flex; align-items:center; gap:8px; margin-top:5px; flex-wrap:wrap;">
                            <span class="badge badge-code">{{ $user->username }}</span>
                            <span class="badge badge-green">Akun Aktif</span>
                        </div>
                    </div>
                </div>

                <div class="table-responsive" style="margin-bottom:18px;">
                    <table>
                        <tbody>
                            <tr>
                                <th style="width:160px; background:#f8fafc; color:#334155; font-size:12px;">Nama Lengkap</th>
                                <td><strong>{{ $user->name }}</strong></td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Username</th>
                                <td><span class="badge badge-code">{{ $user->username }}</span></td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Alamat Email</th>
                                <td>{{ $user->email ?? 'Belum diisi' }}</td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Hak Akses</th>
                                <td><strong style="color:#2563eb;">Administrator Sistem (Penuh)</strong></td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Terdaftar Sejak</th>
                                <td>{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th style="background:#f8fafc; color:#334155; font-size:12px;">Terakhir Diperbarui</th>
                                <td>{{ $user->updated_at ? $user->updated_at->translatedFormat('d F Y, H:i') : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div>
                    <label style="margin-bottom:8px; display:block; font-size:12.5px; font-weight:700; color:#0f172a;">Hak & Wewenang Sistem:</label>
                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                        <span class="badge badge-blue">Kelola Jadwal & Grid</span>
                        <span class="badge badge-blue">Kelola Mata Pelajaran</span>
                        <span class="badge badge-blue">Kelola Data Guru</span>
                        <span class="badge badge-blue">Kelola Rombel Kelas</span>
                        <span class="badge badge-blue">Penugasan Guru Inval</span>
                        <span class="badge badge-green">Cetak Dokumen Resmi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Pengaturan Akun & Keamanan -->
        <div class="card">
            <div class="card-header">
                <h3>Pengaturan Akun & Keamanan</h3>
                <span style="font-size:12px; color:#64748b; font-weight:700;">Ganti Data / Password</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div style="display:flex; flex-direction:column; gap:14px;">
                        <div>
                            <label for="adminProfileName">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                            <input type="text" id="adminProfileName" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div>
                            <label for="adminProfileEmail">Alamat Email</label>
                            <input type="text" id="adminProfileEmail" name="email" value="{{ old('email', $user->email) }}" placeholder="admin@skanic.sch.id">
                        </div>

                        <div style="padding-top:10px; border-top:1.5px solid #e2e8f0;">
                            <label style="color:#0f172a; margin-bottom:10px; display:block; font-weight:700;">Ganti Kata Sandi (Opsional)</label>
                            <span style="font-size:12px; color:#64748b; display:block; margin-bottom:12px;">
                                Kosongkan kolom kata sandi jika Anda tidak ingin mengubah password akun saat ini.
                            </span>

                            <div style="display:flex; flex-direction:column; gap:12px;">
                                <div>
                                    <label for="adminNewPassword">Kata Sandi Baru</label>
                                    <input type="password" id="adminNewPassword" name="password" placeholder="Minimal 6 karakter">
                                </div>

                                <div>
                                    <label for="adminConfirmPassword">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" id="adminConfirmPassword" name="password_confirmation" placeholder="Ulangi kata sandi baru">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top:10px;">
                            <button type="submit" class="btn" style="width:100%; padding:10px 16px;">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan Akun
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

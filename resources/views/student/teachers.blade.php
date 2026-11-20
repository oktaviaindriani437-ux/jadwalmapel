@extends('layouts.app')

@section('title', 'Guru Pengajar | Jadwal Skanic')
@section('page-title', 'Daftar Guru Pengajar')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Dewan Guru</h3>
            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Total: {{ $teachers->count() }} Guru</span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">No.</th>
                            <th>Nama Lengkap Guru</th>
                            <th style="width:160px; text-align:center;">NIP / Kode</th>
                            <th>Mata Pelajaran yang Diampu</th>
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
                                    @if ($teacher->nip)
                                        <span class="badge badge-code">{{ $teacher->nip }}</span>
                                    @else
                                        <span style="color:#94a3b8;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                                        @forelse ($teacher->subjects as $subject)
                                            <span class="badge badge-purple">{{ $subject->name }}</span>
                                        @empty
                                            <span style="color:#94a3b8; font-size:12.5px;">-</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:32px; color:#64748b;">
                                    Belum ada data dewan guru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Pelajaran | Jadwal Skanic')
@section('page-title', 'Mata Pelajaran')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Daftar Pelajaran</h3>
            <span style="font-size: 12px; color: #64748b; font-weight: 600;">Total: {{ $subjects->count() }} Pelajaran</span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">No.</th>
                            <th>Mata Pelajaran</th>
                            <th style="width:120px; text-align:center;">Kode</th>
                            <th>Guru Pengampu</th>
                            <th>Jadwal Pelajaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr>
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
                                <td>{{ $subject->teacher?->name ?? '-' }}</td>
                                <td>
                                    <div style="display:flex; flex-direction:column; gap:4px;">
                                        @forelse($subject->schedules as $schedule)
                                            <div>
                                                <span class="badge badge-blue">
                                                    {{ ucfirst($schedule->day_of_week ?? $schedule->day) }}
                                                </span>
                                                <span class="badge badge-code" style="margin-left:4px;">
                                                    {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                                </span>
                                            </div>
                                        @empty
                                            <span style="color:#94a3b8; font-size:12.5px;">Belum dijadwalkan</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:32px; color:#64748b;">
                                    Belum ada mata pelajaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

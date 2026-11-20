<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ScheduleExportService
{
    /**
     * Build base query from filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getFilteredQuery(array $filters = []): Builder
    {
        return Schedule::query()
            ->with(['academicYear', 'class', 'subject', 'teacher', 'roomModel'])
            ->when(! empty($filters['academic_year_id']), fn ($q) => $q->where('academic_year_id', $filters['academic_year_id']))
            ->when(! empty($filters['class_id']), fn ($q) => $q->where('class_id', $filters['class_id']))
            ->when(! empty($filters['teacher_id']), fn ($q) => $q->where('teacher_id', $filters['teacher_id']))
            ->when(! empty($filters['day']), function ($q) use ($filters) {
                $day = strtolower($filters['day']);
                $q->where(fn ($sub) => $sub->where('day_of_week', $day)->orWhere('day', $day));
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time');
    }

    /**
     * Export schedules to CSV/Excel format.
     *
     * @param  array<string, mixed>  $filters
     */
    public function exportCsv(array $filters = []): StreamedResponse
    {
        $schedules = $this->getFilteredQuery($filters)->get();
        $filename = 'jadwal-pelajaran-'.date('Ymd-His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($schedules): void {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No',
                'Tahun Akademik',
                'Hari',
                'Jam Mulai',
                'Jam Selesai',
                'Kelas',
                'Kode Mapel',
                'Mata Pelajaran',
                'Guru Pengajar',
                'Ruangan',
            ]);

            foreach ($schedules as $index => $schedule) {
                fputcsv($handle, [
                    $index + 1,
                    $schedule->academicYear?->name ?? '-',
                    ucfirst($schedule->day_of_week ?? $schedule->day),
                    $schedule->start_time,
                    $schedule->end_time,
                    $schedule->class?->name ?? '-',
                    $schedule->subject?->code ?? '-',
                    $schedule->subject?->name ?? '-',
                    $schedule->teacher?->name ?? '-',
                    $schedule->roomModel?->name ?? $schedule->room ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export schedules to print-ready PDF/HTML view.
     *
     * @param  array<string, mixed>  $filters
     */
    public function exportPdf(array $filters = []): Response
    {
        $schedules = $this->getFilteredQuery($filters)->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        $grouped = [];

        foreach ($days as $day) {
            $grouped[$day] = $schedules->filter(function ($item) use ($day) {
                return strtolower($item->day_of_week ?? $item->day) === $day;
            });
        }

        $mode = $filters['mode'] ?? $filters['type'] ?? (auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'subject');
        $className = ! empty($filters['class_id'])
            ? \App\Models\ClassModel::find($filters['class_id'])?->name
            : null;

        $html = view('exports.schedules_pdf', [
            'schedules' => $schedules,
            'grouped' => $grouped,
            'mode' => $mode,
            'className' => $className,
            'generatedAt' => now()->translatedFormat('d F Y - H:i'),
        ])->render();

        $filename = ($mode === 'admin')
            ? 'jadwal-keseluruhan-admin-'.date('Ymd-His').'.html'
            : 'jadwal-pelajaran-'.date('Ymd-His').'.html';

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ]);
    }
}

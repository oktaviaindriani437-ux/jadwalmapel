<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('teacher')->orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();

        return view('admin.subjects', compact('subjects', 'teachers'));
    }

    public function store(Request $request)
    {
        if ($request->has('code') && in_array(trim((string) $request->input('code')), ['', '-'])) {
            $request->merge(['code' => null]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:subjects',
            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_name' => 'nullable|string|max:100',
            'code' => 'nullable|string|max:50|unique:subjects,code',
            'credit_hours' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'name.unique' => 'Nama mata pelajaran sudah terdaftar.',
            'code.unique' => 'Kode mata pelajaran sudah digunakan, silakan gunakan kode lain atau kosongkan agar dibuat otomatis.',
        ]);

        $teacherId = $validated['teacher_id'] ?? null;
        $teacherName = trim($request->input('teacher_name') ?? '');

        if (! empty($teacherName)) {
            $teacher = Teacher::whereRaw('LOWER(name) = ?', [strtolower($teacherName)])->first();
            if (! $teacher) {
                $ownerUser = auth()->user() ?? User::where('role', 'admin')->first() ?? User::first();
                $teacher = Teacher::create([
                    'name' => $teacherName,
                    'nip' => 'G-'.str_pad((string) (Teacher::count() + 1), 3, '0', STR_PAD_LEFT),
                    'user_id' => $ownerUser?->id,
                ]);
            }
            $teacherId = $teacher->id;
        }

        $code = trim((string) ($validated['code'] ?? ''));
        if (empty($code)) {
            $code = $this->generateUniqueCode($validated['name']);
        }

        Subject::create([
            'name' => $validated['name'],
            'teacher_id' => $teacherId,
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'credit_hours' => $validated['credit_hours'] ?? 2,
        ]);

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Subject $subject)
    {
        if ($request->has('code') && in_array(trim((string) $request->input('code')), ['', '-'])) {
            $request->merge(['code' => null]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:subjects,name,'.$subject->id,
            'teacher_id' => 'nullable|exists:teachers,id',
            'teacher_name' => 'nullable|string|max:100',
            'code' => 'nullable|string|max:50|unique:subjects,code,'.$subject->id,
            'credit_hours' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama mata pelajaran wajib diisi.',
            'name.unique' => 'Nama mata pelajaran sudah terdaftar.',
            'code.unique' => 'Kode mata pelajaran sudah digunakan, silakan gunakan kode lain atau kosongkan agar dibuat otomatis.',
        ]);

        $teacherId = $validated['teacher_id'] ?? $subject->teacher_id;
        $teacherName = trim($request->input('teacher_name') ?? '');

        if (! empty($teacherName)) {
            $teacher = Teacher::whereRaw('LOWER(name) = ?', [strtolower($teacherName)])->first();
            if (! $teacher) {
                $ownerUser = auth()->user() ?? User::where('role', 'admin')->first() ?? User::first();
                $teacher = Teacher::create([
                    'name' => $teacherName,
                    'nip' => 'G-'.str_pad((string) (Teacher::count() + 1), 3, '0', STR_PAD_LEFT),
                    'user_id' => $ownerUser?->id,
                ]);
            }
            $teacherId = $teacher->id;
        }

        $code = trim((string) ($validated['code'] ?? ''));
        if (empty($code)) {
            $code = ($subject->code && $subject->code !== '-')
                ? $subject->code
                : $this->generateUniqueCode($validated['name'], $subject->id);
        }

        $subject->update([
            'name' => $validated['name'],
            'teacher_id' => $teacherId,
            'code' => $code,
            'description' => $validated['description'] ?? null,
            'credit_hours' => $validated['credit_hours'] ?? $subject->credit_hours,
        ]);

        return back()->with('success', 'Mata pelajaran berhasil diubah.');
    }

    private function generateUniqueCode(string $name, ?int $ignoreId = null): string
    {
        $cleanName = preg_replace('/[^a-zA-Z]/', '', $name);
        $prefix = strtoupper(substr($cleanName, 0, 3));
        if (empty($prefix)) {
            $prefix = 'MPL';
        }

        $counter = 10;
        do {
            $candidate = "{$prefix}-{$counter}";
            $query = Subject::where('code', $candidate);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            $exists = $query->exists();
            $counter++;
        } while ($exists && $counter <= 999);

        return $candidate;
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}

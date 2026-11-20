<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user', 'subjects')->get();
        $existingNips = $teachers->mapWithKeys(function ($t) {
            return [(string) $t->nip => [
                'id' => $t->id,
                'name' => $t->name,
            ]];
        })->toArray();
        $nextSuggestedNip = $this->generateUniqueNip();

        return view('admin.teachers', compact('teachers', 'existingNips', 'nextSuggestedNip'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nip' => 'nullable|string|max:50|unique:teachers,nip',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama lengkap guru wajib diisi.',
            'name.max' => 'Nama guru maksimal 100 karakter.',
            'nip.unique' => 'NIP atau Kode Guru ":input" sudah terdaftar pada guru lain. Silakan gunakan NIP/kode berbeda atau klik "Buat Kode Otomatis".',
            'phone.max' => 'Nomor kontak/telepon maksimal 20 digit.',
        ]);

        if (empty($validated['nip'])) {
            $validated['nip'] = $this->generateUniqueNip();
        }

        $ownerUser = auth()->user() ?? User::where('role', 'admin')->first() ?? User::first();
        $validated['user_id'] = $ownerUser?->id;

        Teacher::create($validated);

        return back()->with('success', 'Guru berhasil ditambahkan.');
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nip' => 'required|string|max:50|unique:teachers,nip,'.$teacher->id,
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama lengkap guru wajib diisi.',
            'name.max' => 'Nama guru maksimal 100 karakter.',
            'nip.required' => 'NIP atau Kode Guru wajib diisi.',
            'nip.unique' => 'NIP atau Kode Guru ":input" sudah terdaftar pada guru lain. Silakan gunakan NIP/kode berbeda.',
            'phone.max' => 'Nomor kontak/telepon maksimal 20 digit.',
        ]);

        $teacher->update($validated);

        return back()->with('success', 'Guru berhasil diubah.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return back()->with('success', 'Guru berhasil dihapus.');
    }

    public function generateUniqueNip(): string
    {
        $maxId = (int) (Teacher::max('id') ?? 0) + 1;
        $code = 'G-'.str_pad((string) $maxId, 3, '0', STR_PAD_LEFT);
        while (Teacher::where('nip', $code)->exists()) {
            $maxId++;
            $code = 'G-'.str_pad((string) $maxId, 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }
}

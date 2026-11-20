<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentManagementController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'class'])->get();
        $classes = ClassModel::all();
        $majors = ClassModel::MAJORS;

        return view('admin.students', compact('students', 'classes', 'majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'nis' => 'required|string|max:50|unique:students,nis',
            'class_id' => 'required|exists:classes,id',
            'jurusan' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama lengkap siswa wajib diisi.',
            'username.required' => 'Username login wajib diisi.',
            'username.unique' => 'Username ini sudah terdaftar. Silakan gunakan username lain.',
            'password.required' => 'Password akun wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'nis.required' => 'Nomor Induk Siswa (NIS) wajib diisi.',
            'nis.unique' => 'Nomor Induk Siswa (NIS) ini sudah digunakan oleh siswa lain.',
            'class_id.required' => 'Silakan pilih rombel kelas siswa.',
            'class_id.exists' => 'Kelas yang dipilih tidak valid.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
            'email' => $validated['username'].'@student.sch.id',
        ]);

        Student::create([
            'user_id' => $user->id,
            'nis' => $validated['nis'],
            'class_id' => $validated['class_id'],
            'jurusan' => $validated['jurusan'] ?? null,
        ]);

        return back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,'.$student->user_id,
            'password' => 'nullable|string|min:6',
            'nis' => 'required|string|max:50|unique:students,nis,'.$student->id,
            'class_id' => 'required|exists:classes,id',
            'jurusan' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama lengkap siswa wajib diisi.',
            'username.required' => 'Username login wajib diisi.',
            'username.unique' => 'Username ini sudah terdaftar. Silakan gunakan username lain.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'nis.required' => 'Nomor Induk Siswa (NIS) wajib diisi.',
            'nis.unique' => 'Nomor Induk Siswa (NIS) ini sudah digunakan oleh siswa lain.',
            'class_id.required' => 'Silakan pilih rombel kelas siswa.',
            'class_id.exists' => 'Kelas yang dipilih tidak valid.',
        ]);

        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
        ];

        if (! empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $student->user->update($userData);

        $student->update([
            'nis' => $validated['nis'],
            'class_id' => $validated['class_id'],
            'jurusan' => $validated['jurusan'] ?? null,
        ]);

        return back()->with('success', 'Siswa berhasil diubah.');
    }

    public function destroy(Student $student)
    {
        $student->user->delete();
        $student->delete();

        return back()->with('success', 'Siswa berhasil dihapus.');
    }
}

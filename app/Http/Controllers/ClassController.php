<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with(['students', 'schedules'])->get();
        $majors = ClassModel::MAJORS;

        return view('admin.classes', compact('classes', 'majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:classes,name',
            'major' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama rombel kelas wajib diisi.',
            'name.unique' => 'Nama rombel kelas sudah ada.',
        ]);

        ClassModel::create($validated);

        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:classes,name,'.$class->id,
            'major' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama rombel kelas wajib diisi.',
            'name.unique' => 'Nama rombel kelas sudah ada.',
        ]);

        $class->update($validated);

        return back()->with('success', 'Kelas berhasil diubah.');
    }

    public function destroy(ClassModel $class)
    {
        $class->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}

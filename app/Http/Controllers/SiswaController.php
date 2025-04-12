<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = Siswa::all();
        return view('siswa.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string|max:60|unique:siswas',
            'nama' => 'required|string|max:50',
            'jeniskelamin' => 'required|in:Laki-laki,Perempuan',
            // 'kelas' => 'required|string|max:50',
            'alamat' => 'required|string|max:50',
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswas.index')
            ->with('success', 'Siswa created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        return view('siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'jeniskelamin' => 'required|in:Laki-laki,Perempuan',
            // 'kelas' => 'required|string|max:50',
            'alamat' => 'required|string|max:50',
        ]);

        $siswa->update($request->all());

        return redirect()->route('siswas.index')
            ->with('success', 'Siswa updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('siswas.index')
            ->with('success', 'Siswa deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);
        Excel::import(new SiswaImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimport!');
    }




}

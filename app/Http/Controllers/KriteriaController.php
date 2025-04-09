<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kriterias = Kriteria::all();
        return view('kriteria.index', compact('kriterias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kriteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'jenis' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric',
        ]);

        Kriteria::create($request->all());

        return redirect()->route('kriterias.index')
            ->with('success', 'Kriteria created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kriteria $kriteria)
    {
        return view('kriteria.show', compact('kriteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kriteria $kriteria)
    {
        return view('kriteria.edit', compact('kriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kriteria $kriteria)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'jenis' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric',
        ]);

        $kriteria->update($request->all());

        return redirect()->route('kriterias.index')
            ->with('success', 'Kriteria updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        return redirect()->route('kriterias.index')
            ->with('success', 'Kriteria deleted successfully.');
    }
}

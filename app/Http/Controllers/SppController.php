<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spp;

class SppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $spp = Spp::all();
        return view('admin.spp', compact('spp'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'nominal' => 'required|integer',
        ]);

        Spp::create($request->all());

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'nominal' => 'required|integer',
        ]);

        $spp = Spp::findOrFail($id);
        $spp->update($request->all());

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $spp = Spp::findOrFail($id);
        $spp->delete();

        return redirect()->route('spp.index')->with('success', 'Data berhasil dihapus!');
    }
}

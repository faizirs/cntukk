<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KompetensiKeahlian;

class KompetensiKeahlianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = KompetensiKeahlian::all();
        return view('admin.kompetensi-keahlian', compact('data'));
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
            'nama_kompetensi_keahlian' => 'required|max:255'
        ]);

        KompetensiKeahlian::create($request->all());
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
            'nama_kompetensi_keahlian' => 'required|max:255'
        ]);

        KompetensiKeahlian::where('id_kompetensi_keahlian', $id)->update([
            'nama_kompetensi_keahlian' => $request->nama_kompetensi_keahlian
        ]);
        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        KompetensiKeahlian::where('id_kompetensi_keahlian', $id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}

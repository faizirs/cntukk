<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\KompetensiKeahlian;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with('kompetensiKeahlian')->get();
        $kompetensi = KompetensiKeahlian::all();
    return view('admin.kelas', compact('kelas', 'kompetensi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not needed as we're using modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|max:10',
            'kompetensi_keahlian_id' => 'required|exists:kompetensi_keahlian,id_kompetensi_keahlian',
        ]);
    
        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'kompetensi_keahlian_id' => $request->kompetensi_keahlian_id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not needed for this implementation
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Not needed as we're using modal
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kelas' => 'required|max:10',
            'kompetensi_keahlian_id' => 'required|exists:kompetensi_keahlian,id_kompetensi_keahlian',
        ]);
    
        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'kompetensi_keahlian_id' => $request->kompetensi_keahlian_id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
}

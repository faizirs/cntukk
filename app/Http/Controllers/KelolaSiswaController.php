<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Spp;

class KelolaSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::with(['user', 'kelas', 'spp'])->get();
        $users = User::all(); // untuk dropdown user di modal
        $kelas = Kelas::all();
        $spp = Spp::all();
        
        return view('admin.kelola-siswa', compact('siswa', 'users', 'kelas', 'spp'));
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
            'nisn' => 'required|unique:siswa,nisn',
            'nis' => 'required',
            'nama' => 'required',
            'id_kelas' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'id_spp' => 'required',
            'id_user' => 'nullable|exists:users,id',
        ]);
        Siswa::create($request->all());
        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan.');
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
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nisn' => 'required|unique:siswa,nisn,' . $id . ',nisn',
            'nis' => 'required',
            'nama' => 'required',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'alamat' => 'required',
            'no_telp' => 'required',
            'id_spp' => 'required|exists:spp,id_spp',
            'id_user' => 'nullable|exists:users,id',
        ]);

        $siswa->update($validated);

        return redirect()->back()->with('success', 'Siswa berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->back()->with('success', 'Siswa berhasil dihapus.');
    }
}

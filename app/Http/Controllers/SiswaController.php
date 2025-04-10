<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

    // Ambil data pembayaran berdasarkan id_user dari user yang login
    $pembayaran = Pembayaran::with(['siswa', 'spp'])
        ->where('id_user', $user->id)
        ->get();
        return view('siswa.riwayat-pembayaran', compact('pembayaran'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Spp;
use App\Exports\PembayaranExport;
use Maatwebsite\Excel\Facades\Excel;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayaran = Pembayaran::with(['siswa', 'spp'])->get();
        $siswa = Siswa::all();
        $spp = Spp::all();
        return view('admin.pembayaran', compact('pembayaran', 'siswa', 'spp'));
    }

    public function exportExcel()
    {
        return Excel::download(new PembayaranExport, 'data_pembayaran.xlsx');

    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'nisn' => 'required',
            'tgl_bayar' => 'required|date',
            'bulan_bayar' => 'required',
            'tahun_bayar' => 'required',
            'id_spp' => 'required',
            'jumlah_bayar' => 'required|numeric',
        ]);

        Pembayaran::create($request->all());
        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update($request->all());
        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Pembayaran::destroy($id);
        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }
}

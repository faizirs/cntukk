<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PembayaranExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return Pembayaran::with(['siswa', 'spp'])->get();
        } else {
            return Pembayaran::with(['siswa', 'spp'])
                ->where('id_user', $user->id)
                ->get();
        }
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Tanggal Bayar',
            'Bulan Bayar',
            'Tahun Bayar',
            'SPP Tahun',
            'Jumlah Bayar',
        ];
    }

    public function map($pembayaran): array
    {
        return [
            $pembayaran->nisn,
            $pembayaran->siswa->nama ?? '-',
            date('d/m/Y', strtotime($pembayaran->tgl_bayar)),
            $pembayaran->bulan_bayar,
            $pembayaran->tahun_bayar,
            $pembayaran->spp->tahun ?? '-',
            $pembayaran->jumlah_bayar,
        ];
    }
}

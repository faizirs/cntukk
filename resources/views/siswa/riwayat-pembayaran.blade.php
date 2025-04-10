@extends('layouts.app')

@section('content')
<h2 class="mb-4">Riwayat Pembayaran SPP</h2>
<hr>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Tanggal Bayar</th>
                    <th>Bulan / Tahun</th>
                    <th>SPP (Tahun)</th>
                    <th>Nominal SPP</th>
                    <th>Jumlah Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembayaran as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->nisn }}</td>
                    <td>{{ $p->siswa->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tgl_bayar)->format('d-m-Y') }}</td>
                    <td>{{ $p->bulan_bayar }} / {{ $p->tahun_bayar }}</td>
                    <td>{{ $p->spp->tahun ?? '-' }}</td>
                    <td>Rp{{ number_format($p->spp->nominal ?? 0, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada riwayat pembayaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

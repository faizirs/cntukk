@extends('layouts.app')

@section('content')
<h2 class="mb-4">Data Pembayaran</h2>
<hr>
<!-- Tombol Tambah -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle mr-1"></i> Tambah Pembayaran
</button>
<a href="{{ route('pembayaran.export') }}" class="btn btn-success mb-3">
    <i class="bi bi-file-earmark-excel"></i> Cetak Laporan
</a>

<!-- Tabel Pembayaran -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Tanggal</th>
                    <th>Bulan/Tahun</th>
                    <th>SPP</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->nisn }}</td>
                    <td>{{ $p->siswa->nama ?? '-' }}</td>
                    <td>{{ $p->tgl_bayar }}</td>
                    <td>{{ $p->bulan_bayar }}/{{ $p->tahun_bayar }}</td>
                    <td>{{ $p->spp->tahun ?? '-' }} - Rp{{ number_format($p->spp->nominal ?? 0, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $p->id_pembayaran }}">Edit</button>
                        <form action="{{ route('pembayaran.destroy', $p->id_pembayaran) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $p->id_pembayaran }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('pembayaran.update', $p->id_pembayaran) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Pembayaran</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @include('admin.pembayaran-form', ['p' => $p, 'siswa' => $siswa, 'spp' => $spp])
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('pembayaran.store') }}">
            @csrf
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pembayaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.pembayaran-form', ['p' => null, 'siswa' => $siswa, 'spp' => $spp])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk ambil NISN otomatis -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.select-siswa').forEach(function(select) {
            const container = select.closest('.modal-body');
            const inputNisn = container.querySelector('.input-nisn');
            const inputIdUser = container.querySelector('.input-id-user');
    
            function updateInputs() {
                const selected = select.options[select.selectedIndex];
                if (selected) {
                    if (inputNisn) inputNisn.value = selected.value;
                    if (inputIdUser) inputIdUser.value = selected.getAttribute('data-iduser');
                }
            }
    
            select.addEventListener('change', updateInputs);
            updateInputs(); // jalanin saat pertama (untuk edit modal)
        });
    });
    </script>
    
    

@endsection

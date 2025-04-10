@extends('layouts.app')

@section('content')
<h2 class="mb-4">Data SPP</h2>
<hr>

<!-- Tombol Tambah -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    Tambah SPP
</button>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Tabel Data -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tahun</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($spp as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $item->id_spp }}">
                            Edit
                        </button>

                        <!-- Form Hapus -->
                        <form action="{{ route('spp.destroy', $item->id_spp) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $item->id_spp }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('spp.update', $item->id_spp) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit SPP</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tahun</label>
                                        <input type="number" name="tahun" class="form-control" value="{{ $item->tahun }}" required>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Nominal</label>
                                        <input type="number" name="nominal" class="form-control" value="{{ $item->nominal }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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
        <form method="POST" action="{{ route('spp.store') }}">
            @csrf
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah SPP</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tahun</label>
                        <input type="number" name="tahun" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Nominal</label>
                        <input type="number" name="nominal" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

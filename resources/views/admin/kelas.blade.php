@extends('layouts.app')

@section('content')
<h2 class="mb-4">Data Kelas</h2>
<hr>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">
    <i class="bi bi-plus-circle mr-1"></i> Tambah Kelas
</button>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Kelas</th>
                    <th>Kompetensi Keahlian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelas as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->nama_kelas }}</td>
                    <td>{{ $item->kompetensiKeahlian->nama_kompetensi_keahlian }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn"
                            data-id="{{ $item->id_kelas }}"
                            data-nama="{{ $item->nama_kelas }}"
                            data-kompetensi="{{ $item->kompetensi_keahlian_id }}"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('kelas.destroy', $item->id_kelas) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <form method="POST" action="{{ route('kelas.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" class="form-control" required>
                    <label class="mt-3">Kompetensi Keahlian</label>
                    <select name="kompetensi_keahlian_id" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($kompetensi as $k)
                        <option value="{{ $k->id_kompetensi_keahlian }}">{{ $k->nama_kompetensi_keahlian }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kelas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="edit_nama" class="form-control" required>
                    <label class="mt-3">Kompetensi Keahlian</label>
                    <select name="kompetensi_keahlian_id" id="edit_kompetensi" class="form-control" required>
                        @foreach($kompetensi as $k)
                        <option value="{{ $k->id_kompetensi_keahlian }}">{{ $k->nama_kompetensi_keahlian }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const editBtns = document.querySelectorAll('.edit-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const kompetensi = this.dataset.kompetensi;

            const form = document.getElementById('editForm');
            form.action = `/kelas/${id}`;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_kompetensi').value = kompetensi;
        });
    });
</script>
@endsection

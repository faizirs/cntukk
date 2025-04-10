@extends('layouts.app')

@section('content')
<h2 class="mb-4">Data Kompetensi Keahlian</h2>
<hr>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">
    <i class="bi bi-plus-circle"></i> Tambah Kompetensi
</button>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-dark table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Kompetensi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->nama_kompetensi_keahlian }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm edit-btn"
                            data-id="{{ $item->id_kompetensi_keahlian }}"
                            data-nama="{{ $item->nama_kompetensi_keahlian }}"
                            data-bs-toggle="modal"
                            data-bs-target="#editModal">
                            Edit
                        </button>
                        <form action="{{ route('kompetensi-keahlian.destroy', $item->id_kompetensi_keahlian) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
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
            <form action="{{ route('kompetensi-keahlian.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kompetensi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Kompetensi</label>
                    <input type="text" name="nama_kompetensi_keahlian" class="form-control" required>
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
                    <h5 class="modal-title">Edit Kompetensi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Nama Kompetensi</label>
                    <input type="text" name="nama_kompetensi_keahlian" id="edit_nama" class="form-control" required>
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
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const form = document.getElementById('editForm');
            form.action = `/kompetensi-keahlian/${id}`;
            document.getElementById('edit_nama').value = nama;
        });
    });
</script>
@endsection

@extends('layouts.app')

@section('content')
<h2 class="mb-4">Kelola Data Siswa</h2>
<hr>
<!-- Tombol Tambah Siswa -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-circle"></i> Tambah Siswa
</button>

<!-- Tabel Siswa -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover table-dark">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NISN</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Alamat</th>
                    <th>No. Telp</th>
                    <th>User</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $index => $s)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $s->nisn }}</td>
                    <td>{{ $s->nis }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $s->alamat }}</td>
                    <td>{{ $s->no_telp }}</td>
                    <td>{{ $s->user->name ?? '-' }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $s->nisn }}">Edit</button>
                        <form action="{{ route('kelola-siswa.destroy', $s->nisn) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $s->nisn }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('kelola-siswa.update', $s->nisn) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Siswa</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @include('admin.siswa-form', ['s' => $s])
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
        <form method="POST" action="{{ route('kelola-siswa.store') }}">
            @csrf
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.siswa-form', ['s' => null])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
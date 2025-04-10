@extends('layouts.app')

@section('content')
<h2 class="mb-4">Manajemen User</h2>
<hr>

<!-- Tombol Tambah -->
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
    Tambah User
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
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username ?? '-' }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $user->id }}">
                            Edit
                        </button>

                        <!-- Form Hapus -->
                        <form action="{{ route('management.destroy', $user->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('management.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit User</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Username (Kosongkan untuk siswa)</label>
                                        <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Password (Kosongkan jika tidak ingin diganti)</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="form-group mt-2">
                                        <label>Role</label>
                                        <select name="role" class="form-select" required>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                        </select>
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
        <form method="POST" action="{{ route('management.store') }}">
            @csrf
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Username (kosongkan jika siswa)</label>
                        <input type="text" name="username" class="form-control">
                    </div>
                    <div class="form-group mt-2">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <label>Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="siswa">Siswa</option>
                        </select>
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

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="nisn" class="form-label">NISN</label>
        <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $s->nisn ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="nis" class="form-label">NIS</label>
        <input type="text" name="nis" class="form-control" value="{{ old('nis', $s->nis ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $s->nama ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="id_kelas" class="form-label">Kelas</label>
        <select name="id_kelas" class="form-select" required>
            <option value="">-- Pilih Kelas --</option>
            @foreach ($kelas as $k)
                <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $s->id_kelas ?? '') == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" required>{{ old('alamat', $s->alamat ?? '') }}</textarea>
    </div>

    <div class="col-md-6 mb-3">
        <label for="no_telp" class="form-label">No. Telepon</label>
        <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $s->no_telp ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label for="id_spp" class="form-label">SPP</label>
        <select name="id_spp" class="form-select" required>
            <option value="">-- Pilih SPP --</option>
            @foreach ($spp as $sp)
                <option value="{{ $sp->id_spp }}" {{ old('id_spp', $s->id_spp ?? '') == $sp->id_spp ? 'selected' : '' }}>
                    {{ $sp->tahun }} - Rp{{ number_format($sp->nominal) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 mb-3">
        <label for="id_user" class="form-label">Tautkan ke User</label>
        <select name="id_user" class="form-select">
            <option value="">-- Kosongkan jika belum ada --</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" {{ old('id_user', $s->id_user ?? '') == $u->id ? 'selected' : '' }}>
                    {{ $u->name }} ({{ $u->email }})
                </option>
            @endforeach
        </select>
    </div>
</div>

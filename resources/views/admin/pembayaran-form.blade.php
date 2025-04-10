<!-- Pilih Nama Siswa -->
<div class="mb-3">
    <label for="nisn" class="form-label">Nama Siswa</label>
    <select name="nisn" class="form-select select-siswa" required>
        <option value="">-- Pilih Siswa --</option>
        @foreach ($siswa as $s)
            <option value="{{ $s->nisn }}"
                    data-iduser="{{ $s->id_user }}"
                    {{ isset($p) && $p->nisn == $s->nisn ? 'selected' : '' }}>
                {{ $s->nama }}
            </option>
        @endforeach
    </select>
</div>

<!-- NISN Otomatis -->
<div class="mb-3">
    <label for="inputNisn" class="form-label">NISN</label>
    <input type="text" class="form-control input-nisn" id="inputNisn" readonly>
</div>

<!-- Hidden input id_user -->
<input type="hidden" name="id_user" class="input-id-user">

<!-- Tanggal Bayar -->
<div class="mb-3">
    <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
    <input type="date" name="tgl_bayar" class="form-control" value="{{ $p->tgl_bayar ?? '' }}" required>
</div>

<!-- Bulan Bayar -->
<div class="mb-3">
    <label for="bulan_bayar" class="form-label">Bulan Bayar</label>
    <input type="text" name="bulan_bayar" class="form-control" value="{{ $p->bulan_bayar ?? '' }}" required>
</div>

<!-- Tahun Bayar -->
<div class="mb-3">
    <label for="tahun_bayar" class="form-label">Tahun Bayar</label>
    <input type="text" name="tahun_bayar" class="form-control" value="{{ $p->tahun_bayar ?? '' }}" required>
</div>

<!-- SPP -->
<div class="mb-3">
    <label for="id_spp" class="form-label">SPP</label>
    <select name="id_spp" class="form-select" required>
        @foreach ($spp as $s)
            <option value="{{ $s->id_spp }}"
                {{ isset($p) && $p->id_spp == $s->id_spp ? 'selected' : '' }}>
                {{ $s->tahun }} - Rp{{ number_format($s->nominal, 0, ',', '.') }}
            </option>
        @endforeach
    </select>
</div>

<!-- Jumlah Bayar -->
<div class="mb-3">
    <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
    <input type="number" name="jumlah_bayar" class="form-control" value="{{ $p->jumlah_bayar ?? '' }}" required>
</div>

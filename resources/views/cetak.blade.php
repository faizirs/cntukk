<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi Pembayaran SPP</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
                padding: 15px;
            }
            .kuitansi {
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .card {
                border: 1px solid #000 !important;
            }
            .table-bordered {
                border: 1px solid #000 !important;
            }
            .table-bordered td, .table-bordered th {
                border: 1px solid #000 !important;
            }
        }
        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin-top: 70px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Tombol cetak (hanya muncul di layar) -->
        <div class="row mb-4 no-print">
            <div class="col text-center">
                <button class="btn btn-primary" onclick="window.print()">Cetak Kuitansi</button>
                <button class="btn btn-secondary" onclick="window.close()">Tutup</button>
            </div>
        </div>
        
        <!-- Kuitansi -->
        <div class="kuitansi card shadow-sm">
            <div class="card-header bg-primary text-white text-center py-3">
                <h4 class="mb-0">KUITANSI PEMBAYARAN SPP</h4>
            </div>
            
            <div class="card-body">
                <!-- Header Sekolah -->
                <div class="text-center mb-4">
                    <h5 class="fw-bold">SEKOLAH MENENGAH KEJURUAN</h5>
                    <p class="mb-0">Jl. Pendidikan No. 123, Kota, Provinsi</p>
                    <p class="mb-0">Telp: (021) 1234567 | Email: info@smk.sch.id</p>
                </div>
                
                <hr>
                
                <!-- Informasi Pembayaran -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>No. Kuitansi:</strong> SPP-{{ str_pad($pembayaran->id_pembayran, 4, '0', STR_PAD_LEFT) }}</p>
                        <p><strong>Tanggal:</strong> {{ date('d F Y', strtotime($pembayaran->tgl_pembayaran)) }}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p><strong>NISN:</strong> {{ $pembayaran->nisn }}</p>
                        <p><strong>Nama:</strong> {{ $pembayaran->siswa->nama }}</p>
                        <p><strong>Kelas:</strong> {{ $pembayaran->siswa->kelas->nama_kelas }}</p>
                    </div>
                </div>
                
                <!-- Detail Pembayaran -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Deskripsi</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Pembayaran SPP Bulan {{ $pembayaran->bulan_pembayaran }} {{ $pembayaran->tahun_pembayaran }}</td>
                                <td class="text-end">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }},-</td>
                            </tr>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-end">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }},-</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <p><strong>Terbilang:</strong></p>
                        <p><i>{{ ucwords(terbilang($pembayaran->jumlah_bayar)) }} Rupiah</i></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p>{{ date('d F Y', strtotime($pembayaran->tgl_pembayaran)) }}</p>
                        <p>Petugas</p>
                        <div class="signature-line mx-auto ms-md-auto me-md-0"></div>
                        <p><strong>{{ $pembayaran->user->name }}</strong></p>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light text-center py-2">
                <small>Kuitansi ini sah tanpa tanda tangan dan cap</small>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Fungsi untuk mengubah angka menjadi kata-kata dalam bahasa Indonesia
function terbilang($angka) {
    $angka = abs($angka);
    $bilangan = array(
        '',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan',
        'sepuluh',
        'sebelas'
    );
    
    if ($angka < 12) {
        return $bilangan[$angka];
    } elseif ($angka < 20) {
        return $bilangan[$angka - 10] . ' belas';
    } elseif ($angka < 100) {
        $hasil = $bilangan[(int)($angka / 10)] . ' puluh ';
        $hasil .= $bilangan[$angka % 10];
        return $hasil;
    } elseif ($angka < 200) {
        return 'seratus ' . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        $hasil = $bilangan[(int)($angka / 100)] . ' ratus ';
        $hasil .= terbilang($angka % 100);
        return $hasil;
    } elseif ($angka < 2000) {
        return 'seribu ' . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $hasil = terbilang((int)($angka / 1000)) . ' ribu ';
        $hasil .= terbilang($angka % 1000);
        return $hasil;
    } elseif ($angka < 1000000000) {
        $hasil = terbilang((int)($angka / 1000000)) . ' juta ';
        $hasil .= terbilang($angka % 1000000);
        return $hasil;
    }
    
    return '';
}
?>
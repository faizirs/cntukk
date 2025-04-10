@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-dark text-white shadow-lg rounded-4">
                <div class="card-body text-center">
                    <h1 class="display-5 fw-bold mb-3">Selamat Datang di Aplikasi Pembayaran SPP</h1>
                    <hr class="my-4 border-light">
                    <p class="mb-4">
                        Silakan gunakan menu navigasi di atas untuk mengelola data SPP, siswa, pembayaran, dan lainnya.
                    </p>
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-cash-coin me-1"></i> Lihat Data Pembayaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

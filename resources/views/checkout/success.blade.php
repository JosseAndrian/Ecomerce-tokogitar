@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Musik Store')

@section('content')
<div class="container my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <i class="bi bi-check-circle-fill text-success display-1 mb-4"></i>
            <h1 class="fw-bold mb-3">Pesanan Berhasil Dibuat!</h1>
            <p class="lead text-muted mb-4">Terima kasih telah berbelanja di Musik Store. Nomor pesanan Anda adalah <strong class="text-dark">{{ $order->order_code }}</strong>.</p>

            <div class="card border-0 shadow-sm rounded-4 mb-4 text-start">
                <div class="card-body p-4 bg-light rounded-4 border">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Pembayaran</h5>
                    
                    @if($order->payment_method === 'transfer_bank')
                        <p class="mb-2">Total Tagihan:</p>
                        <h3 class="text-primary fw-bold mb-3">{{ $order->formatted_total }}</h3>
                        
                        <p class="text-muted small mb-2">Silakan transfer ke salah satu rekening berikut:</p>
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2 bg-white p-2 rounded border">
                                <strong class="me-auto">BCA</strong>
                                <span class="fs-5 me-2">1234 5678 90</span>
                                <small class="text-muted">a.n Musik Store</small>
                            </div>
                            <div class="d-flex align-items-center mb-2 bg-white p-2 rounded border">
                                <strong class="me-auto">Mandiri</strong>
                                <span class="fs-5 me-2">0987 6543 21</span>
                                <small class="text-muted">a.n Musik Store</small>
                            </div>
                        </div>
                        
                        <div class="alert alert-warning mb-0 border-0">
                            <i class="bi bi-info-circle me-2"></i> Setelah melakukan transfer, harap unggah bukti pembayaran agar pesanan dapat segera diproses.
                        </div>
                    @else
                        <p class="mb-2">Total Tagihan:</p>
                        <h3 class="text-primary fw-bold mb-3">{{ $order->formatted_total }}</h3>
                        <div class="alert alert-info mb-0 border-0">
                            <i class="bi bi-info-circle me-2"></i> Anda memilih metode <strong>Bayar di Tempat (COD)</strong>. Siapkan uang tunai saat kurir tiba.
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary rounded-pill px-4">Lihat Detail Pesanan</a>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Lanjut Belanja</a>
            </div>
        </div>
    </div>
</div>
@endsection

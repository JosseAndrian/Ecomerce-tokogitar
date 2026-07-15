@extends('layouts.app')

@section('title', 'Pesanan Saya - Musik Store')

@section('content')
<div class="container my-4">
    <h2 class="fw-bold mb-4"><i class="bi bi-box-seam text-primary me-2"></i>Pesanan Saya</h2>

    @if($orders->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-light">
            <i class="bi bi-receipt display-1 text-muted mb-3"></i>
            <h4>Belum Ada Pesanan</h4>
            <p class="text-muted">Anda belum pernah melakukan pemesanan.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Mulai Belanja</a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-10 mx-auto">
                @foreach($orders as $order)
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small me-3"><i class="bi bi-calendar3 me-1"></i> {{ $order->created_at->format('d M Y, H:i') }}</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-hash"></i> {{ $order->order_code }}</span>
                        </div>
                        <span class="badge bg-{{ $order->status_badge }} px-3 py-2 rounded-pill">{{ $order->status_label }}</span>
                    </div>
                    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="mb-3 mb-md-0">
                            <p class="text-muted small mb-1">Total Belanja</p>
                            <h4 class="fw-bold text-primary mb-0">{{ $order->formatted_total }}</h4>
                        </div>
                        <div class="d-flex gap-2">
                            @if($order->status === 'pending')
                                <button type="button" class="btn btn-outline-danger rounded-pill px-4" 
                                    data-bs-toggle="modal" data-bs-target="#cancelModal-{{ $order->id }}">
                                    Batalkan
                                </button>

                                <!-- Cancel Modal -->
                                <div class="modal fade" id="cancelModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                                        <div class="modal-content border-0 shadow rounded-4 text-center p-4">
                                            <div class="modal-body">
                                                <i class="bi bi-exclamation-triangle text-warning display-1 mb-3"></i>
                                                <h4 class="fw-bold mb-3">Batalkan Pesanan?</h4>
                                                <p class="text-muted mb-4">Apakah Anda yakin ingin membatalkan pesanan <strong>#{{ $order->order_code }}</strong>?</p>
                                                <div class="d-grid gap-2">
                                                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill shadow-sm mb-2">Ya, Batalkan</button>
                                                    </form>
                                                    <button type="button" class="btn btn-light btn-lg w-100 rounded-pill" data-bs-dismiss="modal">Kembali</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($order->payment_method === 'transfer_bank')
                                    <a href="{{ route('payment.show', $order->id) }}" class="btn btn-warning rounded-pill px-4 shadow-sm">Bayar Sekarang</a>
                                @endif
                            @endif
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary rounded-pill px-4">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

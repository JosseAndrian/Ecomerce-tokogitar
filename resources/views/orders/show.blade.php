@extends('layouts.app')

@section('title', 'Detail Pesanan - Musik Store')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-file-earmark-text text-primary me-2"></i>Detail Pesanan</h2>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <div>
                            <h5 class="fw-bold mb-1">No. Pesanan: <span class="text-primary">{{ $order->order_code }}</span></h5>
                            <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</small>
                        </div>
                        <span class="badge bg-{{ $order->status_badge }} px-3 py-2 fs-6 rounded-pill">{{ $order->status_label }}</span>
                    </div>

                    <h6 class="fw-bold mb-3">Daftar Produk</h6>
                    <div class="list-group list-group-flush mb-4 border rounded-3">
                        @foreach($order->orderDetails as $detail)
                        <div class="list-group-item px-3 py-3 border-bottom">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    @if($detail->product)
                                        <img src="{{ $detail->image_url }}" alt="{{ $detail->product_name }}" class="rounded bg-light border p-1 me-3" style="width: 60px; height: 60px; object-fit: contain;">
                                    @else
                                        <div class="rounded border p-1 me-3 bg-light d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <div class="ms-3 flex-grow-1">
                                        <h6 class="mb-0 fw-bold">{{ $detail->product_name }}</h6>
                                        @if($detail->variation)
                                            <small class="text-primary fw-semibold">Variasi: {{ $detail->variation }}</small><br>
                                        @endif
                                        <small class="text-muted">{{ $detail->quantity }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold text-primary">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>

                            @if(in_array($order->status, ['paid', 'shipped', 'completed']) && $detail->product)
                                @php
                                    $review = \App\Models\Review::where('order_id', $order->id)
                                                              ->where('product_id', $detail->product_id)
                                                              ->first();
                                @endphp

                                <div class="mt-3 p-3 bg-light rounded-4">
                                    @if($review)
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="text-warning me-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                                @endfor
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                        </div>
                                        <p class="mb-0 small text-dark fst-italic">"{{ $review->comment }}"</p>
                                    @else
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#reviewForm-{{ $detail->id }}">
                                            <i class="bi bi-star me-1"></i> Beri Ulasan
                                        </button>
                                        <div class="collapse mt-2" id="reviewForm-{{ $detail->id }}">
                                            <form action="{{ route('reviews.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <input type="hidden" name="product_id" value="{{ $detail->product_id }}">
                                                
                                                <div class="mb-2">
                                                    <label class="small fw-bold mb-1 d-block">Rating:</label>
                                                    <div class="star-rating">
                                                        @for($i = 5; $i >= 1; $i--)
                                                            <input type="radio" id="star{{ $i }}-{{ $detail->id }}" name="rating" value="{{ $i }}" required />
                                                            <label for="star{{ $i }}-{{ $detail->id }}" title="{{ $i }} stars">
                                                                <i class="bi bi-star-fill"></i>
                                                            </label>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <textarea name="comment" class="form-control form-control-sm rounded-3" placeholder="Bagikan pengalaman Anda menggunakan produk ini..." rows="2"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">Kirim Ulasan</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @endforeach

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}
.star-rating input { display: none; }
.star-rating label {
    color: #ddd;
    font-size: 1.25rem;
    padding: 0 0.1rem;
    cursor: pointer;
    transition: color 0.2s;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffc107;
}
</style>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">Informasi Pengiriman</h6>
                    <div class="row text-muted">
                        <div class="col-sm-3 fw-semibold">Penerima</div>
                        <div class="col-sm-9 text-dark">{{ $order->user->name }} ({{ $order->phone }})</div>
                        
                        <div class="col-sm-3 fw-semibold mt-2">Alamat</div>
                        <div class="col-sm-9 text-dark mt-2">{{ $order->shipping_address }}, {{ $order->city }}, {{ $order->province }} - {{ $order->postal_code }}</div>
                        
                        @if($order->notes)
                        <div class="col-sm-3 fw-semibold mt-2">Catatan</div>
                        <div class="col-sm-9 text-dark mt-2 fst-italic">{{ $order->notes }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4">Ringkasan Pembayaran</h6>
                    
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Metode Pembayaran</span>
                        <span class="text-dark fw-bold">{{ $order->payment_method === 'transfer_bank' ? 'Transfer Bank' : 'Bayar di Tempat (COD)' }}</span>
                    </div>
                    
                    <hr class="border-light my-3">
                    
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between p-3 bg-light rounded-3">
                        <span class="fw-bold text-dark">Total Pembayaran</span>
                        <span class="fw-bold text-primary fs-5">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            @if($order->payment_method === 'transfer_bank')
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Status Pembayaran</h6>
                        
                        @if(!$order->payment)
                            <div class="alert alert-warning border-0 rounded-3 text-center mb-3">
                                <i class="bi bi-exclamation-triangle-fill d-block fs-3 mb-2"></i>
                                Anda belum mengunggah bukti pembayaran.
                            </div>
                            @if($order->status === 'pending')
                                <a href="{{ route('payment.show', $order->id) }}" class="btn btn-warning w-100 rounded-pill fw-bold">Upload Bukti Transfer</a>
                            @endif
                        @else
                            <div class="text-center mb-3">
                                @if($order->payment->status === 'pending')
                                    <span class="badge bg-warning p-2 fs-6 rounded-pill w-100"><i class="bi bi-hourglass-split me-2"></i>Menunggu Verifikasi</span>
                                @elseif($order->payment->status === 'verified')
                                    <span class="badge bg-success p-2 fs-6 rounded-pill w-100"><i class="bi bi-check-circle me-2"></i>Pembayaran Diverifikasi</span>
                                @else
                                    <span class="badge bg-danger p-2 fs-6 rounded-pill w-100"><i class="bi bi-x-circle me-2"></i>Pembayaran Ditolak</span>
                                @endif
                            </div>
                            
                            <div class="bg-light p-3 rounded-3 text-center">
                                <p class="small text-muted mb-2">Bukti Transfer:</p>
                                <a href="{{ $order->payment->proof_image_url }}" target="_blank">
                                    <img src="{{ $order->payment->proof_image_url }}" alt="Bukti Transfer" class="img-fluid rounded border" style="max-height: 150px; object-fit: cover;">
                                </a>
                            </div>
                            
                            @if($order->payment->status === 'rejected')
                                <a href="{{ route('payment.show', $order->id) }}" class="btn btn-danger w-100 mt-3 rounded-pill">Upload Ulang</a>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

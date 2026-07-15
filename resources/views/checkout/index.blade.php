@extends('layouts.app')

@section('title', 'Checkout - Musik Store')

@section('content')
<div class="container my-4">
    <h2 class="fw-bold mb-4"><i class="bi bi-credit-card text-primary me-2"></i>Checkout</h2>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">1. Alamat Pengiriman</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Penerima</label>
                                <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor HP</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', auth()->user()->phone) }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Provinsi</label>
                                <input type="text" name="province" class="form-control @error('province') is-invalid @enderror" value="{{ old('province') }}" required>
                                @error('province')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kota / Kabupaten</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Alamat Lengkap</label>
                                <textarea name="shipping_address" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required placeholder="Nama jalan, gedung, no. rumah, RT/RW, patokan">{{ old('shipping_address', auth()->user()->address) }}</textarea>
                                @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Kode Pos</label>
                                <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code') }}" required>
                                @error('postal_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Catatan Pesanan (Opsional)</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Contoh: Titip di pos satpam">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">2. Metode Pembayaran</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="card h-100 border p-3 rounded-3 cursor-pointer @error('payment_method') border-danger @enderror" style="cursor: pointer;">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="radio" name="payment_method" value="transfer_bank" required checked>
                                        <span class="form-check-label fw-bold d-block ms-2">
                                            Transfer Bank (Manual)
                                        </span>
                                        <small class="text-muted ms-2 d-block mt-1">Transfer ke rekening BCA atau Mandiri.</small>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="card h-100 border p-3 rounded-3 cursor-pointer @error('payment_method') border-danger @enderror" style="cursor: pointer;">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="radio" name="payment_method" value="cod" required>
                                        <span class="form-check-label fw-bold d-block ms-2">
                                            Bayar di Tempat (COD)
                                        </span>
                                        <small class="text-muted ms-2 d-block mt-1">Bayar tunai saat kurir datang.</small>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="col-12 text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 80px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                        
                        <div class="list-group list-group-flush mb-4">
                            @foreach($carts as $cart)
                            <div class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $cart->product->image_url }}" alt="{{ $cart->product->name }}" class="rounded border p-1 me-2 bg-light" style="width: 40px; height: 40px; object-fit: contain;">
                                        <div>
                                            <h6 class="mb-0 text-truncate" style="max-width: 150px; font-size: 0.9rem;">{{ $cart->product->name }}</h6>
                                            <small class="text-muted">{{ $cart->quantity }} x {{ $cart->product->formatted_price }}</small>
                                        </div>
                                    </div>
                                    <span class="fw-bold fs-6">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Harga ({{ $carts->sum('quantity') }} barang)</span>
                            <span class="fw-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span class="fw-bold">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        
                        <hr class="border-secondary mb-3">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total Tagihan</span>
                            <span class="fw-bold fs-5 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">Buat Pesanan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

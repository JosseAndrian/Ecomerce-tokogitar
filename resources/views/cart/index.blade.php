@extends('layouts.app')

@section('title', 'Keranjang Belanja - Musik Store')

@section('content')
<div class="container my-4">
    <h2 class="fw-bold mb-4"><i class="bi bi-cart3 text-primary me-2"></i>Keranjang Belanja</h2>

    @if($carts->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
            <h4>Keranjang belanja Anda kosong</h4>
            <p class="text-muted">Yuk, cari alat musik impianmu sekarang!</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">Mulai Belanja</a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted text-uppercase small">
                                    <tr>
                                        <th class="ps-4 py-3">Produk</th>
                                        <th class="py-3">Harga</th>
                                        <th class="py-3" style="width: 150px;">Kuantitas</th>
                                        <th class="py-3">Subtotal</th>
                                        <th class="pe-4 py-3 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($carts as $cart)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $cart->image_url }}" alt="{{ $cart->product->name }}" class="rounded bg-light border p-1 me-3" style="width: 60px; height: 60px; object-fit: contain;">
                                                <div>
                                                    <h6 class="mb-1 fw-bold"><a href="{{ route('shop.show', $cart->product->slug) }}" class="text-dark text-decoration-none">{{ $cart->product->name }}</a></h6>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted me-2">{{ $cart->product->category->name }}</small>
                                                        @if($cart->variation)
                                                            <span class="badge bg-light text-primary border border-primary-subtle rounded-pill py-1 px-2" style="font-size: 0.7rem;">
                                                                Variasi: {{ $cart->variation }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">{{ $cart->formatted_price }}</td>
                                        <td class="py-3">
                                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="d-flex align-items-center bg-light rounded-pill p-1" style="width: 120px;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button" class="btn btn-sm btn-white rounded-circle shadow-sm" onclick="stepQty('qty-{{ $cart->id }}', -1); this.form.submit()" style="width: 25px; height: 25px; padding: 0;">-</button>
                                                <input type="number" name="quantity" id="qty-{{ $cart->id }}" class="form-control form-control-sm text-center border-0 fw-bold bg-transparent" value="{{ $cart->quantity }}" min="1" max="{{ $cart->product->stock }}" readonly style="padding: 0;">
                                                <button type="button" class="btn btn-sm btn-white rounded-circle shadow-sm" onclick="stepQty('qty-{{ $cart->id }}', 1); this.form.submit()" style="width: 25px; height: 25px; padding: 0;">+</button>
                                            </form>
                                            @if($cart->product->stock < $cart->quantity)
                                                <small class="text-danger d-block mt-1" style="font-size: 0.7rem;">Stok tidak cukup</small>
                                            @endif
                                        </td>
                                        <td class="py-3 fw-bold text-primary">Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</td>
                                        <td class="pe-4 py-3 text-end">
                                            <button type="button" class="btn btn-outline-danger btn-sm rounded-circle btn-delete-item" 
                                                data-url="{{ route('cart.remove', $cart->id) }}" 
                                                data-name="{{ $cart->product->name }}"
                                                data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 p-4 d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-secondary btn-sm btn-delete-item" 
                            data-url="{{ route('cart.clear') }}" 
                            data-name="semua isi keranjang"
                            data-is-clear="true"
                            data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                            Kosongkan Keranjang
                        </button>
                        <a href="{{ route('shop.index') }}" class="text-decoration-none fw-semibold"><i class="bi bi-arrow-left me-1"></i> Lanjut Belanja</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Ringkasan Belanja Sidebar -->
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Harga ({{ $carts->sum('quantity') }} barang)</span>
                            <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        
                        <hr class="border-secondary mb-4">
                        
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Total Bayar</span>
                            <span class="fw-bold fs-5 text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        @php
                            $canCheckout = true;
                            foreach($carts as $c) {
                                if($c->product->stock < $c->quantity) {
                                    $canCheckout = false;
                                    break;
                                }
                            }
                        @endphp

                        @if($canCheckout)
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">Beli Sekarang ({{ $carts->sum('quantity') }})</a>
                        @else
                            <button class="btn btn-secondary btn-lg w-100 rounded-pill disabled">Stok Tidak Mencukupi</button>
                            <div class="form-text text-danger mt-2 text-center small">Silakan kurangi kuantitas produk yang stoknya habis.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Custom Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center p-5">
                <div class="mb-4">
                    <i class="bi bi-exclamation-circle text-danger display-1"></i>
                </div>
                <h4 class="fw-bold mb-3">Konfirmasi Hapus</h4>
                <p class="text-muted mb-4">Apakah Anda yakin ingin menghapus <span id="deleteItemName" class="fw-bold text-dark"></span>?</p>
                
                <div class="d-grid gap-2">
                    <form id="deleteConfirmForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill shadow-sm mb-2">Ya, Hapus Sekarang</button>
                    </form>
                    <button type="button" class="btn btn-light btn-lg w-100 rounded-pill" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteConfirmModal');
        const deleteForm = document.getElementById('deleteConfirmForm');
        const deleteNameDisplay = document.getElementById('deleteItemName');

        document.querySelectorAll('.btn-delete-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                const name = this.getAttribute('data-name');
                const isClear = this.getAttribute('data-is-clear');

                deleteForm.action = url;
                deleteNameDisplay.innerText = name;
                
                if(isClear) {
                    deleteModal.querySelector('h4').innerText = "Kosongkan Keranjang";
                    deleteModal.querySelector('i').className = "bi bi-cart-x text-danger display-1";
                } else {
                    deleteModal.querySelector('h4').innerText = "Konfirmasi Hapus";
                    deleteModal.querySelector('i').className = "bi bi-exclamation-circle text-danger display-1";
                }
            });
        });
    });
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('title', 'Detail Pesanan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Detail Pesanan #{{ $order->order_code }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Informasi Produk</h5>
                <span class="badge bg-{{ $order->status_badge }} rounded-pill px-3 py-2 fs-6">{{ $order->status_label }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Produk</th>
                                <th class="py-3 text-center">Harga</th>
                                <th class="py-3 text-center">Qty</th>
                                <th class="pe-4 py-3 text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $detail)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        @if($detail->product)
                                            <img src="{{ $detail->image_url }}" alt="{{ $detail->product_name }}" class="rounded bg-light border p-1 me-3" style="width: 50px; height: 50px; object-fit: contain;">
                                        @else
                                            <div class="rounded border p-1 me-3 bg-light d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        @endif
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $detail->product_name }}</span>
                                            @if($detail->variation)
                                                <small class="text-primary fw-semibold">Variasi: {{ $detail->variation }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-center">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                <td class="py-3 text-center">{{ $detail->quantity }}</td>
                                <td class="pe-4 py-3 text-end fw-bold text-primary">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white p-4 border-top">
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal Produk</span>
                            <span class="fw-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span class="fw-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold fs-5">Total Bayar</span>
                            <span class="fw-bold fs-5 text-primary">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0">Update Status Pesanan</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <select name="status" class="form-select form-select-lg">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Pembayaran Diterima (Proses)</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Sedang Diproses (Packing)</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Sedang Dikirim (Resi)</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Pesanan Selesai</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-3 mt-md-0">
                            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill">Update Status</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0">Info Pengiriman</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <p class="text-muted small mb-1">Nama Penerima</p>
                    <h6 class="fw-bold">{{ $order->user->name }}</h6>
                </div>
                <div class="mb-3">
                    <p class="text-muted small mb-1">No. Handphone</p>
                    <h6 class="fw-bold">{{ $order->phone }}</h6>
                </div>
                <div class="mb-3">
                    <p class="text-muted small mb-1">Alamat Lengkap</p>
                    <p class="mb-0">{{ $order->shipping_address }}<br>
                    {{ $order->city }}, {{ $order->province }} - {{ $order->postal_code }}</p>
                </div>
                @if($order->notes)
                <div>
                    <p class="text-muted small mb-1">Catatan Pesanan</p>
                    <p class="mb-0 text-dark bg-light p-2 rounded fst-italic">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0">Info Pembayaran</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <p class="text-muted small mb-1">Metode Pembayaran</p>
                    <h6 class="fw-bold">{{ $order->payment_method === 'transfer_bank' ? 'Transfer Bank' : 'Bayar di Tempat (COD)' }}</h6>
                </div>

                @if($order->payment_method === 'transfer_bank')
                    @if($order->payment)
                        <hr>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Bank Pengirim</p>
                            <h6 class="fw-bold">{{ $order->payment->bank_name }}</h6>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Atas Nama</p>
                            <h6 class="fw-bold">{{ $order->payment->account_name }} ({{ $order->payment->account_number }})</h6>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-2">Bukti Transfer</p>
                            <a href="{{ $order->payment->proof_image_url }}" target="_blank">
                                <img src="{{ $order->payment->proof_image_url }}" class="img-fluid rounded border shadow-sm" alt="Bukti Transfer">
                            </a>
                        </div>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Status Verifikasi</p>
                            @if($order->payment->status === 'pending')
                                <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                            @elseif($order->payment->status === 'verified')
                                <span class="badge bg-success">Diverifikasi {{ $order->payment->verified_at ? '(' . $order->payment->verified_at->format('d M Y') . ')' : '' }}</span>
                            @else
                                <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </div>

                        @if($order->payment->status === 'pending')
                        <hr>
                        <div class="d-grid gap-2">
                            <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="payment_status" value="verified">
                                <input type="hidden" name="order_status" value="paid">
                                <button type="submit" class="btn btn-success rounded-pill fw-bold w-100 mb-2">
                                    <i class="bi bi-check-circle me-1"></i> Verifikasi Pembayaran
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="payment_status" value="rejected">
                                <input type="hidden" name="order_status" value="pending">
                                <button type="submit" class="btn btn-outline-danger rounded-pill fw-bold w-100">
                                    <i class="bi bi-x-circle me-1"></i> Tolak Pembayaran
                                </button>
                            </form>
                        </div>
                        @endif
                    @else
                        <div class="alert alert-warning border-0 rounded-3 mb-0">
                            <i class="bi bi-exclamation-triangle-fill d-block fs-3 mb-2 text-center"></i>
                            <div class="text-center">Pelanggan belum mengunggah bukti pembayaran.</div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

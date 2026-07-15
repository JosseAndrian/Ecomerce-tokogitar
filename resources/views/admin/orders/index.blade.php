@extends('layouts.admin')

@section('title', 'Pesanan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Manajemen Pesanan</h1>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Order ID</th>
                        <th class="py-3">Pelanggan</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Total Tagihan</th>
                        <th class="py-3">Metode</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4 py-3 fw-semibold">
                            #{{ $order->order_code }}
                        </td>
                        <td class="py-3">
                            <div class="fw-bold">{{ $order->user->name }}</div>
                            <small class="text-muted">{{ $order->phone }}</small>
                        </td>
                        <td class="py-3">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="py-3 fw-bold text-primary">{{ $order->formatted_total }}</td>
                        <td class="py-3">
                            @if($order->payment_method === 'transfer_bank')
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">Transfer Bank</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">COD</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <span class="badge bg-{{ $order->status_badge }} rounded-pill">{{ $order->status_label }}</span>
                        </td>
                        <td class="pe-4 py-3 text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox display-4 d-block mb-3"></i>
                            Belum ada data pesanan masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

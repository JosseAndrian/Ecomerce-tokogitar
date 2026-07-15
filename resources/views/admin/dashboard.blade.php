@extends('layouts.admin')

@section('title', 'Admin Dashboard - Musik Store')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Dashboard Statistik</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-bar-chart-line me-1"></i> Lihat Laporan
        </a>
        <a href="{{ route('admin.reports.print', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill px-3">
            <i class="bi bi-printer me-1"></i> Cetak Laporan Bulan Ini
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">
    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.78rem;">Total Pendapatan</h6>
                        <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                        <small class="text-muted">Dari pesanan selesai</small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.78rem;">Total Pesanan</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalOrders }} <small class="text-muted fs-6">Transaksi</small></h4>
                        <small class="text-warning fw-semibold">{{ $pendingOrders }} menunggu pembayaran</small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="bi bi-cart-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.78rem;">Total Produk</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalProducts }} <small class="text-muted fs-6">Item</small></h4>
                        <small class="text-muted">{{ $totalCategories }} kategori</small>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.78rem;">Pelanggan</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalCustomers }} <small class="text-muted fs-6">User</small></h4>
                        <small class="text-primary fw-semibold">{{ $processingOrders }} pesanan diproses</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div class="row g-4 mb-4">
    {{-- Monthly Revenue Chart --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-graph-up me-2 text-primary"></i>Pendapatan 12 Bulan Terakhir</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="revenueChart" height="110"></canvas>
            </div>
        </div>
    </div>

    {{-- Order Status Donut --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Status Pesanan</h5>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                <canvas id="statusChart" style="max-height:200px;"></canvas>
                <div class="mt-3 w-100" id="statusLegend" style="font-size:0.82rem;"></div>
            </div>
        </div>
    </div>
</div>

{{-- TOP PRODUCTS + ORDERS + CHATS --}}
<div class="row g-4">
    {{-- Top Products --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white p-4 border-bottom">
                <h5 class="fw-bold mb-0"><i class="bi bi-trophy me-2 text-warning"></i>Produk Terlaris</h5>
            </div>
            <div class="card-body p-0">
                @forelse($topProducts as $i => $product)
                <div class="d-flex align-items-center px-4 py-3 border-bottom">
                    <span class="badge rounded-pill me-3 {{ $i === 0 ? 'bg-warning text-dark' : ($i === 1 ? 'bg-secondary' : 'bg-light text-dark') }}"
                          style="width:28px;height:28px;line-height:20px;text-align:center;">{{ $i+1 }}</span>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-semibold text-truncate" style="max-width:180px;" title="{{ $product->product_name }}">{{ $product->product_name }}</div>
                        <small class="text-muted">Terjual: <strong>{{ $product->total_qty }} unit</strong></small>
                    </div>
                    <div class="text-end ms-2">
                        <small class="text-primary fw-bold">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</small>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-box-seam display-5 d-block mb-2 opacity-25"></i>
                    Belum ada data penjualan.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Pesanan Terbaru</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4 py-3">Order ID</th>
                                <th class="py-3">Pelanggan</th>
                                <th class="py-3">Total</th>
                                <th class="py-3">Status</th>
                                <th class="pe-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td class="ps-4 py-2 fw-semibold small">#{{ $order->order_code }}</td>
                                <td class="py-2 small">{{ $order->user->name }}</td>
                                <td class="py-2 fw-bold text-dark small">{{ $order->formatted_total }}</td>
                                <td class="py-2">
                                    <span class="badge bg-{{ $order->status_badge }} rounded-pill" style="font-size:0.7rem;">{{ $order->status_label }}</span>
                                </td>
                                <td class="pe-4 py-2 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light border rounded-circle" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data pesanan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Chats --}}
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-chat-dots me-2 text-primary"></i>Pesan Pelanggan</h5>
                <a href="{{ route('admin.chat.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentChats as $chat)
                        @php $latestMsg = $chat->messages->first(); @endphp
                        <a href="{{ route('admin.chat.show', $chat->id) }}" class="list-group-item list-group-item-action p-3 border-0 d-flex justify-content-between align-items-center border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="position-relative me-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary" style="width:38px;height:38px;display:flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-person fs-5"></i>
                                    </div>
                                    @if($chat->unread_count > 0)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size:0.6em;padding:3px 5px;">{{ $chat->unread_count }}</span>
                                    @endif
                                </div>
                                <div style="max-width:120px;">
                                    <h6 class="mb-0 fw-bold text-dark text-truncate small">{{ $chat->name }}</h6>
                                    <small class="text-muted text-truncate d-block" style="font-size:0.72rem;">{{ $latestMsg ? Str::limit($latestMsg->message, 30) : 'Belum ada pesan.' }}</small>
                                </div>
                            </div>
                            <div class="text-muted" style="font-size:0.7rem;white-space:nowrap;">
                                {{ $latestMsg ? $latestMsg->created_at->diffForHumans() : '' }}
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-chat-left-text display-4 mb-2 d-block opacity-25"></i>
                            Belum ada pesan masuk.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Monthly Revenue Chart ──────────────────────────────────────────────────
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($chartData) !!},
            backgroundColor: 'rgba(13, 110, 253, 0.15)',
            borderColor: 'rgba(13, 110, 253, 0.85)',
            borderWidth: 2,
            borderRadius: 6,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y)
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: { grid: { display: false } }
        }
    }
});

// ── Status Donut Chart ─────────────────────────────────────────────────────
const statusRaw = {!! json_encode($orderStatusData->map(fn($s) => $s->count)) !!};
const statusColors = {
    pending: '#ffc107', paid: '#0dcaf0', processing: '#0d6efd',
    shipped: '#6c757d', completed: '#198754', cancelled: '#dc3545'
};
const statusLabels = {
    pending: 'Menunggu Bayar', paid: 'Bayar Diterima', processing: 'Diproses',
    shipped: 'Dikirim', completed: 'Selesai', cancelled: 'Dibatalkan'
};
const statusKeys   = Object.keys(statusRaw);
const statusValues = Object.values(statusRaw);
const statusBgColors = statusKeys.map(k => statusColors[k] || '#aaa');

const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusKeys.map(k => statusLabels[k] || k),
        datasets: [{ data: statusValues, backgroundColor: statusBgColors, borderWidth: 2 }]
    },
    options: {
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});

// Build custom legend
const legend = document.getElementById('statusLegend');
statusKeys.forEach((k, i) => {
    legend.innerHTML += `<span class="me-2 d-inline-flex align-items-center gap-1 mb-1">
        <span style="width:10px;height:10px;border-radius:50%;background:${statusBgColors[i]};display:inline-block;"></span>
        ${statusLabels[k]||k}: <strong>${statusValues[i]}</strong>
    </span>`;
});
</script>
@endpush
@endsection


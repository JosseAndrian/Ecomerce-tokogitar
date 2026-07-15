@extends('layouts.admin')

@section('title', 'Laporan Penjualan - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Laporan Penjualan</h1>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row align-items-end g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-4 flex-grow-1"><i class="bi bi-filter me-2"></i>Filter</button>
                <a href="{{ route('admin.reports.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-outline-danger rounded-pill px-3" title="Cetak PDF">
                    <i class="bi bi-printer"></i>
                </a>
                <a href="{{ route('admin.reports.export-csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-outline-success rounded-pill px-3" title="Ekspor CSV">
                    <i class="bi bi-filetype-csv"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
            <div class="card-body p-4">
                <h6 class="text-uppercase mb-2" style="opacity: 0.8;">Total Pendapatan</h6>
                <h3 class="fw-bold mb-0">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                <small style="opacity: 0.8;">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</small>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3" width="50">No</th>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Order ID</th>
                        <th class="py-3">Pelanggan</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 py-3 text-end">Total Belanja</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4 py-3">{{ $loop->iteration }}</td>
                        <td class="py-3">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="py-3 fw-semibold">#{{ $order->order_code }}</td>
                        <td class="py-3">{{ $order->user->name }}</td>
                        <td class="py-3">
                            <span class="badge bg-{{ $order->status_badge }} rounded-pill">{{ $order->status_label }}</span>
                        </td>
                        <td class="pe-4 py-3 text-end fw-bold text-primary">{{ $order->formatted_total }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-folder-x display-4 d-block mb-3"></i>
                            Tidak ada data transaksi pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($orders->count() > 0)
                <tfoot class="bg-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end py-3">TOTAL PENDAPATAN</td>
                        <td class="pe-4 py-3 text-end text-primary fs-5">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection

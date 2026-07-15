<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Penjualan</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0 0 5px 0; font-size: 24px; }
        .header p { margin: 0; color: #666; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .footer { margin-top: 50px; text-align: right; }
        .signature { margin-top: 80px; text-decoration: underline; font-weight: bold; }
        @media print {
            body { padding: 0; }
            .btn-print { display: none; }
        }
        .btn-print {
            padding: 10px 20px; background-color: #0d6efd; color: white; 
            border: none; border-radius: 5px; cursor: pointer; margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <button class="btn-print" onclick="window.print()">Cetak Dokumen (Ctrl+P)</button>

    <div class="header">
        <h1>MUSIK STORE</h1>
        <p>Jl. Margonda Raya No. 100, Depok | Telp: 0812-3456-7890</p>
        <p>Website: www.musikstore.com | Email: cs@musikstore.com</p>
    </div>

    <div class="info">
        <h2 style="text-align: center; font-size: 18px; text-transform: uppercase;">Laporan Penjualan</h2>
        <p><strong>Periode:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s.d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">No. Pesanan</th>
                <th width="25%">Nama Pelanggan</th>
                <th width="15%">Status</th>
                <th class="text-right" width="20%">Total Belanja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td>{{ $order->order_code }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->status_label }}</td>
                <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4">Tidak ada data transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        @if($orders->count() > 0)
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN</td>
                <td class="text-right">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Depok, {{ now()->format('d F Y') }}</p>
        <p>Dibuat oleh,</p>
        <div class="signature">Administrator</div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>

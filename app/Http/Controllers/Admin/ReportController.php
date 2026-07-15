<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $orders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                       ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
                       ->latest()
                       ->get();

        $totalIncome = $orders->sum('total_price');

        return view('admin.reports.index', compact('orders', 'startDate', 'endDate', 'totalIncome'));
    }

    public function print(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $orders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                       ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
                       ->latest()
                       ->get();

        $totalIncome = $orders->sum('total_price');

        return view('admin.reports.print', compact('orders', 'startDate', 'endDate', 'totalIncome'));
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $orders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                       ->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
                       ->latest()
                       ->get();

        $filename = 'Laporan-Penjualan-' . $startDate . '-to-' . $endDate . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'Tanggal', 'Order ID', 'Pelanggan', 'Status', 'Total Belanja'];

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $index => $order) {
                fputcsv($file, [
                    $index + 1,
                    $order->created_at->format('d/m/Y'),
                    $order->order_code,
                    $order->user->name,
                    $order->status_label,
                    $order->total_price
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}

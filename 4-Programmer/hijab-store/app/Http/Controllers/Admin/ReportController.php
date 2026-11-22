<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private function authorizeOwner()
    {
        if (!in_array(Auth::user()->role ?? '', ['pemilik', 'admin'])) {
            abort(403, 'Akses ditolak. Halaman Rekap Penjualan hanya dapat diakses oleh Pemilik.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeOwner();

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date, 'Asia/Jakarta')->startOfDay()
            : Carbon::now('Asia/Jakarta')->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date, 'Asia/Jakarta')->endOfDay()
            : Carbon::now('Asia/Jakarta')->endOfDay();

        $orders = Order::with(['items.productVariant.product'])
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $summary = [
            'total_revenue'   => $orders->sum('total_amount'),
            'total_orders'    => $orders->count(),
            'total_items'     => $orders->sum(fn($o) => $o->items->sum('quantity')),
            'average_order'   => $orders->count() > 0 ? ($orders->sum('total_amount') / $orders->count()) : 0,
        ];

        return view('admin.reports.index', [
            'orders'    => $orders,
            'summary'   => $summary,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate'   => $endDate->format('Y-m-d'),
        ]);
    }

    public function export(Request $request)
    {
        $this->authorizeOwner();

        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date, 'Asia/Jakarta')->startOfDay()
            : Carbon::now('Asia/Jakarta')->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date, 'Asia/Jakarta')->endOfDay()
            : Carbon::now('Asia/Jakarta')->endOfDay();

        $orders = Order::with('items')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $filename = 'rekap-penjualan-shea-daily-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 agar karakter terbaca rapi di Microsoft Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['No. Pesanan', 'Tanggal', 'Nama Pembeli', 'No. HP', 'Kurir', 'Jumlah Item', 'Status Pesanan', 'Total Pembayaran (Rp)']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    '#' . $order->id,
                    $order->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i'),
                    $order->customer_name,
                    $order->customer_phone ?? '-',
                    strtoupper($order->courier ?? '-'),
                    $order->items->sum('quantity'),
                    ucfirst($order->order_status ?? 'pending'),
                    $order->total_amount,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
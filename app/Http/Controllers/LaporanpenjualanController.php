<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use App\Models\LaporanPenjualan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal dari request, jika tidak ada set ke bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        
        // Adjust the date range to include the full day
        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();
        
        $viewMode = $request->input('view_mode', 'daily');

        // Query untuk data chart (penghasilan per periode)
        if ($viewMode == 'daily') {
            $chartData = DB::table('transaksi')
                ->whereBetween('tanggal_transaksi', [$startDateTime, $endDateTime])
                ->select(
                    DB::raw('DATE(tanggal_transaksi) as period'),
                    DB::raw('SUM(total_harga) as total')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            $chartLabels = $chartData->pluck('period');
            $chartValues = $chartData->pluck('total');
        } else {
            $chartData = DB::table('transaksi')
                ->whereBetween('tanggal_transaksi', [$startDateTime, $endDateTime])
                ->select(
                    DB::raw('MONTH(tanggal_transaksi) as month'),
                    DB::raw('YEAR(tanggal_transaksi) as year'),
                    DB::raw('SUM(total_harga) as total')
                )
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get()
                ->map(function ($item) {
                    $monthName = Carbon::createFromDate($item->year, $item->month, 1)->format('M Y');
                    return [
                        'period' => $monthName,
                        'total' => $item->total
                    ];
                });

            $chartLabels = $chartData->pluck('period');
            $chartValues = $chartData->pluck('total');
        }

        // Check if customer columns exist
        $hasCustomerColumns = Schema::hasColumns('transaksi', ['customer_name', 'customer_phone']);

        // Base query
        $query = DB::table('transaksi_details')
            ->join('transaksi', 'transaksi.id', '=', 'transaksi_details.transaksi_id')
            ->join('produks', 'produks.id', '=', 'transaksi_details.produk_id');

        // Select fields based on available columns
        $selectFields = [
            'transaksi.id as transaksi_id',
            'transaksi.tanggal_transaksi',
            'produks.nama_produk',
            'transaksi_details.quantity as total_terjual',
            'transaksi_details.subtotal as total_penjualan'
        ];

        if ($hasCustomerColumns) {
            $selectFields[] = 'transaksi.customer_name';
            $selectFields[] = 'transaksi.customer_phone';
        }

        // Build and execute query
        $laporan = $query->select($selectFields)
            ->whereBetween('transaksi.tanggal_transaksi', [$startDateTime, $endDateTime])
            ->orderBy('transaksi.tanggal_transaksi', 'desc')
            ->paginate(20);

        // Statistik ringkasan
        $summary = [
            'total_penjualan' => DB::table('transaksi')
                ->whereBetween('tanggal_transaksi', [$startDateTime, $endDateTime])
                ->sum('total_harga'),
            'jumlah_transaksi' => DB::table('transaksi')
                ->whereBetween('tanggal_transaksi', [$startDateTime, $endDateTime])
                ->count()
        ];

        return view('pages.admin.laporan_penjualan.index', compact(
            'laporan', 
            'startDate', 
            'endDate', 
            'viewMode',
            'chartLabels',
            'chartValues',
            'summary',
            'hasCustomerColumns'
        ));
    }

    public function generatePdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Check if customer columns exist
        $hasCustomerColumns = Schema::hasColumns('transaksi', ['customer_name', 'customer_phone']);

        // Base query
        $query = DB::table('transaksi_details')
            ->join('transaksi', 'transaksi.id', '=', 'transaksi_details.transaksi_id')
            ->join('produks', 'produks.id', '=', 'transaksi_details.produk_id');

        // Select fields based on available columns
        $selectFields = [
            'transaksi.id as transaksi_id',
            'transaksi.tanggal_transaksi',
            DB::raw('GROUP_CONCAT(produks.nama_produk SEPARATOR ", ") as nama_produk'),
            DB::raw('SUM(transaksi_details.quantity) as total_terjual'),
            DB::raw('SUM(transaksi_details.subtotal) as total_penjualan')
        ];

        $groupByFields = ['transaksi.id', 'transaksi.tanggal_transaksi'];

        if ($hasCustomerColumns) {
            $selectFields[] = 'transaksi.customer_name';
            $selectFields[] = 'transaksi.customer_phone';
            $groupByFields[] = 'transaksi.customer_name';
            $groupByFields[] = 'transaksi.customer_phone';
        }

        // Build and execute query
        $laporan = $query->select($selectFields)
            ->whereBetween('transaksi.tanggal_transaksi', [$startDate, $endDate])
            ->groupBy($groupByFields)
            ->orderBy('transaksi.tanggal_transaksi', 'desc')
            ->get();

        // Statistik untuk PDF
        $summary = [
            'total_penjualan' => DB::table('transaksi')
                ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                ->sum('total_harga'),
            'jumlah_transaksi' => DB::table('transaksi')
                ->whereBetween('tanggal_transaksi', [$startDate, $endDate])
                ->count(),
            'produk_terjual' => DB::table('transaksi_details')
                ->join('transaksi', 'transaksi.id', '=', 'transaksi_details.transaksi_id')
                ->whereBetween('transaksi.tanggal_transaksi', [$startDate, $endDate])
                ->sum('transaksi_details.quantity')
        ];
        
        $data = [
            'laporan' => $laporan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => $summary,
            'tanggal_cetak' => Carbon::now()->format('d-m-Y H:i:s'),
            'hasCustomerColumns' => $hasCustomerColumns
        ];

        $pdf = Pdf::loadView('pages.admin.laporan_penjualan.pdf', $data);
        
        return $pdf->download('laporan-penjualan-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    // Menyimpan data laporan penjualan ke database (jika diperlukan)
    public function store($tanggal)
    {
        // Generate laporan untuk tanggal tertentu dan simpan ke database
        $data = DB::table('transaksi')
            ->whereDate('tanggal_transaksi', $tanggal)
            ->select(
                DB::raw('COUNT(id) as jumlah_transaksi'),
                DB::raw('SUM(total_harga) as total_penjualan')
            )
            ->first();
            
        $produkTerjual = DB::table('transaksi_details')
            ->join('transaksi', 'transaksi.id', '=', 'transaksi_details.transaksi_id')
            ->whereDate('transaksi.tanggal_transaksi', $tanggal)
            ->sum('transaksi_details.quantity');
            
        // Simpan ke database
        LaporanPenjualan::updateOrCreate(
            ['tanggal' => $tanggal],
            [
                'total_penjualan' => $data->total_penjualan ?? 0,
                'total_produk_terjual' => $produkTerjual ?? 0,
                'jumlah_transaksi' => $data->jumlah_transaksi ?? 0
            ]
        );
        
        return redirect()->back()->with('success', 'Laporan berhasil disimpan');
    }
}

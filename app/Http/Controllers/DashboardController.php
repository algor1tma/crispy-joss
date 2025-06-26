<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Super;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Produk;
use App\Models\RawMaterial;
use App\Models\ProductRecipe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $myData = auth()->user();

        if ($myData->roles === 'super') {
            $myData = Super::where('user_id', auth()->id())->first();

            // Get statistics for super dashboard
            $totalUsers = User::whereIn('roles', ['admin', 'karyawan'])->count();
            $totalAdmins = User::where('roles', 'admin')->count();
            $totalKaryawan = User::where('roles', 'karyawan')->count();

            // Get recent users
            $recentUsers = User::whereIn('roles', ['admin', 'karyawan'])
                ->with(['admin', 'karyawan'])
                ->latest()
                ->take(5)
                ->get();

            return view('pages.dashboard.dashboard', compact(
                'myData',
                'totalUsers',
                'totalAdmins',
                'totalKaryawan',
                'recentUsers'
            ));
        } elseif ($myData->roles === 'admin') {
            // Get daily transactions count
            $dailyTransactions = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->count();

            // Get monthly revenue and expenses
            $monthlyRevenue = Transaksi::whereYear('tanggal_transaksi', Carbon::now()->year)
                ->whereMonth('tanggal_transaksi', Carbon::now()->month)
                ->sum('total_harga');

            $monthlyExpenses = DB::table('raw_material_logs')
                ->where('type', 'in')
                ->whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('subtotal');

            // Get user statistics
            $userStats = [
                'total' => User::count(),
                'admin' => User::where('roles', 'admin')->count(),
                'kasir' => User::where('roles', 'karyawan')->count(),
                'active' => User::whereIn('roles', ['admin', 'karyawan'])->count()
            ];

            // Get product recipe statistics
            $productRecipeStats = [
                'total_products' => Produk::count(),
                'with_recipe' => Produk::has('recipes')->count(),
                'without_recipe' => Produk::doesntHave('recipes')->count()
            ];

            // Get low stock materials
            $lowStockMaterials = RawMaterial::select(
                'name',
                'stock as current_stock',
                DB::raw('10 as minimum_stock'), // You can adjust this value or make it dynamic
                'unit'
            )
            ->whereRaw('stock <= 10')
            ->get();

            // Get products with low materials
            $productsWithLowMaterials = DB::table('produks')
                ->join('product_recipes', 'produks.id', '=', 'product_recipes.produk_id')
                ->join('raw_materials', 'product_recipes.raw_material_id', '=', 'raw_materials.id')
                ->select(
                    'produks.nama_produk',
                    'raw_materials.name as material_name',
                    'raw_materials.stock as current_stock',
                    DB::raw('10 as minimum_stock') // You can adjust this value or make it dynamic
                )
                ->whereRaw('raw_materials.stock <= 10')
                ->get();

            // Get revenue data for the last 30 days
            $revenueData = $this->getRevenueData();

            // Get summary data (produk terjual dan terpopuler)
            $summary = [
                'produk_terjual' => DB::table('transaksi_details')
                    ->join('transaksi', 'transaksi.id', '=', 'transaksi_details.transaksi_id')
                    ->whereMonth('transaksi.tanggal_transaksi', Carbon::now()->month)
                    ->whereYear('transaksi.tanggal_transaksi', Carbon::now()->year)
                    ->sum('transaksi_details.quantity'),
                'produk_terpopuler' => DB::table('transaksi_details')
                    ->join('transaksi', 'transaksi.id', '=', 'transaksi_details.transaksi_id')
                    ->join('produks', 'produks.id', '=', 'transaksi_details.produk_id')
                    ->whereMonth('transaksi.tanggal_transaksi', Carbon::now()->month)
                    ->whereYear('transaksi.tanggal_transaksi', Carbon::now()->year)
                    ->select(
                        'produks.nama_produk',
                        DB::raw('SUM(transaksi_details.quantity) as total_terjual')
                    )
                    ->groupBy('produks.nama_produk')
                    ->orderBy('total_terjual', 'desc')
                    ->first()
            ];

            return view('pages.dashboard.dashboard', compact(
                'myData',
                'dailyTransactions',
                'monthlyRevenue',
                'monthlyExpenses',
                'userStats',
                'productRecipeStats',
                'lowStockMaterials',
                'productsWithLowMaterials',
                'revenueData',
                'summary'
            ));
        } elseif ($myData->roles === 'karyawan') {
            // Get daily transactions count for karyawan
            $dailyTransactions = Transaksi::whereDate('tanggal_transaksi', Carbon::today())->count();

            // Get product recipe statistics
            $productRecipeStats = [
                'total_products' => Produk::count(),
                'with_recipe' => Produk::has('recipes')->count(),
                'without_recipe' => Produk::doesntHave('recipes')->count()
            ];

            // Get low stock materials
            $lowStockMaterials = RawMaterial::select(
                'name',
                'stock as current_stock',
                DB::raw('10 as minimum_stock'), // You can adjust this value or make it dynamic
                'unit'
            )
            ->whereRaw('stock <= 10')
            ->get();

            // Get products with low materials
            $productsWithLowMaterials = DB::table('produks')
                ->join('product_recipes', 'produks.id', '=', 'product_recipes.produk_id')
                ->join('raw_materials', 'product_recipes.raw_material_id', '=', 'raw_materials.id')
                ->select(
                    'produks.nama_produk',
                    'raw_materials.name as material_name',
                    'raw_materials.stock as current_stock',
                    DB::raw('10 as minimum_stock') // You can adjust this value or make it dynamic
                )
                ->whereRaw('raw_materials.stock <= 10')
                ->get();

            return view('pages.dashboard.dashboard', compact(
                'myData',
                'dailyTransactions',
                'productRecipeStats',
                'lowStockMaterials',
                'productsWithLowMaterials'
            ));
        } elseif ($myData->roles === 'pelanggan') {
            // Get customer specific data
            $countReservasi = Transaksi::where('pelanggan_id', $myData->id)
                ->whereDate('tanggal_transaksi', '>=', Carbon::today())
                ->count();

            return view('pages.dashboard.dashboard', compact('myData', 'countReservasi'));
        } elseif ($myData->roles === 'super') {
            // Get all data for super admin
            $totalTransaksi = Transaksi::count();
            $totalUser = User::count();
            $totalProduk = Produk::count();
            $totalMaterial = RawMaterial::count();

            // Get daily, monthly, and yearly statistics
            $dailyStats = $this->getDailyStats();
            $monthlyStats = $this->getMonthlyStats();
            $yearlyStats = $this->getYearlyStats();

            return view('pages.dashboard.dashboard', compact(
                'myData',
                'totalTransaksi',
                'totalUser',
                'totalProduk',
                'totalMaterial',
                'dailyStats',
                'monthlyStats',
                'yearlyStats'
            ));
        }
    }

    private function getRevenueData()
    {
        $dates = [];
        $amounts = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $amount = Transaksi::whereDate('tanggal_transaksi', $date)
                ->sum('total_harga');

            $dates[] = $date->format('d M');
            $amounts[] = $amount;
        }

        return [
            'dates' => $dates,
            'amounts' => $amounts
        ];
    }

    private function getDailyStats()
    {
        // Implementasi pengambilan data statistik harian
    }

    private function getMonthlyStats()
    {
        // Implementasi pengambilan data statistik bulanan
    }

    private function getYearlyStats()
    {
        // Implementasi pengambilan data statistik tahunan
    }
}

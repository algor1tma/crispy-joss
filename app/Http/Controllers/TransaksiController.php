<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Tampilkan daftar transaksi
    public function index()
    {
        $transaksis = Transaksi::orderBy('tanggal_transaksi', 'desc')->get();
        return view('pages.admin.Transaksi.index', compact('transaksis'));
    }

    // Tampilkan form tambah transaksi
    public function create()
    {
        $produks = Produk::all();
        return view('pages.admin.Transaksi.create', compact('produks'));
    }

    // Simpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'waktu_transaksi' => 'required',
            'total_harga' => 'required|numeric|min:0',
            'nama_pelanggan' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Generate kode transaksi
            $lastTransaksi = Transaksi::latest()->first();
            $lastNumber = $lastTransaksi ? intval(substr($lastTransaksi->kode_transaksi, -4)) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . $newNumber;

            // Buat transaksi baru
            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'tanggal_transaksi' => Carbon::parse($request->tanggal_transaksi . ' ' . $request->waktu_transaksi),
                'total_harga' => $request->total_harga,
                'customer_name' => $request->nama_pelanggan,
                'customer_phone' => $request->no_telp,
                'user_id' => auth()->id(),
            ]);

            // Simpan detail transaksi
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                
                // Validasi stok
                if ($produk->stok_produk < $item['jumlah']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi!");
                }

                // Kurangi stok produk
                $produk->decrement('stok_produk', $item['jumlah']);

                // Simpan detail transaksi
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk_id'],
                    'quantity' => $item['jumlah'],
                    'price' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    // Tampilkan form edit transaksi
    public function edit($id)
    {
        $transaksi = Transaksi::with('details.produk')->findOrFail($id);
        $produks = Produk::all();
        return view('pages.admin.Transaksi.edit', compact('transaksi', 'produks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'waktu_transaksi' => 'required',
            'total_harga' => 'required|numeric|min:0',
            'nama_pelanggan' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.jumlah' => 'required|numeric|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);
            
            // Update data transaksi
            $transaksi->update([
                'tanggal_transaksi' => Carbon::parse($request->tanggal_transaksi . ' ' . $request->waktu_transaksi),
                'total_harga' => $request->total_harga,
                'customer_name' => $request->nama_pelanggan,
                'customer_phone' => $request->no_telp,
                'user_id' => auth()->id(),
            ]);

            // Kembalikan stok produk dari detail transaksi lama
            foreach ($transaksi->details as $detail) {
                $detail->produk->increment('stok_produk', $detail->quantity);
            }

            // Hapus detail transaksi lama
            $transaksi->details()->delete();

            // Simpan detail transaksi baru
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                
                // Validasi stok
                if ($produk->stok_produk < $item['jumlah']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak mencukupi!");
                }

                // Kurangi stok produk
                $produk->decrement('stok_produk', $item['jumlah']);

                // Simpan detail transaksi
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk_id'],
                    'quantity' => $item['jumlah'],
                    'price' => $item['harga'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            DB::commit();
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    // Hapus transaksi
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
    
    // Export to CSV
    public function exportExcel()
    {
        $transaksis = Transaksi::with('details.produk')->orderBy('tanggal_transaksi', 'desc')->get();
        
        $filename = 'laporan_transaksi_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $columns = ['No', 'ID Transaksi', 'Tanggal', 'Total Harga', 'Detail Produk'];
        
        $callback = function() use ($transaksis, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($transaksis as $index => $transaksi) {
                $details = [];
                foreach($transaksi->details as $detail) {
                    $details[] = $detail->produk->nama_produk . ' (' . $detail->jumlah . ' x ' . number_format($detail->harga_satuan, 0, ',', '.') . ')';
                }
                
                fputcsv($file, [
                    $index + 1,
                    '#' . $transaksi->id,
                    $transaksi->tanggal_transaksi,
                    $transaksi->total_harga,
                    implode(', ', $details)
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

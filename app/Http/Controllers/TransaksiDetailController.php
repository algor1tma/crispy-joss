<?php

namespace App\Http\Controllers;

use App\Models\TransaksiDetail;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;

class TransaksiDetailController extends Controller
{
    public function index()
    {
        $details = TransaksiDetail::with(['transaksi', 'produk'])->get();
        return view('pages.admin.Transaksi_detail.index', compact('details'));
    }

    public function create()
    {
        $transaksis = Transaksi::all();
        $produks = Produk::all();
        return view('pages.admin.Transaksi_detail.create', compact('transaksis', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'produk_id' => 'required|exists:produks,id',
            'price' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        TransaksiDetail::create($request->all());

        return redirect()->route('transaksidetail.index')->with('success', 'Detail transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $detail = TransaksiDetail::findOrFail($id);
        $transaksis = Transaksi::all();
        $produks = Produk::all();
        return view('pages.admin.Transaksi_detail.edit', compact('detail', 'transaksis', 'produks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'transaksi_id' => 'required|exists:transaksi,id',
            'produk_id' => 'required|exists:produks,id',
            'price' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $detail = TransaksiDetail::findOrFail($id);
        $detail->update($request->all());

        return redirect()->route('transaksidetail.index')->with('success', 'Detail transaksi berhasil diupdate.');
    }

    public function destroy($id)
    {
        $detail = TransaksiDetail::findOrFail($id);
        $detail->delete();

        return redirect()->route('transaksidetail.index')->with('success', 'Detail transaksi berhasil dihapus.');
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['details.produk'])->findOrFail($id);
        $details = $transaksi->details;
        
        return view('pages.admin.Transaksi_detail.show', [
            'details' => $details,
            'transaksi' => $transaksi,
            'tanggal' => \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-m-Y'),
        ]);
    }
}

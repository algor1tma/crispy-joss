<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\RawMaterial;
use App\Models\RawMaterialLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class PosController extends Controller
{
    public function __construct()
    {
        // Check and add customer columns if they don't exist
        if (!Schema::hasColumn('transaksi', 'customer_name')) {
            DB::statement('ALTER TABLE transaksi ADD COLUMN customer_name VARCHAR(255) AFTER tanggal_transaksi');
        }
        if (!Schema::hasColumn('transaksi', 'customer_phone')) {
            DB::statement('ALTER TABLE transaksi ADD COLUMN customer_phone VARCHAR(255) AFTER customer_name');
        }
    }
    public function index()
    {
        $produks = Produk::with(['recipes.rawMaterial'])->get();

        // Transform products with availability info
        $produks = $produks->map(function ($produk) {
            return [
                'id' => $produk->id,
                'nama_produk' => $produk->nama_produk,
                'harga_produk' => $produk->harga_produk,
                'stok_produk' => $produk->stok_produk,
                'foto' => $produk->foto,
                'deskripsi_produk' => $produk->deskripsi_produk,
                'recipes' => $produk->recipes,
                'can_sell' => $this->canSellProduct($produk),
                'max_sellable' => $this->getMaxSellableQuantity($produk),
                'missing_materials' => $this->getMissingMaterialsForSale($produk),
                'has_recipe' => $produk->hasCompleteRecipe(),
            ];
        });

        return view('pages.admin.pos.index', compact('produks'));
    }

    private function generateTransactionCode()
    {
        $today = now()->format('Ymd');
        $lastTransaction = Transaksi::whereDate('created_at', today())->orderBy('kode_transaksi', 'desc')->first();

        if ($lastTransaction) {
            // Extract the numeric part and increment
            $lastNumber = (int) substr($lastTransaction->kode_transaksi, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return 'TRX-' . $today . '-' . $newNumber;
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'price' => 'required|array',
            'total_harga' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generate kode transaksi
            $kodeTransaksi = $this->generateTransactionCode();

            // Buat transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'tanggal_transaksi' => now(),
                'total_harga' => $request->total_harga,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'user_id' => auth()->id(), // Tambahkan user_id dari user yang login
                'status' => 'completed',
            ]);

            // Proses setiap item
            foreach ($request->product_id as $index => $productId) {
                $produk = Produk::with('recipes.rawMaterial')->findOrFail($productId);
                $quantity = $request->quantity[$index];
                $price = $request->price[$index];

                // Validasi stok produk
                if ($produk->stok_produk < $quantity) {
                    throw new \Exception("Stok tidak mencukupi untuk produk {$produk->nama_produk}");
                }

                // Jika produk memiliki resep, kurangi stok bahan baku
                if ($produk->hasCompleteRecipe()) {
                    foreach ($produk->recipes as $recipe) {
                        $requiredAmount = $recipe->quantity * $quantity;

                        // Cek apakah stok bahan baku mencukupi
                        if (!$recipe->hasSufficientStock($quantity)) {
                            $materialName = $recipe->rawMaterial->name;
                            $available = $recipe->rawMaterial->getStockInUnit($recipe->unit);
                            $required = $requiredAmount;
                            throw new \Exception("Stok bahan baku {$materialName} tidak mencukupi. Tersedia: {$available} {$recipe->unit}, Dibutuhkan: {$required} {$recipe->unit}");
                        }
                    }

                    // Konsumsi bahan baku untuk produksi
                    foreach ($produk->recipes as $recipe) {
                        $notes = "Penjualan produk {$produk->nama_produk} - Transaksi {$kodeTransaksi} - Qty: {$quantity}";
                        $recipe->consumeForProduction($quantity, $notes);
                    }
                }

                // Kurangi stok produk
                $produk->stok_produk -= $quantity;
                $produk->save();

                // Buat detail transaksi
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $price * $quantity,
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'transaction_id' => $transaksi->id,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Transaction error: ' . $e->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                400,
            );
        }
    }

    public function printReceipt($id)
    {
        try {
            $transaction = Transaksi::with('details.produk')->findOrFail($id);
            return view('pages.admin.pos.receipt', compact('transaction'));
        } catch (\Exception $e) {
            Log::error('Print receipt error: ' . $e->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal memuat struk: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Check if product can be sold (considering both product stock and raw materials)
     */
    private function canSellProduct($produk, $quantity = 1)
    {
        // Check product stock
        if ($produk->stok_produk < $quantity) {
            return false;
        }

        // If product has recipe, check raw materials
        if ($produk->hasCompleteRecipe()) {
            return $produk->canProduce($quantity);
        }

        return true;
    }

    /**
     * Get maximum sellable quantity considering both product stock and raw materials
     */
    private function getMaxSellableQuantity($produk)
    {
        $maxFromStock = $produk->stok_produk;

        // If no recipe, return product stock
        if (!$produk->hasCompleteRecipe()) {
            return $maxFromStock;
        }

        // Get max producible from raw materials
        $maxFromMaterials = $produk->getMaxProducibleQuantity();

        // Return the minimum of both
        return min($maxFromStock, $maxFromMaterials);
    }

    /**
     * Get missing materials for selling a product
     */
    private function getMissingMaterialsForSale($produk, $quantity = 1)
    {
        if (!$produk->hasCompleteRecipe()) {
            return [];
        }

        return $produk->getMissingMaterials($quantity);
    }

    /**
     * Check product availability via AJAX
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produks,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $produk = Produk::with(['recipes.rawMaterial'])->findOrFail($request->product_id);
        $quantity = $request->quantity;

        $canSell = $this->canSellProduct($produk, $quantity);
        $maxSellable = $this->getMaxSellableQuantity($produk);
        $missingMaterials = $this->getMissingMaterialsForSale($produk, $quantity);

        return response()->json([
            'can_sell' => $canSell,
            'max_sellable' => $maxSellable,
            'product_stock' => $produk->stok_produk,
            'has_recipe' => $produk->hasCompleteRecipe(),
            'missing_materials' => $missingMaterials,
            'message' => $canSell ? 'Produk tersedia' : 'Stok tidak mencukupi',
        ]);
    }
}

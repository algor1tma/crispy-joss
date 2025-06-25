<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\RawMaterial;
use App\Models\ProductRecipe;
use App\Models\RawMaterialLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    // Tampilkan daftar produk
    public function index()
    {
        $produks = Produk::all();
        return view('pages.admin.produk.index', compact('produks'));
    }

    // Tampilkan form tambah produk
    public function create()
    {
        $materials = RawMaterial::orderBy('name')->get();
        return view('pages.admin.produk.create', compact('materials'));
    }

    // Simpan data produk baru
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_produk' => 'required',
            'harga_produk' => 'required|numeric',
            'stok_produk' => 'required|numeric',
            'deskripsi_produk' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'recipes' => 'array'
        ]);

        DB::beginTransaction();
        try {
            // Create product
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('img/produk'), $fotoName);
                $validatedData['foto'] = $fotoName;
            }

            $produk = Produk::create($validatedData);

            // Save recipes if provided
            if ($request->has('recipes')) {
                foreach ($request->recipes as $recipe) {
                    if (!empty($recipe['material_id'])) {
                        // Create recipe without timestamps
                        ProductRecipe::create([
                            'produk_id' => $produk->id,
                            'raw_material_id' => $recipe['material_id'],
                            'quantity' => $recipe['quantity'],
                            'unit' => $recipe['unit'],
                            'notes' => $recipe['notes'] ?? null
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Tampilkan form edit produk
    public function edit($id)
    {
        $produk = Produk::with('recipes.rawMaterial')->findOrFail($id);
        $materials = RawMaterial::orderBy('name')->get();
        return view('pages.admin.produk.edit', compact('produk', 'materials'));
    }

    // Update data produk
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_produk' => 'required',
            'harga_produk' => 'required|numeric',
            'stok_produk' => 'required|numeric',
            'deskripsi_produk' => 'nullable',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'recipes' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($id);
            $oldStock = $produk->stok_produk;
            $newStock = $validatedData['stok_produk'];
            $stockDifference = $newStock - $oldStock;

            if ($request->hasFile('foto')) {
                if ($produk->foto && $produk->foto !== 'default_foto.jpg') {
                    $oldPhotoPath = public_path('img/produk/' . $produk->foto);
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                $foto = $request->file('foto');
                $fotoName = time() . '.' . $foto->getClientOriginalExtension();
                $foto->move(public_path('img/produk'), $fotoName);
                $validatedData['foto'] = $fotoName;
            }

            // Update recipes terlebih dahulu
            $oldRecipes = $produk->recipes()->get(); // Simpan resep lama
            
            if ($request->has('recipes')) {
                // Delete existing recipes
                ProductRecipe::where('produk_id', $produk->id)->delete();

                // Add new recipes
                foreach ($request->recipes as $recipe) {
                    if (!empty($recipe['material_id'])) {
                        ProductRecipe::create([
                            'produk_id' => $produk->id,
                            'raw_material_id' => $recipe['material_id'],
                            'quantity' => $recipe['quantity'],
                            'unit' => $recipe['unit'],
                            'notes' => $recipe['notes'] ?? null
                        ]);
                    }
                }
            }

            // Refresh produk data untuk mendapatkan resep terbaru
            $produk->refresh();

            // Jika ada penambahan stok
            if ($stockDifference > 0) {
                // Jika produk memiliki resep
                if ($produk->recipes()->exists()) {
                    foreach ($produk->recipes as $recipe) {
                        $material = RawMaterial::find($recipe->raw_material_id);
                        if (!$material) {
                            throw new \Exception("Bahan baku tidak ditemukan untuk resep.");
                        }

                        $requiredQuantity = $recipe->quantity * $stockDifference;

                        // Validasi stok bahan baku mencukupi
                        if ($material->stock < $requiredQuantity) {
                            throw new \Exception("Stok bahan baku {$material->name} tidak mencukupi untuk menambah stok produk. Dibutuhkan: {$requiredQuantity} {$material->unit}, Tersedia: {$material->stock} {$material->unit}");
                        }

                        // Kurangi stok bahan baku
                        $material->decrement('stock', $requiredQuantity);

                        // Catat pengurangan di log
                        RawMaterialLog::create([
                            'raw_material_id' => $material->id,
                            'user_id' => auth()->id(),
                            'type' => 'production',
                            'quantity' => $requiredQuantity,
                            'price' => $material->price,
                            'subtotal' => $requiredQuantity * $material->price,
                            'notes' => "Pengurangan untuk produksi {$produk->nama_produk} (Penambahan stok: {$stockDifference})"
                        ]);
                    }
                }
            }

            // Update produk setelah semua validasi berhasil
            $produk->update($validatedData);

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Hapus produk
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($id);
            
            // Delete recipes first
            ProductRecipe::where('produk_id', $produk->id)->delete();
            
            // Delete photo if exists
            if ($produk->foto && $produk->foto !== 'default_foto.jpg') {
                $photoPath = public_path('img/produk/' . $produk->foto);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
            
            $produk->delete();
            
            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

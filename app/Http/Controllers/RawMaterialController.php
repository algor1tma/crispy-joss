<?php

namespace App\Http\Controllers; // Deklarasi namespace untuk kelas kontroler ini. Ini membantu mengorganisir kode dan mencegah konflik nama kelas.

use App\Models\RawMaterial; // Mengimpor model `RawMaterial`. Model ini merepresentasikan tabel 'raw_materials' di database dan digunakan untuk berinteraksi dengan data bahan baku.
use App\Models\RawMaterialLog; // Mengimpor model `RawMaterialLog`. Model ini merepresentasikan tabel 'raw_material_logs' dan digunakan untuk mencatat setiap perubahan stok bahan baku (misalnya, masuk, keluar, penyesuaian).
use App\Models\Supplier; // Mengimpor model `Supplier`. Meskipun tidak digunakan secara langsung di semua metode yang ditampilkan, model ini mungkin digunakan di bagian lain aplikasi atau untuk tampilan terkait (misalnya, di form pembelian untuk memilih supplier).
use Illuminate\Http\Request; // Mengimpor kelas `Request`. Objek Request digunakan untuk mengakses data yang dikirimkan melalui permintaan HTTP (seperti data dari form, parameter URL, dll.).
use Illuminate\Support\Facades\DB; // Mengimpor facade `DB`. Ini menyediakan metode untuk berinteraksi langsung dengan database, termasuk menjalankan transaksi database. Transaksi sangat penting untuk memastikan beberapa operasi database berhasil atau gagal secara bersamaan (atomik).

class RawMaterialController extends Controller // Deklarasi kelas `RawMaterialController` yang mewarisi (extends) dari kelas `Controller` dasar Laravel. Ini memberikan akses ke berbagai fitur dasar kontroler.
{
    /**
     * Metode `index()`: Menampilkan daftar semua bahan baku.
     * Metode ini berfungsi sebagai halaman utama untuk manajemen bahan baku, menampilkan semua item yang tersedia.
     *
     * @return \Illuminate\View\View Mengembalikan instance view Laravel yang akan ditampilkan ke pengguna.
     */
    public function index()
    {
        // Mengambil semua data bahan baku dari tabel 'raw_materials'.
        // `RawMaterial::orderBy('name')` mengurutkan hasil berdasarkan kolom 'name' secara ascending (A-Z).
        // `->get()` mengeksekusi query dan mengembalikan koleksi (Collection) dari objek RawMaterial.
        $materials = RawMaterial::orderBy('name')->get();

        // Mengirim data bahan baku ($materials) ke view.
        // `view('raw-materials.index')` merujuk ke file view Blade di `resources/views/raw-materials/index.blade.php`.
        // `compact('materials')` adalah cara singkat untuk mengirim variabel `$materials` ke view dengan nama yang sama.
        return view('raw-materials.index', compact('materials'));
    }

    /**
     * Metode `create()`: Menampilkan form untuk membuat bahan baku baru.
     * Metode ini bertanggung jawab untuk menampilkan antarmuka pengguna untuk menambahkan bahan baku baru ke sistem.
     *
     * @return \Illuminate\View\View Mengembalikan instance view Laravel yang berisi form.
     */
    public function create()
    {
        // Mengirim pengguna ke view 'raw-materials.create', yang berisi form input untuk data bahan baku baru.
        // View ini biasanya ada di `resources/views/raw-materials/create.blade.php`.
        return view('raw-materials.create');
    }

    /**
     * Metode `store()`: Menyimpan data bahan baku baru yang dikirim dari form `create()`.
     * Metode ini menerima data dari permintaan POST dan menyimpannya ke database setelah validasi.
     *
     * @param  \Illuminate\Http\Request  $request Objek Request yang berisi semua data input dari form.
     * @return \Illuminate\Http\RedirectResponse Mengarahkan pengguna kembali ke halaman lain dengan pesan status.
     */
    public function store(Request $request)
    {
        // Melakukan validasi data yang diterima dari request.
        // Ini memastikan bahwa data yang masuk ke database sesuai dengan aturan yang ditentukan.
        $request->validate([
            'name' => 'required', // Nama bahan baku wajib diisi.
            'stock' => 'required|numeric|min:0', // Stok wajib diisi, harus berupa angka, dan tidak boleh kurang dari 0.
            'unit' => 'required', // Satuan (misalnya, kg, liter) wajib diisi.
            'price' => 'required|numeric|min:0', // Harga wajib diisi, harus berupa angka, dan tidak boleh kurang dari 0.
            'description' => 'nullable', // Deskripsi boleh kosong (tidak wajib).
            'type' => 'required|in:in,adjustment' // Tipe transaksi awal harus 'in' (masuk) atau 'adjustment' (penyesuaian).
        ]);

        // Membuat entri bahan baku baru di tabel 'raw_materials' menggunakan data yang tervalidasi.
        // `RawMaterial::create()` adalah metode Eloquent yang membuat dan menyimpan record baru ke database.
        $material = RawMaterial::create([
            'name' => $request->name, // Mengambil nilai 'name' dari request.
            'description' => $request->description, // Mengambil nilai 'description' dari request.
            'stock' => $request->stock, // Mengambil nilai 'stock' dari request.
            'unit' => $request->unit, // Mengambil nilai 'unit' dari request.
            'price' => $request->price, // Mengambil nilai 'price' dari request.
            'minimum_stock' => 10 // Menetapkan nilai default 'minimum_stock' menjadi 10.
        ]);

        // Memeriksa apakah stok awal yang dimasukkan lebih dari 0.
        if ($material->stock > 0) {
            // Jika stok lebih dari 0, buat log di tabel 'raw_material_logs'.
            // Ini mencatat entri awal bahan baku ke dalam sistem.
            RawMaterialLog::create([
                'raw_material_id' => $material->id, // ID bahan baku yang baru saja dibuat.
                'user_id' => auth()->id(), // ID pengguna yang sedang login (yang melakukan operasi ini).
                'type' => $request->type, // Tipe log, diambil dari input 'type' form (e.g., 'in' atau 'adjustment').
                'quantity' => $material->stock, // Kuantitas stok yang dicatat dalam log.
                'price' => $material->price, // Harga per unit bahan baku saat log dibuat.
                'subtotal' => $material->stock * $material->price, // Subtotal (kuantitas * harga).
                // Catatan log disesuaikan berdasarkan tipe: 'Stok awal bahan baku' jika 'in', 'Penyesuaian stok awal' jika 'adjustment'.
                'notes' => $request->type === 'in' ? 'Stok awal bahan baku' : 'Penyesuaian stok awal'
            ]);
        }

        // Mengarahkan pengguna kembali ke halaman index bahan baku setelah operasi berhasil.
        // `with('success', ...)` menambahkan pesan flash 'success' yang bisa ditampilkan di view.
        return redirect()->route('raw-materials.index')
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    /**
     * Metode `edit()`: Menampilkan form untuk mengedit bahan baku yang sudah ada.
     * Metode ini mengambil data bahan baku berdasarkan ID dan menyediakannya untuk pengeditan.
     *
     * @param  \App\Models\RawMaterial  $rawMaterial Ini adalah fitur Route Model Binding Laravel. Laravel secara otomatis mencari objek `RawMaterial` berdasarkan ID yang ada di URL dan menyuntikkannya ke metode ini.
     * @return \Illuminate\View\View Mengembalikan instance view Laravel yang berisi form pengeditan.
     */
    public function edit(RawMaterial $rawMaterial)
    {
        // Mengirim data bahan baku ($rawMaterial) yang akan diedit ke view.
        // View ini biasanya ada di `resources/views/raw-materials/edit.blade.php`.
        return view('raw-materials.edit', compact('rawMaterial'));
    }

    /**
     * Metode `update()`: Memperbarui data bahan baku yang sudah ada.
     * Metode ini menerima data dari form pengeditan dan memperbarui record di database.
     *
     * @param  \Illuminate\Http\Request  $request Objek Request yang berisi data input yang diperbarui.
     * @param  \App\Models\RawMaterial  $rawMaterial Objek RawMaterial yang akan diperbarui (didapat dari Route Model Binding).
     * @return \Illuminate\Http\RedirectResponse Mengarahkan pengguna kembali dengan pesan status.
     */
    public function update(Request $request, RawMaterial $rawMaterial)
    {
        // Melakukan validasi data yang diterima untuk pembaruan.
        // Aturannya mirip dengan `store()`, dengan tambahan `minimum_stock`.
        $request->validate([
            'name' => 'required',
            'stock' => 'required|numeric|min:0',
            'unit' => 'required',
            'price' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'description' => 'nullable'
        ]);

        // Memeriksa apakah ada perubahan pada nilai stok.
        // Jika stok yang diinputkan berbeda dengan stok yang ada di database saat ini, maka buat log perubahan.
        if ($request->stock != $rawMaterial->stock) {
            // Menghitung selisih antara stok baru dan stok lama.
            $difference = $request->stock - $rawMaterial->stock;
            // Menentukan tipe log: 'in' jika stok bertambah, 'out' jika stok berkurang.
            $type = $difference > 0 ? 'in' : 'out';
            // Mengambil nilai absolut dari selisih untuk kuantitas log.
            $quantity = abs($difference);

            // Membuat entri log di tabel 'raw_material_logs' untuk mencatat penyesuaian stok.
            RawMaterialLog::create([
                'raw_material_id' => $rawMaterial->id, // ID bahan baku yang stoknya diubah.
                'user_id' => auth()->id(), // ID pengguna yang melakukan perubahan.
                'type' => $type, // Tipe log ('in' atau 'out').
                'quantity' => $quantity, // Jumlah kuantitas yang berubah.
                'price' => $request->price, // Harga per unit saat perubahan (diambil dari input form).
                'subtotal' => $quantity * $request->price, // Subtotal perubahan.
                'notes' => 'Penyesuaian stok melalui edit bahan baku' // Catatan default untuk log ini.
            ]);
        }

        // Memperbarui data bahan baku di database dengan semua data dari request.
        // `update($request->all())` akan memperbarui semua kolom yang ada di `$request->all()` yang sesuai dengan fillable di model.
        $rawMaterial->update($request->all());

        // Mengarahkan kembali ke halaman index bahan baku dengan pesan sukses.
        return redirect()->route('raw-materials.index')
            ->with('success', 'Data bahan baku berhasil diperbarui.');
    }

    /**
     * Metode `destroy()`: Menghapus bahan baku dari database.
     * Metode ini digunakan untuk menghapus record bahan baku secara permanen.
     *
     * @param  \App\Models\RawMaterial  $rawMaterial Objek RawMaterial yang akan dihapus.
     * @return \Illuminate\Http\RedirectResponse Mengarahkan pengguna kembali dengan pesan status.
     */
    public function destroy(RawMaterial $rawMaterial)
    {
        // Menghapus data bahan baku dari database.
        $rawMaterial->delete();

        // Mengarahkan kembali ke halaman index bahan baku dengan pesan sukses.
        return redirect()->route('raw-materials.index')
            ->with('success', 'Raw material deleted successfully.');
    }

    /**
     * Metode `adjustStock()`: Menyesuaikan stok bahan baku (menambah atau mengurangi).
     * Metode ini dirancang untuk penyesuaian stok manual (misalnya, koreksi inventaris), bukan untuk pembelian.
     *
     * @param  \Illuminate\Http\Request  $request Objek Request yang berisi data penyesuaian stok.
     * @param  \App\Models\RawMaterial  $rawMaterial Objek RawMaterial yang stoknya akan disesuaikan.
     * @return \Illuminate\Http\RedirectResponse Mengarahkan pengguna kembali dengan pesan status.
     */
    public function adjustStock(Request $request, RawMaterial $rawMaterial)
    {
        // Melakukan validasi input untuk penyesuaian stok.
        $validated = $request->validate([
            'type' => 'required|in:in,out', // Tipe penyesuaian: 'in' (masuk) atau 'out' (keluar).
            'quantity' => 'required|numeric|min:1', // Kuantitas penyesuaian, harus angka positif.
            'notes' => 'nullable', // Catatan penyesuaian (opsional).
        ]);

        // Menggunakan transaksi database (`DB::transaction`).
        // Ini penting untuk menjaga integritas data: jika ada langkah yang gagal, semua perubahan dalam blok ini akan dibatalkan.
        DB::transaction(function () use ($rawMaterial, $validated) {
            // Menghitung subtotal berdasarkan harga bahan baku saat ini dan kuantitas yang disesuaikan.
            $subtotal = $rawMaterial->price * $validated['quantity'];

            // Logika penyesuaian stok:
            if ($validated['type'] === 'in') {
                // Jika tipe adalah 'in' (masuk), tambahkan kuantitas ke stok bahan baku.
                $rawMaterial->increment('stock', $validated['quantity']);
            } else {
                // Jika tipe adalah 'out' (keluar), kurangi kuantitas dari stok.
                // Penting: Lakukan pemeriksaan stok untuk mencegah stok negatif.
                if ($rawMaterial->stock < $validated['quantity']) {
                    // Jika stok tidak mencukupi, lemparkan Exception (ini akan memicu rollback transaksi).
                    throw new \Exception('Insufficient stock.');
                }
                // Kurangi stok bahan baku.
                $rawMaterial->decrement('stock', $validated['quantity']);
            }

            // Mencatat log penyesuaian stok di tabel 'raw_material_logs'.
            RawMaterialLog::create([
                'raw_material_id' => $rawMaterial->id, // ID bahan baku yang disesuaikan.
                'user_id' => auth()->id(), // ID pengguna yang melakukan penyesuaian.
                'type' => $validated['type'], // Tipe penyesuaian ('in' atau 'out').
                'quantity' => $validated['quantity'], // Kuantitas yang disesuaikan.
                'price' => $rawMaterial->price, // Harga bahan baku saat itu (dari database).
                'subtotal' => $subtotal, // Subtotal penyesuaian.
                'notes' => $validated['notes'] // Catatan dari pengguna.
            ]);
        });

        // Mengarahkan kembali ke halaman index bahan baku dengan pesan sukses.
        return redirect()->route('raw-materials.index')
            ->with('success', 'Stock adjusted successfully.');
    }

    /**
     * Metode `report()`: Menampilkan laporan log bahan baku (mutasi stok).
     * Metode ini memungkinkan pengguna melihat riwayat perubahan stok dengan filter tertentu.
     *
     * @param  \Illuminate\Http\Request  $request Objek Request yang berisi filter (tanggal, ID bahan baku).
     * @return \Illuminate\View\View Mengembalikan view laporan dengan data log.
     */
    public function report(Request $request)
    {
        // Membangun query dasar untuk mengambil log bahan baku.
        // `with(['rawMaterial', 'user.admin', 'user.karyawan'])` memuat relasi terkait (bahan baku, pengguna, admin/karyawan yang terkait dengan pengguna)
        // untuk menghindari N+1 query problem dan menampilkan detail yang relevan di laporan.
        // `orderBy('created_at', 'desc')` mengurutkan log berdasarkan waktu pembuatan terbaru.
        $query = RawMaterialLog::with(['rawMaterial', 'user.admin', 'user.karyawan'])
            ->orderBy('created_at', 'desc');

        // Menerapkan filter berdasarkan tanggal mulai jika `start_date` ada di request.
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        // Menerapkan filter berdasarkan tanggal selesai jika `end_date` ada di request.
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Menerapkan filter berdasarkan ID bahan baku jika `material_id` ada di request.
        if ($request->filled('material_id')) {
            $query->where('raw_material_id', $request->material_id);
        }

        // Mengeksekusi query dan mendapatkan hasil log.
        $logs = $query->get();
        // Mengambil semua bahan baku untuk digunakan dalam dropdown filter di tampilan laporan.
        $materials = RawMaterial::orderBy('name')->get();

        // Mengirim data log dan daftar bahan baku ke view 'raw-materials.report'.
        return view('raw-materials.report', compact('logs', 'materials'));
    }

    /**
     * Metode `lowStock()`: Menampilkan daftar bahan baku dengan stok rendah.
     * Metode ini berguna untuk mengidentifikasi bahan baku yang perlu segera di-restock.
     *
     * @return \Illuminate\View\View Mengembalikan view yang menampilkan bahan baku stok rendah.
     */
    public function lowStock()
    {
        // Mengambil bahan baku di mana stoknya kurang dari atau sama dengan 10.
        // `whereRaw('stock <= ?', [10])` digunakan untuk kondisi SQL mentah, membandingkan stok dengan ambang batas tetap 10.
        // `orderBy('name')` mengurutkan hasilnya berdasarkan nama.
        $materials = RawMaterial::whereRaw('stock <= ?', [10]) // Menggunakan ambang batas tetap 10 untuk sementara
            ->orderBy('name')
            ->get();

        // Mengirim data bahan baku stok rendah ke view 'raw-materials.low-stock'.
        return view('raw-materials.low-stock', compact('materials'));
    }

    /**
     * Metode `purchase()`: Menampilkan form untuk mencatat pembelian bahan baku.
     * Metode ini menyediakan antarmuka untuk mencatat pembelian bahan baku baru dari supplier.
     *
     * @return \Illuminate\View\View Mengembalikan view form pembelian.
     */
    public function purchase()
    {
        // Mengambil semua bahan baku untuk dropdown pilihan di form pembelian.
        $materials = RawMaterial::orderBy('name')->get();
        // Mengambil semua supplier (pemasok) untuk dropdown pilihan di form pembelian.
        $suppliers = Supplier::orderBy('name')->get();

        // Mengirim data bahan baku dan supplier ke view 'raw-materials.purchase'.
        return view('raw-materials.purchase', compact('materials', 'suppliers'));
    }

    /**
     * Metode `storePurchase()`: Menyimpan data pembelian bahan baku.
     * Metode ini memproses data dari form pembelian, memperbarui stok, dan mencatat log pembelian.
     *
     * @param  \Illuminate\Http\Request  $request Objek Request yang berisi data pembelian.
     * @return \Illuminate\Http\RedirectResponse Mengarahkan pengguna kembali dengan pesan sukses.
     */
    public function storePurchase(Request $request)
    {
        // Melakukan validasi input untuk data pembelian.
        // 'items' diharapkan berupa array, dan setiap item dalam array tersebut harus memiliki 'material_id', 'quantity', dan 'subtotal' yang valid.
        $validated = $request->validate([
            'items' => 'required|array', // 'items' wajib ada dan harus berupa array.
            'items.*.material_id' => 'required|exists:raw_materials,id', // Setiap item harus memiliki 'material_id' yang valid (ada di tabel 'raw_materials').
            'items.*.quantity' => 'required|numeric|min:0.01', // Kuantitas wajib, harus angka, minimal 0.01.
            'items.*.subtotal' => 'required|numeric|min:1', // Subtotal wajib, harus angka, minimal 1.
            'notes' => 'nullable|string' // Catatan pembelian (opsional).
        ]);

        // Menggunakan transaksi database (`DB::transaction`).
        // Ini memastikan bahwa semua pembaruan stok dan log pembelian untuk semua item berjalan secara atomik.
        // Jika ada masalah saat memproses satu item, semua perubahan akan dibatalkan.
        DB::transaction(function () use ($validated) {
            // Melakukan iterasi (loop) untuk setiap item bahan baku yang dibeli.
            foreach ($validated['items'] as $item) {
                // Mencari objek RawMaterial berdasarkan 'material_id' dari item yang dibeli.
                // `findOrFail()` akan melempar error 404 jika bahan baku tidak ditemukan.
                $material = RawMaterial::findOrFail($item['material_id']);
                // Menghitung harga per unit baru. Ini penting karena harga beli bisa bervariasi.
                // `round(..., 2)` membulatkan hasil ke dua tempat desimal.
                $newPrice = round($item['subtotal'] / $item['quantity'], 2);

                // Memperbarui stok dan harga di tabel `raw_materials`.
                $material->update([
                    'stock' => $material->stock + $item['quantity'], // Menambahkan kuantitas yang dibeli ke stok yang ada.
                    'price' => $newPrice // Memperbarui harga bahan baku dengan harga rata-rata baru dari pembelian ini.
                ]);

                // Membuat entri log di tabel `raw_material_logs` untuk mencatat transaksi pembelian ini.
                RawMaterialLog::create([
                    'raw_material_id' => $item['material_id'], // ID bahan baku yang dibeli.
                    'user_id' => auth()->id(), // ID pengguna yang sedang login yang melakukan pembelian.
                    'type' => 'in', // Tipe log adalah 'in' (masuk), menunjukkan penambahan stok.
                    'quantity' => $item['quantity'], // Kuantitas yang dibeli.
                    'price' => $newPrice, // Harga per unit yang baru dihitung.
                    'subtotal' => $item['subtotal'], // Subtotal total untuk item ini.
                    // Catatan log: jika ada input 'notes', gunakan itu; jika tidak, gunakan 'Pembelian bahan baku'.
                    'notes' => $validated['notes'] ? $validated['notes'] : 'Pembelian bahan baku'
                ]);
            }
        });

        // Mengarahkan kembali ke halaman index bahan baku dengan pesan sukses setelah semua item berhasil disimpan.
        return redirect()->route('raw-materials.index')
            ->with('success', 'Pembelian bahan baku berhasil disimpan.');
    }
}

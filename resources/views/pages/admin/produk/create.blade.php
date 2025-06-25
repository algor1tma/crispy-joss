@extends('component.main')

@section('content')
<div class="pagetitle">
    <h1>Tambah Produk</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Manajemen</li>
            <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Data Produk</a></li>
            <li class="breadcrumb-item active">Tambah Produk</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-8">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Tambah Produk</h5>

                    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="harga_produk" class="form-label">Harga Produk (Rp)</label>
                            <input type="number" min="0" class="form-control @error('harga_produk') is-invalid @enderror" id="harga_produk" name="harga_produk" value="{{ old('harga_produk') }}" required>
                            @error('harga_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="stok_produk" class="form-label">Stok Produk</label>
                            <input type="number" min="0" class="form-control @error('stok_produk') is-invalid @enderror" id="stok_produk" name="stok_produk" value="{{ old('stok_produk') }}" required>
                            @error('stok_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Produk (jpeg,png,jpg max 2MB)</label>
                            <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_produk" class="form-label">Deskripsi Produk</label>
                            <textarea class="form-control @error('deskripsi_produk') is-invalid @enderror" id="deskripsi_produk" name="deskripsi_produk" rows="3">{{ old('deskripsi_produk') }}</textarea>
                            @error('deskripsi_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Resep Produk</label>
                            <div class="col-sm-10">
                                <div id="recipe-container">
                                    <div class="recipe-item mb-2">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <select name="recipes[0][material_id]" class="form-select" required>
                                                    <option value="">Pilih Bahan Baku</option>
                                                    @foreach($materials as $material)
                                                        <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">
                                                            {{ $material->name }} ({{ $material->unit }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="number" name="recipes[0][quantity]" class="form-control" placeholder="Jumlah" required>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="recipes[0][unit]" class="form-select" required>
                                                    <option value="g">Gram (g)</option>
                                                    <option value="kg">Kilogram (kg)</option>
                                                    <option value="ml">Mililiter (ml)</option>
                                                    <option value="l">Liter (l)</option>
                                                    <option value="pcs">Pieces (pcs)</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-danger remove-recipe">Hapus</button>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-10">
                                                <input type="text" name="recipes[0][notes]" class="form-control" placeholder="Catatan penggunaan (opsional)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success mt-2" id="add-recipe">Tambah Bahan</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let recipeCount = 0;

        // Tambah form resep
        $('#add-recipe').click(function() {
            recipeCount++;
            const newRecipe = `
                <div class="recipe-item mb-2">
                    <div class="row">
                        <div class="col-sm-4">
                            <select name="recipes[${recipeCount}][material_id]" class="form-select" required>
                                <option value="">Pilih Bahan Baku</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">
                                        {{ $material->name }} ({{ $material->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="number" name="recipes[${recipeCount}][quantity]" class="form-control" placeholder="Jumlah" required>
                        </div>
                        <div class="col-sm-3">
                            <select name="recipes[${recipeCount}][unit]" class="form-select" required>
                                <option value="g">Gram (g)</option>
                                <option value="kg">Kilogram (kg)</option>
                                <option value="ml">Mililiter (ml)</option>
                                <option value="l">Liter (l)</option>
                                <option value="pcs">Pieces (pcs)</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-danger remove-recipe">Hapus</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-10">
                            <input type="text" name="recipes[${recipeCount}][notes]" class="form-control" placeholder="Catatan penggunaan (opsional)">
                        </div>
                    </div>
                </div>
            `;
            $('#recipe-container').append(newRecipe);
        });

        // Hapus form resep
        $(document).on('click', '.remove-recipe', function() {
            $(this).closest('.recipe-item').remove();
        });

        // Auto-select unit based on material
        $(document).on('change', 'select[name$="[material_id]"]', function() {
            const selectedOption = $(this).find('option:selected');
            const unit = selectedOption.data('unit');
            $(this).closest('.row').find('select[name$="[unit]"]').val(unit);
        });
    });
</script>
@endpush

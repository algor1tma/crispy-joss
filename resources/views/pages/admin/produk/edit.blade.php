@extends('component.main')

@section('content')
<div class="pagetitle">
    <h1>Edit Produk</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Manajemen</li>
            <li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Data Produk</a></li>
            <li class="breadcrumb-item active">Edit Produk</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Edit Produk</h5>

                    <form action="{{ route('produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Nama Produk</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="nama_produk" value="{{ $produk->nama_produk }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Harga</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="harga_produk" value="{{ $produk->harga_produk }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Stok</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" name="stok_produk" value="{{ $produk->stok_produk }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Foto</label>
                            <div class="col-sm-10">
                                @if($produk->foto)
                                    <img src="{{ asset('img/produk/' . $produk->foto) }}" alt="Current Photo" class="mb-2" style="max-width: 200px;">
                                @endif
                                <input type="file" class="form-control" name="foto">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Deskripsi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="deskripsi_produk" rows="3">{{ $produk->deskripsi_produk }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-sm-2 col-form-label">Resep Produk</label>
                            <div class="col-sm-10">
                                <div id="recipe-container">
                                    @foreach($produk->recipes as $index => $recipe)
                                        <div class="recipe-item mb-2">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <select name="recipes[{{ $index }}][material_id]" class="form-select material-select" required>
                                                        <option value="">Pilih Bahan Baku</option>
                                                        @foreach($materials as $material)
                                                            <option value="{{ $material->id }}" 
                                                                data-unit="{{ $material->unit }}"
                                                                {{ $recipe->raw_material_id == $material->id ? 'selected' : '' }}>
                                                                {{ $material->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="recipes[{{ $index }}][unit]" value="{{ $recipe->unit }}" class="unit-input">
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <input type="number" name="recipes[{{ $index }}][quantity]" 
                                                            class="form-control" value="{{ $recipe->quantity }}" 
                                                            step="0.01" min="0.01" required>
                                                        <span class="input-group-text material-unit">
                                                            {{ $materials->find($recipe->raw_material_id)->unit ?? '' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-danger remove-recipe">Hapus</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-success mt-2" id="add-recipe">Tambah Bahan</button>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
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
        let recipeCount = {{ count($produk->recipes) }};

        // Update unit when material is selected
        $(document).on('change', '.material-select', function() {
            const selectedOption = $(this).find('option:selected');
            const unit = selectedOption.data('unit');
            const row = $(this).closest('.recipe-item');
            row.find('.material-unit').text(unit || '');
            row.find('.unit-input').val(unit || '');
        });

        // Tambah form resep
        $('#add-recipe').click(function() {
            const newRecipe = `
                <div class="recipe-item mb-2">
                    <div class="row">
                        <div class="col-sm-4">
                            <select name="recipes[${recipeCount}][material_id]" class="form-select material-select" required>
                                <option value="">Pilih Bahan Baku</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" data-unit="{{ $material->unit }}">
                                        {{ $material->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="recipes[${recipeCount}][unit]" class="unit-input">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" name="recipes[${recipeCount}][quantity]" 
                                    class="form-control" placeholder="Jumlah" 
                                    step="0.01" min="0.01" required>
                                <span class="input-group-text material-unit"></span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-danger remove-recipe">Hapus</button>
                        </div>
                    </div>
                </div>
            `;
            $('#recipe-container').append(newRecipe);
            recipeCount++;
        });

        // Hapus form resep
        $(document).on('click', '.remove-recipe', function() {
            $(this).closest('.recipe-item').remove();
        });

        // Set initial unit values
        $('.material-select').each(function() {
            const selectedOption = $(this).find('option:selected');
            const unit = selectedOption.data('unit');
            const row = $(this).closest('.recipe-item');
            row.find('.unit-input').val(unit || '');
        });
    });
</script>
@endpush

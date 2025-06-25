@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Edit Bahan Baku</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item">Data Bahan Baku</li>
                <li class="breadcrumb-item active">Edit Bahan</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Edit Bahan Baku</h5>

                        <form action="{{ route('raw-materials.update', $rawMaterial->id) }}" method="POST" class="row g-3">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Nama Bahan</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $rawMaterial->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3">{{ old('description', $rawMaterial->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                                       value="{{ old('stock', $rawMaterial->stock) }}" required min="0" step="0.01">
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Minimum Stok</label>
                                <input type="number" name="minimum_stock" class="form-control @error('minimum_stock') is-invalid @enderror" 
                                       value="{{ old('minimum_stock', $rawMaterial->minimum_stock) }}" required min="0" step="0.01">
                                @error('minimum_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" 
                                       value="{{ old('unit', $rawMaterial->unit) }}" required>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Harga per Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                           value="{{ old('price', $rawMaterial->price) }}" required min="0" step="0.01">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('raw-materials.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection 
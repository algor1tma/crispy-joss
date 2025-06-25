@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Tambah Bahan Baku</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item">Data Bahan Baku</li>
                <li class="breadcrumb-item active">Tambah Bahan</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Form Tambah Bahan Baku</h5>

                        <form action="{{ route('raw-materials.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-12">
                                <label class="form-label">Nama Bahan</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                          rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Stok Awal</label>
                                <div class="input-group">
                                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                                           value="{{ old('stock', 0) }}" required min="0" step="0.01">
                                    <span class="input-group-text" id="unit-addon"></span>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" 
                                       value="{{ old('unit') }}" required placeholder="Contoh: kg, pcs, liter"
                                       onchange="document.getElementById('unit-addon').textContent = this.value">
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Harga per Satuan</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                                           value="{{ old('price') }}" required min="0" step="0.01">
                                    <span class="input-group-text">/ <span class="unit-display"></span></span>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tipe Pencatatan</label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Pilih tipe pencatatan</option>
                                    {{-- <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>Stok Masuk (Pembelian)</option> --}}
                                    <option value="adjustment" {{ old('type') === 'adjustment' ? 'selected' : '' }}>Penambahan bahan baku baru</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    Pilih "penambahan bahan baku baru" untuk menambahkan bahan baku baru
                                </small>
                            </div>

                            <div class="text-end">
                                <a href="{{ route('raw-materials.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.querySelector('input[name="unit"]').addEventListener('input', function(e) {
            document.querySelectorAll('.unit-display').forEach(el => {
                el.textContent = e.target.value;
            });
        });
    </script>
    @endpush
@endsection 
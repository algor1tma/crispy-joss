@extends('component.main')

@section('content')
<div class="pagetitle">
    <h1>Edit Transaksi</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Manajemen</li>
            <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}">Data Transaksi</a></li>
            <li class="breadcrumb-item active">Edit Transaksi</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Edit Transaksi</h5>

                    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" id="transaksiForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Data Pelanggan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Pelanggan</label>
                                    <input type="text" name="nama_pelanggan" class="form-control" value="{{ $transaksi->customer_name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" name="no_telp" class="form-control" value="{{ $transaksi->customer_phone }}" required>
                                </div>
                            </div>

                            <!-- Tanggal dan Waktu -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal dan Waktu Transaksi</label>
                                    <div class="row">
                                        <div class="col">
                                            <input type="date" class="form-control" name="tanggal_transaksi" 
                                                value="{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="col">
                                            <input type="time" class="form-control" name="waktu_transaksi" 
                                                value="{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('H:i') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Menu -->
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered" id="menuTable">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi->details as $index => $detail)
                                    <tr>
                                        <td>
                                            <select name="items[{{ $index }}][produk_id]" class="form-select produk-select" required>
                                                <option value="">Pilih Menu</option>
                                                @foreach($produks as $produk)
                                                    <option value="{{ $produk->id }}" 
                                                        data-harga="{{ $produk->harga_produk }}"
                                                        {{ $detail->produk_id == $produk->id ? 'selected' : '' }}>
                                                        {{ $produk->nama_produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][harga]" class="form-control harga" 
                                                value="{{ $detail->price }}" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][jumlah]" class="form-control jumlah" 
                                                value="{{ $detail->quantity }}" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][subtotal]" class="form-control subtotal" 
                                                value="{{ $detail->subtotal }}" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm hapus-menu">Hapus</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-success" id="tambahMenu">
                                <i class="bi bi-plus-circle"></i> Tambah Menu
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Total Harga</label>
                                    <input type="number" name="total_harga" id="totalHarga" class="form-control" 
                                        value="{{ $transaksi->total_harga }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Update</button>
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
    let rowIndex = {{ count($transaksi->details) }};

    // Fungsi untuk menghitung subtotal
    function hitungSubtotal(row) {
        const harga = parseFloat(row.find('.harga').val()) || 0;
        const jumlah = parseFloat(row.find('.jumlah').val()) || 0;
        const subtotal = harga * jumlah;
        row.find('.subtotal').val(subtotal);
        hitungTotal();
    }

    // Fungsi untuk menghitung total
    function hitungTotal() {
        let total = 0;
        $('.subtotal').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#totalHarga').val(total);
    }

    // Event ketika memilih produk
    $(document).on('change', '.produk-select', function() {
        const row = $(this).closest('tr');
        const harga = $(this).find(':selected').data('harga') || 0;
        row.find('.harga').val(harga);
        hitungSubtotal(row);
    });

    // Event ketika mengubah jumlah
    $(document).on('input', '.jumlah', function() {
        hitungSubtotal($(this).closest('tr'));
    });

    // Tambah baris menu baru
    $('#tambahMenu').click(function() {
        rowIndex++;
        const newRow = `
            <tr>
                <td>
                    <select name="items[${rowIndex}][produk_id]" class="form-select produk-select" required>
                        <option value="">Pilih Menu</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}" data-harga="{{ $produk->harga_produk }}">
                                {{ $produk->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${rowIndex}][harga]" class="form-control harga" readonly>
                </td>
                <td>
                    <input type="number" name="items[${rowIndex}][jumlah]" class="form-control jumlah" value="1" min="1" required>
                </td>
                <td>
                    <input type="number" name="items[${rowIndex}][subtotal]" class="form-control subtotal" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm hapus-menu">Hapus</button>
                </td>
            </tr>
        `;
        $('#menuTable tbody').append(newRow);
    });

    // Hapus baris menu
    $(document).on('click', '.hapus-menu', function() {
        if ($('#menuTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
            hitungTotal();
        } else {
            alert('Minimal harus ada satu menu!');
        }
    });

    // Hitung total awal
    hitungTotal();
});
</script>
@endpush

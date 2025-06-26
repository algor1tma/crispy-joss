@extends('component.main')

@section('content')
    <div class="pagetitle">
        <h1>Pembelian Bahan Baku</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Manajemen</li>
                <li class="breadcrumb-item">Data Bahan Baku</li>
                <li class="breadcrumb-item active">Pembelian</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Daftar Bahan Baku</h5>
                            <div class="search-box">
                                <input type="text" class="form-control" id="searchMaterial" placeholder="Cari bahan baku...">
                            </div>
                        </div>
                        <div class="row" id="materialContainer">
                            @foreach($materials as $material)
                                <div class="col-md-4 mb-3 material-item">
                                    <div class="card material-card" 
                                        data-id="{{ $material->id }}" 
                                        data-name="{{ $material->name }}" 
                                        data-price="{{ $material->price }}" 
                                        data-unit="{{ $material->unit }}">
                                        <div class="card-body">
                                            <h6 class="material-name">{{ $material->name }}</h6>
                                            <p class="text-muted mb-1">
                                                Harga: Rp {{ number_format($material->price, 0, ',', '.') }}/{{ $material->unit }}
                                            </p>
                                            <p class="text-muted mb-2">
                                                Stok: {{ number_format($material->stock, ) }} {{ $material->unit }}
                                            </p>
                                            <button class="btn btn-sm btn-primary add-to-cart w-100">
                                                <i class="bi bi-plus-circle"></i> Tambah
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Pembelian</h5>
                        <form id="purchase-form" action="{{ route('raw-materials.purchase.store') }}" method="POST">
                            @csrf
                            <div class="cart-items mb-3">
                                <div class="alert alert-info mb-3">
                                    <small>
                                        <i class="bi bi-info-circle"></i>
                                        Transaksi akan dicatat atas nama: 
                                        <strong>
                                            @if(auth()->user()->roles === 'admin')
                                                {{ auth()->user()->admins->nama ?? 'Admin' }}
                                            @else
                                                {{ auth()->user()->karyawans->nama ?? 'Karyawan' }}
                                            @endif
                                        </strong>
                                    </small>
                                </div>
                                <table class="table table-sm" id="cart-table">
                                    <thead>
                                        <tr>
                                            <th>Bahan</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Item keranjang akan ditambahkan di sini -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="cart-summary">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5>Total:</h5>
                                    <h5 id="cart-total">Rp 0</h5>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Catatan</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Catatan pembelian (opsional)"></textarea>
                                </div>

                                <button type="submit" class="btn btn-success w-100 mb-2" id="process-purchase">
                                    <i class="bi bi-check-circle"></i> Proses Pembelian
                                </button>
                                <button type="button" class="btn btn-secondary w-100" id="reset-cart">
                                    <i class="bi bi-x-circle"></i> Bersihkan
                                </button>
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
        let cart = [];
        
        // Fungsi pencarian
        $('#searchMaterial').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.material-item').each(function() {
                const materialName = $(this).find('.material-name').text().toLowerCase();
                if (materialName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Tambah item ke keranjang
        $('.add-to-cart').click(function() {
            const card = $(this).closest('.material-card');
            const id = card.data('id');
            const name = card.data('name');
            const price = parseFloat(card.data('price'));
            const unit = card.data('unit');
            
            // Cek apakah bahan sudah ada di keranjang
            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.quantity++;
                existingItem.subtotal = existingItem.price * existingItem.quantity;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    unit: unit,
                    quantity: 1,
                    subtotal: price
                });
            }
            
            updateCart();
        });
        
        // Hapus item dari keranjang
        $(document).on('click', '.remove-item', function() {
            const index = $(this).data('index');
            cart.splice(index, 1);
            updateCart();
        });
        
        // Update jumlah
        $(document).on('input', '.item-quantity', function() {
            // Hanya update value di cart, jangan updateCart()
            const index = $(this).data('index');
            let newQuantity = parseInt($(this).val()) || 0;
            if (newQuantity < 1) {
                newQuantity = 1;
                $(this).val(1);
            }
            cart[index].quantity = newQuantity;
            cart[index].subtotal = cart[index].price * newQuantity;
            updateSubtotalInput(index);
            // Jangan panggil updateCart() di sini
        });

        // Update cart saat input quantity kehilangan fokus atau tekan Enter
        $(document).on('blur', '.item-quantity', function() {
            updateCart();
        });
        $(document).on('keydown', '.item-quantity', function(e) {
            if (e.key === 'Enter') {
                $(this).blur();
            }
        });

        // Update subtotal
        $(document).on('input', '.item-subtotal', function() {
            // Hanya update value di cart, jangan updateCart()
            const index = $(this).data('index');
            const newSubtotal = parseFloat($(this).val()) || 0;
            if (newSubtotal > 0) {
                cart[index].subtotal = newSubtotal;
                // Update harga per satuan
                cart[index].price = Math.round(newSubtotal / cart[index].quantity);
                updatePricePerUnit(index);
            }
            // Jangan panggil updateCart() di sini
        });
        $(document).on('blur', '.item-subtotal', function() {
            updateCart();
        });
        $(document).on('keydown', '.item-subtotal', function(e) {
            if (e.key === 'Enter') {
                $(this).blur();
            }
        });
        
        function updateSubtotalInput(index) {
            $(`.item-subtotal[data-index="${index}"]`).val(Math.round(cart[index].subtotal));
        }
        
        // Update harga per satuan
        function updatePricePerUnit(index) {
            const pricePerUnit = Math.round(cart[index].price);
            $(`.price-per-unit[data-index="${index}"]`).text(
                `Rp ${formatNumber(pricePerUnit)}/${cart[index].unit}`
            );
        }
        
        // Update tampilan keranjang
        function updateCart() {
            $('#cart-table tbody').empty();
            
            cart.forEach((item, index) => {
                $('#cart-table tbody').append(`
                    <tr>
                        <td>
                            ${item.name}
                            <br>
                            <small class="text-muted price-per-unit" data-index="${index}">
                                Rp ${formatNumber(item.price)}/${item.unit}
                            </small>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm item-quantity" 
                                data-index="${index}" value="${item.quantity}" min="1" style="width: 80px">
                            <small class="text-muted">${item.unit}</small>
                        </td>
                        <td>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control form-control-sm item-subtotal" 
                                    data-index="${index}" value="${item.subtotal}" min="1" style="width: 120px">
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger remove-item" data-index="${index}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
            
            const total = calculateTotal();
            $('#cart-total').text(`Rp ${formatNumber(total)}`);
        }
        
        // Proses pembelian
        $('#purchase-form').submit(function(e) {
            e.preventDefault();
            
            if (cart.length === 0) {
                alert('Keranjang pembelian kosong!');
                return;
            }

            // Hapus input tersembunyi yang ada
            $('.temp-input').remove();

            // Tambahkan data item sebagai input tersembunyi
            cart.forEach((item, index) => {
                $('<input>').attr({
                    type: 'hidden',
                    class: 'temp-input',
                    name: `items[${index}][material_id]`,
                    value: item.id
                }).appendTo(this);

                $('<input>').attr({
                    type: 'hidden',
                    class: 'temp-input',
                    name: `items[${index}][quantity]`,
                    value: item.quantity
                }).appendTo(this);

                $('<input>').attr({
                    type: 'hidden',
                    class: 'temp-input',
                    name: `items[${index}][subtotal]`,
                    value: item.subtotal
                }).appendTo(this);
            });

            // Kirim form
            this.submit();
        });
        
        // Bersihkan keranjang
        $('#reset-cart').click(function() {
            cart = [];
            updateCart();
            $('#notes').val('');
        });
        
        // Hitung total
        function calculateTotal() {
            return cart.reduce((total, item) => total + parseFloat(item.subtotal), 0);
        }
        
        // Format angka dengan pemisah ribuan
        function formatNumber(number) {
            return number.toLocaleString('id-ID');
        }

        // Parse format angka kembali ke number
        function parseFormattedNumber(formattedNumber) {
            return parseFloat(formattedNumber.replace(/\./g, '').replace(',', '.'));
        }
    });
</script>
@endpush
@extends('component.main')

@section('title', 'Point of Sale')

@section('content')
    <div class="pagetitle">
        <h1>Point of Sale (POS)</h1>
        <nav>
            <ol class="breadcrumb">
                {{-- <li class="breadcrumb-item"><a href="{{ route('indexDashboard') }}">Home</a></li> --}}
                <li class="breadcrumb-item active">Point of Sale</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Daftar Menu</h5>
                            <div class="search-box">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" id="searchMenu" placeholder="Cari menu...">
                                </div>
                            </div>
                        </div>
                        <div class="row g-3" id="menuContainer">
                            @foreach($produks as $produk)
                                <div class="col-md-4 menu-item">
                                    <div class="card h-100 product-card" data-id="{{ $produk->id }}" data-name="{{ $produk->nama_produk }}" data-price="{{ $produk->harga_produk }}">
                                        <div class="card-img-top position-relative" style="height: 160px; overflow: hidden;">
                                            @if($produk->foto)
                                                <img src="{{ asset('img/produk/' . $produk->foto) }}" 
                                                     alt="{{ $produk->nama_produk }}" 
                                                     class="w-100 h-100" 
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif
                                            @if($produk->stok_produk <= 0)
                                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                                                     style="background-color: rgba(0,0,0,0.5);">
                                                    <span class="badge bg-danger fs-6">Stok Habis</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title menu-name text-center mb-2">{{ $produk->nama_produk }}</h6>
                                            <div class="text-center mb-2">
                                                <span class="badge bg-success fs-6">
                                                    Rp {{ number_format($produk->harga_produk, 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                                <small class="text-muted">Stok: {{ $produk->stok_produk }}</small>
                                                <button class="btn btn-primary btn-sm add-to-cart" 
                                                        {{ $produk->stok_produk <= 0 ? 'disabled' : '' }}>
                                                    <i class="bi bi-cart-plus"></i> Tambah
                                                </button>
                                            </div>
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
                        <h5 class="card-title">Keranjang Belanja</h5>
                        <div class="cart-items mb-3">
                            <table class="table table-sm" id="cart-table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Cart items will be added here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="cart-summary">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Total:</h5>
                                <h5 id="cart-total">Rp 0</h5>
                            </div>

                            <!-- Customer Information Form -->
                            <div class="customer-info mb-4">
                                <h6 class="mb-3">Data Pelanggan</h6>
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Nama Pelanggan</label>
                                    <input type="text" class="form-control" id="customer_name" required>
                                    <div class="invalid-feedback">Nama pelanggan harus diisi</div>
                                </div>
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Nomor HP</label>
                                    <input type="text" class="form-control" id="customer_phone" required>
                                    <div class="invalid-feedback">Nomor HP harus diisi</div>
                                </div>
                            </div>

                            <button class="btn btn-success w-100 mb-2" id="process-transaction">
                                <i class="bi bi-check2-circle"></i> Proses Transaksi
                            </button>
                            <button class="btn btn-secondary w-100" id="reset-cart">
                                <i class="bi bi-trash"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">
                        <i class="bi bi-receipt"></i> Struk Transaksi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="receipt-content">
                    <!-- Receipt content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" id="print-receipt">
                        <i class="bi bi-printer"></i> Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let cart = [];
        
        // Search functionality
        $('#searchMenu').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('.menu-item').each(function() {
                const menuName = $(this).find('.menu-name').text().toLowerCase();
                if (menuName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Add item to cart
        $('.add-to-cart').click(function() {
            const card = $(this).closest('.product-card');
            const id = card.data('id');
            const name = card.data('name');
            const price = parseInt(card.data('price'));
            
            // Check if product already in cart
            const existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.quantity++;
                existingItem.subtotal = existingItem.price * existingItem.quantity;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: price,
                    quantity: 1,
                    subtotal: price
                });
            }
            
            updateCart();
        });
        
        // Remove item from cart
        $(document).on('click', '.remove-item', function() {
            const index = $(this).data('index');
            cart.splice(index, 1);
            updateCart();
        });
        
        // Update quantity
        $(document).on('change', '.item-quantity', function() {
            const index = $(this).data('index');
            const newQuantity = parseInt($(this).val());
            
            if (newQuantity > 0) {
                cart[index].quantity = newQuantity;
                cart[index].subtotal = cart[index].price * newQuantity;
            } else {
                cart.splice(index, 1);
            }
            
            updateCart();
        });
        
        // Process transaction
        $('#process-transaction').click(function() {
            if (cart.length === 0) {
                alert('Keranjang belanja kosong!');
                return;
            }

            // Validate customer information
            const customerName = $('#customer_name').val().trim();
            const customerPhone = $('#customer_phone').val().trim();

            if (!customerName) {
                $('#customer_name').addClass('is-invalid');
                return;
            }
            if (!customerPhone) {
                $('#customer_phone').addClass('is-invalid');
                return;
            }
            
            const items = cart.map(item => ({
                id: item.id,
                quantity: item.quantity,
                price: item.price
            }));
            
            // Show loading state
            const $btn = $(this);
            const originalText = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...');
            
            $.ajax({
                url: "{{ route('pos.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    customer_name: customerName,
                    customer_phone: customerPhone,
                    product_id: items.map(item => item.id),
                    quantity: items.map(item => item.quantity),
                    price: items.map(item => item.price),
                    total_harga: calculateTotal()
                },
                success: function(response) {
                    if (response.success) {
                        // Load receipt
                        $.get("{{ url('pos/receipt') }}/" + response.transaction_id, function(data) {
                            $('#receipt-content').html(data);
                            $('#receiptModal').modal('show');
                        });
                        
                        // Reset cart and form
                        cart = [];
                        updateCart();
                        $('#customer_name').val('');
                        $('#customer_phone').val('');
                        
                        // Only reload page after modal is closed
                        $('#receiptModal').on('hidden.bs.modal', function () {
                            location.reload();
                        });
                    } else {
                        alert('Gagal menyimpan transaksi: ' + (response.message || 'Terjadi kesalahan'));
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    let errorMessage = 'Terjadi kesalahan saat menyimpan transaksi!';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                    alert(errorMessage);
                },
                complete: function() {
                    // Reset button state
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        });
        
        // Reset cart
        $('#reset-cart').click(function() {
            if (cart.length > 0 && confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
                cart = [];
                updateCart();
                $('#customer_name').val('');
                $('#customer_phone').val('');
            }
        });
        
        // Remove invalid class on input
        $('.form-control').on('input', function() {
            $(this).removeClass('is-invalid');
        });
        
        // Print receipt
        $('#print-receipt').click(function() {
            const printContent = $('#receipt-content').html();
            const printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>Struk Transaksi</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('body { font-family: Arial, sans-serif; font-size: 12px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
            printWindow.document.write('th, td { padding: 5px; }');
            printWindow.document.write('hr { border: none; border-top: 1px dashed #000; }');
            printWindow.document.write('.text-center { text-align: center; }');
            printWindow.document.write('.text-end { text-align: right; }');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            setTimeout(function() {
                printWindow.focus();
                printWindow.print();
            }, 300);
        });
        
        // Update cart view
        function updateCart() {
            $('#cart-table tbody').empty();
            let total = 0;
            
            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                total += subtotal;
                
                $('#cart-table tbody').append(`
                    <tr>
                        <td>${item.name}</td>
                        <td class="text-center">
                            <input type="number" class="form-control form-control-sm item-quantity" 
                                data-index="${index}" value="${item.quantity}" min="1" style="width: 60px; margin: 0 auto;">
                        </td>
                        <td class="text-end">Rp ${formatNumber(item.price)}</td>
                        <td class="text-end">Rp ${formatNumber(subtotal)}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-danger remove-item" data-index="${index}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });
            
            $('#cart-total').text(`Rp ${formatNumber(total)}`);
        }
        
        // Calculate total
        function calculateTotal() {
            return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        }
        
        // Format number
        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });
</script>

<style>
.product-card {
    transition: transform 0.2s, box-shadow 0.2s;
    cursor: pointer;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.search-box {
    width: 300px;
}

.cart-items {
    max-height: 300px;
    overflow-y: auto;
}

.menu-name {
    font-size: 1rem;
    margin: 0;
    line-height: 1.2;
    height: 2.4em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
</style>
@endpush 
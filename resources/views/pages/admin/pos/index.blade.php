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
                            @foreach ($produks as $produk)
                                <div class="col-md-4 menu-item">
                                    <div class="card h-100 product-card" data-id="{{ $produk['id'] }}"
                                        data-name="{{ $produk['nama_produk'] }}" data-price="{{ $produk['harga_produk'] }}">
                                        <div class="card-img-top position-relative"
                                            style="height: 160px; overflow: hidden;">
                                            @if ($produk['foto'])
                                                <img src="{{ asset('img/produk/' . $produk['foto']) }}"
                                                    alt="{{ $produk['nama_produk'] }}" class="w-100 h-100"
                                                    style="object-fit: cover;">
                                            @else
                                                <div
                                                    class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                                                </div>
                                            @endif
                                            @if ($produk['stok_produk'] <= 0)
                                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                                    style="background-color: rgba(0,0,0,0.5);">
                                                    <span class="badge bg-danger fs-6">Stok Habis</span>
                                                </div>
                                            @elseif(!$produk['can_sell'])
                                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                                    style="background-color: rgba(255,193,7,0.8);">
                                                    <span class="badge bg-warning fs-6">Bahan Kurang</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body d-flex flex-column" style="min-height: 180px;">
                                            <h6 class="card-title menu-name text-center mb-3">{{ $produk['nama_produk'] }}
                                            </h6>
                                            <div class="text-center mb-3">
                                                <span class="badge bg-success fs-6">
                                                    Rp {{ number_format($produk['harga_produk'], 0, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-end mt-auto">
                                                <div class="flex-grow-1">
                                                    <small class="text-muted d-block">Stok:
                                                        {{ $produk['stok_produk'] }}</small>
                                                    @if ($produk['has_recipe'])
                                                        <small class="text-info d-block">Max:
                                                            {{ $produk['max_sellable'] }}</small>
                                                        @if (count($produk['missing_materials']) > 0)
                                                            <small class="text-warning d-block">
                                                                <i class="bi bi-exclamation-triangle"></i> Bahan kurang
                                                            </small>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <button class="btn btn-primary btn-sm add-to-cart"
                                                        {{ !$produk['can_sell'] ? 'disabled' : '' }}
                                                        data-max-sellable="{{ $produk['max_sellable'] }}"
                                                        data-has-recipe="{{ $produk['has_recipe'] ? 'true' : 'false' }}">
                                                        <i class="bi bi-cart-plus"></i> Tambah
                                                    </button>
                                                </div>
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
                <div class="card cart-card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-cart3"></i> Keranjang Belanja
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <!-- Cart Items Section -->
                        <div class="cart-items-section">
                            <div class="cart-items-header px-3 py-2 bg-light border-bottom">
                                <small class="text-muted fw-bold">ITEM PESANAN</small>
                            </div>
                            <div class="cart-items">
                                <div id="empty-cart-message" class="text-center py-4 text-muted">
                                    <i class="bi bi-cart-x fs-1 text-secondary"></i>
                                    <p class="mt-2 mb-0">Keranjang masih kosong</p>
                                    <small>Silakan pilih menu yang diinginkan</small>
                                </div>
                                <table class="table table-sm mb-0" id="cart-table" style="display: none;">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Produk</th>
                                            <th class="border-0 text-center" width="80">Qty</th>
                                            <th class="border-0 text-end" width="80">Subtotal</th>
                                            <th class="border-0 text-center" width="40"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Cart items will be added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Cart Summary -->
                        <div class="cart-summary-section border-top">
                            <div class="px-3 py-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 text-primary fw-bold">Total Pembayaran:</h5>
                                    <h4 class="mb-0 text-success fw-bold" id="cart-total">Rp 0</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information Form -->
                        <div class="customer-info-section border-top bg-light">
                            <div class="px-3 py-3">
                                <h6 class="mb-3 text-primary">
                                    <i class="bi bi-person-fill"></i> Data Pelanggan
                                </h6>
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label small fw-bold">Nama Pelanggan</label>
                                    <input type="text" class="form-control form-control-sm" id="customer_name"
                                        placeholder="Masukkan nama pelanggan" required>
                                    <div class="invalid-feedback">Nama pelanggan harus diisi</div>
                                </div>
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label small fw-bold">Nomor HP</label>
                                    <input type="tel" class="form-control form-control-sm" id="customer_phone"
                                        placeholder="Masukkan nomor HP" required maxlength="14"
                                        pattern="[0-9]{8,14}" title="Nomor HP harus 8-14 digit angka">
                                    <div class="invalid-feedback">Nomor HP harus diisi (8-14 digit)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="cart-actions-section border-top">
                            <div class="px-3 py-3">
                                <button class="btn btn-success w-100 mb-2 fw-bold" id="process-transaction">
                                    <i class="bi bi-check2-circle me-2"></i> Proses Transaksi
                                </button>
                                <button class="btn btn-outline-secondary w-100" id="reset-cart">
                                    <i class="bi bi-trash me-2"></i> Kosongkan Keranjang
                                </button>
                            </div>
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
                    {{-- <button type="button" class="btn btn-success" id="download-thermal-pdf">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF Thermal
                    </button> --}}
                    <button type="button" class="btn btn-primary" id="print-receipt">
                        <i class="bi bi-printer"></i> Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Keranjang kosong',
                        text: 'Keranjang belanja kosong!'
                    });
                    return;
                }

                // Validate customer information
                const customerName = $('#customer_name').val().trim();
                const customerPhone = $('#customer_phone').val().trim();

                // Reset previous validation states
                $('#customer_name').removeClass('is-invalid');
                $('#customer_phone').removeClass('is-invalid');

                let hasError = false;

                if (!customerName) {
                    $('#customer_name').addClass('is-invalid');
                    $('#customer_name').siblings('.invalid-feedback').text('Nama pelanggan harus diisi');
                    hasError = true;
                }

                if (!customerPhone) {
                    $('#customer_phone').addClass('is-invalid');
                    $('#customer_phone').siblings('.invalid-feedback').text('Nomor HP harus diisi');
                    hasError = true;
                } else if (!/^[0-9]{8,14}$/.test(customerPhone)) {
                    $('#customer_phone').addClass('is-invalid');
                    $('#customer_phone').siblings('.invalid-feedback').text('Nomor HP harus 8-14 digit angka');
                    hasError = true;
                }

                if (hasError) {
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
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...'
                    );

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
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Transaksi berhasil.'
                        });

                        // Store transaction ID for PDF download
                        window.currentTransactionId = response.transaction_id;

                        // Load receipt
                        $.get("{{ url('pos/receipt') }}/" + response.transaction_id,
                            function(data) {
                                $('#receipt-content').html(data);
                                $('#receiptModal').modal('show');
                            });

                        // Reset cart and form
                        cart = [];
                        updateCart();
                        $('#customer_name').val('');
                        $('#customer_phone').val('');

                        // Only reload page after modal is closed
                        $('#receiptModal').on('hidden.bs.modal', function() {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMsg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorMsg
                        });
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

            // Phone number validation and formatting
            $('#customer_phone').on('input', function() {
                let value = $(this).val();

                // Remove non-numeric characters
                value = value.replace(/\D/g, '');

                // Limit to 14 digits
                if (value.length > 14) {
                    value = value.substring(0, 14);
                }

                $(this).val(value);
                $(this).removeClass('is-invalid');

                // Real-time validation feedback
                if (value.length > 0 && (value.length < 8 || value.length > 14)) {
                    $(this).addClass('is-invalid');
                    $(this).siblings('.invalid-feedback').text('Nomor HP harus 8-14 digit angka');
                } else if (value.length >= 8 && value.length <= 14) {
                    $(this).removeClass('is-invalid');
                }
            });

            // Print receipt
            $('#print-receipt').click(function() {
                const printContent = $('#receipt-content').html();
                const printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Struk Transaksi</title>');
                printWindow.document.write('<style>');
                printWindow.document.write('@page { size: 58mm auto; margin: 0; }');
                printWindow.document.write('body { font-family: "Courier New", monospace; font-size: 9px; margin: 0; padding: 2mm; width: 54mm; color: #000; background: #fff; }');
                printWindow.document.write('table { width: 100%; border-collapse: collapse; font-size: 8px; }');
                printWindow.document.write('th, td { padding: 1px 2px; border: none; vertical-align: top; }');
                printWindow.document.write('th { font-weight: bold; }');
                printWindow.document.write('hr { border: none; border-top: 1px dashed #000; margin: 2px 0; }');
                printWindow.document.write('.text-center { text-align: center; }');
                printWindow.document.write('.text-end { text-align: right; }');
                printWindow.document.write('h4 { font-size: 12px; margin: 2px 0; font-weight: bold; }');
                printWindow.document.write('p { margin: 1px 0; font-size: 8px; line-height: 1.2; }');
                printWindow.document.write('.receipt { width: 100%; }');
                printWindow.document.write('.mb-4 { margin-bottom: 4px; }');
                printWindow.document.write('thead th { font-size: 7px; }');
                printWindow.document.write('tbody td { font-size: 7px; }');
                printWindow.document.write('tfoot th { font-size: 8px; font-weight: bold; }');
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

            // Download thermal PDF
            $('#download-thermal-pdf').click(function() {
                if (window.currentTransactionId) {
                    window.open("{{ url('pos/thermal-receipt') }}/" + window.currentTransactionId, '_blank');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'ID transaksi tidak ditemukan!'
                    });
                }
            });

            // Update cart view
            function updateCart() {
                $('#cart-table tbody').empty();
                let total = 0;

                if (cart.length === 0) {
                    $('#empty-cart-message').show();
                    $('#cart-table').hide();
                } else {
                    $('#empty-cart-message').hide();
                    $('#cart-table').show();

                    cart.forEach((item, index) => {
                        const subtotal = item.price * item.quantity;
                        total += subtotal;

                        $('#cart-table tbody').append(`
                        <tr>
                            <td class="border-0 py-2">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">${item.name}</span>
                                    <small class="text-muted">Rp ${formatNumber(item.price)} x ${item.quantity}</small>
                                </div>
                            </td>
                            <td class="border-0 py-2 text-center">
                                <input type="number" class="form-control form-control-sm item-quantity text-center"
                                    data-index="${index}" value="${item.quantity}" min="1" style="width: 50px; margin: 0 auto;">
                            </td>
                            <td class="border-0 py-2 text-end">
                                <span class="fw-bold text-success">Rp ${formatNumber(subtotal)}</span>
                            </td>
                            <td class="border-0 py-2 text-center">
                                <button class="btn btn-sm btn-outline-danger remove-item" data-index="${index}" title="Hapus item">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    });
                }

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
            height: 100%;
            min-height: 380px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-box {
            width: 300px;
        }

        .cart-items {
            max-height: 350px;
            overflow-y: auto;
        }

        .menu-name {
            font-size: 0.95rem;
            margin: 0;
            line-height: 1.3;
            min-height: 3.9em;
            max-height: 3.9em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            word-wrap: break-word;
            hyphens: auto;
        }

        .menu-item {
            margin-bottom: 1rem;
        }

        .card-img-top {
            flex-shrink: 0;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        /* Cart Styling */
        .cart-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .cart-card .card-header {
            border-bottom: none;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            padding: 1rem 1.25rem;
        }

        .cart-items-section {
            min-height: 200px;
        }

        .cart-items-header {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
        }

        .cart-items::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .cart-items::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .cart-summary-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .customer-info-section {
            background-color: #f8f9fa;
        }

        .cart-actions-section {
            background-color: #ffffff;
        }

        #empty-cart-message {
            color: #6c757d;
        }

        #empty-cart-message i {
            opacity: 0.5;
        }

        .form-control-sm {
            border-radius: 6px;
            border: 1px solid #ced4da;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control-sm:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        .btn-outline-secondary {
            border-radius: 8px;
            padding: 10px;
            transition: all 0.2s ease;
        }

        .btn-outline-secondary:hover {
            transform: translateY(-1px);
        }

        .table-sm td {
            padding: 0.5rem 0.75rem;
            vertical-align: middle;
        }
    </style>
@endpush

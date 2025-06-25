<div class="receipt">
    <div class="text-center mb-4">
        <h4>Crispy Joss</h4>
        <p>Jl. Raya No. 123<br>Telp: 0831-2993-0489</p>
        <hr>
        <p>STRUK PEMBELIAN</p>
        <p>{{ $transaction->created_at->format('d-m-Y H:i:s') }}</p>
        <p>No. Transaksi: #{{ $transaction->id }}</p>
        <hr>
    </div>
    
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Harga</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->details as $detail)
            <tr>
                <td>{{ $detail->produk->nama_produk }}</td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-end">{{ number_format($detail->price, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total:</th>
                <th class="text-end">{{ number_format($transaction->total_harga, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    
    <hr>
    <div class="text-center">
        <p>Terima kasih telah berbelanja di Crispy Joss</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
    </div>
</div> 
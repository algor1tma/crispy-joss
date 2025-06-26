<div class="receipt">
    <div class="text-center mb-4">
        <h4>Crispy Joss</h4>
        <p>Jl. Raya No. 123<br>Telp: 0831-2993-0489</p>
        <hr>
        <p><strong>STRUK PEMBELIAN</strong></p>
        <p>{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        <p>No: #{{ $transaction->id }}</p>
        <hr>
    </div>

    <table class="table table-sm">
        <tbody>
            @foreach($transaction->details as $detail)
            <tr>
                <td colspan="4" class="item-name">{{ $detail->produk->nama_produk }}</td>
            </tr>
            <tr>
                <td class="text-start">{{ $detail->quantity }} x</td>
                <td class="text-end">{{ number_format($detail->price, 0, ',', '.') }}</td>
                <td></td>
                <td class="text-end">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><hr></td>
            </tr>
            <tr>
                <th colspan="3" class="text-start"><strong>TOTAL:</strong></th>
                <th class="text-end"><strong>{{ number_format($transaction->total_harga, 0, ',', '.') }}</strong></th>
            </tr>
        </tfoot>
    </table>

    <hr>
    <div class="text-center">
        <p><small>Terima kasih telah berbelanja<br>di Crispy Joss</small></p>
        <p><small>Barang yang sudah dibeli<br>tidak dapat dikembalikan</small></p>
    </div>
</div>

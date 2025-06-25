<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanPenjualan extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'laporanpenjualans';

    protected $fillable = [
        'tanggal',
        'total_penjualan',
        'total_produk_terjual',
        'jumlah_transaksi',
    ];

    protected $dates = [
        'tanggal',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'tanggal_transaksi',
        'total_harga',
        'customer_name',
        'customer_phone',
        'user_id'
    ];

    protected $dates = ['tanggal_transaksi', 'created_at', 'updated_at', 'deleted_at'];
    
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

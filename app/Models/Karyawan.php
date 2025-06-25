<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;
    
    // Tentukan nama tabel
    protected $table = 'karyawan';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'user_id',
        'nama',
        'no_telp',
        'alamat',
        'foto',
    ];

    // Menyertakan kolom deleted_at untuk soft delete
    protected $dates = ['deleted_at'];

    // Relasi ke User (satu user bisa memiliki satu karyawan)
    public function user()
    {
        // Relasi karyawan ke user dengan soft delete
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama',
        'jenis_kelamin',
        'no_telp',
        'alamat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

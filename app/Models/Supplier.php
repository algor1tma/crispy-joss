<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rawMaterialLogs()
    {
        return $this->hasMany(RawMaterialLog::class);
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    use HasFactory;
    protected $table='penjualan';

    protected $guarded = ['id'];

    public $timestamps = false;


    public function pelanggan():BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'kd_pelanggan', 'kd_pelanggan');
    }

    public function kasir():BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'kd_user', 'kd_pengguna');
    }
}

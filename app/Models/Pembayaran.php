<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'pembayaran';
    public $timestamps = false;


    public function customer():BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan', 'kd_pelanggan');
    }

    public function kasir():BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'kd_user', 'kd_pengguna');
    }
}

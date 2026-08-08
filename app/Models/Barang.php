<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'master_barang';
    protected $primaryKey = 'kd_barang';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
    public $timestamps = false;

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'kd_supplier', 'kd_supplier');
    }
}

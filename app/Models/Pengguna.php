<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Pengguna extends Authenticatable
{
    use HasFactory;
    use Notifiable;


    protected $table = 'master_pengguna';
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];
}

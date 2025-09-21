<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // <- importante
use Illuminate\Notifications\Notifiable;

class Administrador extends Authenticatable
{
    use Notifiable;

    protected $table = 'administradores';
    protected $primaryKey = 'id_admin';
    public $timestamps = true;

    protected $fillable = ['nome','email','senha_hash'];

    protected $hidden = ['senha_hash'];

}

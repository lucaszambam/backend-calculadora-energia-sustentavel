<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TipoEnergia extends Model {
    protected $table = 'tipos_energia';
    protected $primaryKey = 'id_tipo_energia';
    public $timestamps = false;
    protected $fillable = ['descricao'];
}

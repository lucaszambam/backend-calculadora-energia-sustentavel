<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TipoInstalacao extends Model {
    protected $table = 'tipos_instalacao';
    protected $primaryKey = 'id_tipo_instalacao';
    public $timestamps = false;
    protected $fillable = ['descricao'];
}
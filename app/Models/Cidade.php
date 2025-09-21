<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model {
    protected $table = 'cidades';
    protected $primaryKey = 'id_cidade';
    public $timestamps = false;
    protected $fillable = ['nome','id_estado'];

    public function estado() { 
        return $this->belongsTo(Estado::class, 'id_estado'); 
    }

    public function parametro() { 
        return $this->hasOne(Parametro::class, 'id_cidade'); 
    }

    public function simulacoes() { 
        return $this->hasMany(Simulacao::class, 'id_cidade'); 
    }
    
}

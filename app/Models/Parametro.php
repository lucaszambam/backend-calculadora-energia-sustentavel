<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model {
    protected $table = 'parametros';
    protected $primaryKey = 'id_parametro';
    protected $fillable = [
        'id_cidade',
        'tarifa_base',
        'taxa_distribuicao',
        'co2_por_kwh',
    ];


    public function cidade() { 
        return $this->belongsTo(Cidade::class, 'id_cidade'); 
    }
    
    public function eficiencias() { 
        return $this->hasMany(Eficiencia::class, 'id_parametro'); 
    }
}
<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Eficiencia extends Model {
    protected $table = 'eficiencias';
    protected $fillable = ['id_parametro','id_segmento','eficiencia_valor'];

    public function parametro() { 
        return $this->belongsTo(Parametro::class, 'id_parametro'); 
    }

    public function segmento() { 
        return $this->belongsTo(Segmento::class, 'id_segmento'); 
    }
}
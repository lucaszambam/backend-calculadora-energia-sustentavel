<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Eficiencia extends Model {
    protected $table = 'eficiencias';
    protected $fillable = [
        'id_parametro',
        'id_segmento',
        'id_tipo_energia',
        'id_tipo_instalacao',
        'eficiencia_valor'
    ];

    public function parametro() { 
        return $this->belongsTo(Parametro::class, 'id_parametro'); 
    }

    public function segmento() { 
        return $this->belongsTo(Segmento::class, 'id_segmento'); 
    }

    public function tipoEnergia() { 
        return $this->belongsTo(TipoEnergia::class, 'id_tipo_energia'); 
    }

    public function tipoInstalacao() { 
        return $this->belongsTo(TipoInstalacao::class, 'id_tipo_instalacao'); 
    }

}
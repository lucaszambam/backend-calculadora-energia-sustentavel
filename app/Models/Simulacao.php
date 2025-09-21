<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Simulacao extends Model {
    protected $table = 'simulacoes';
    protected $primaryKey = 'id_simulacao';
    public $timestamps = false;

    protected $fillable = [
        'id_cidade','id_tipo_energia','id_tipo_instalacao','id_segmento','id_parametro',
        'valor_conta_medio','consumo_kwh_estimado','economia_reais','economia_percentual',
        'co2_evitado','status_contato','nome_contato','email_contato','telefone_contato','data_hora'
    ];

    public function cidade() { 
        return $this->belongsTo(Cidade::class, 'id_cidade'); 
    }

    public function tipoEnergia() { 
        return $this->belongsTo(TipoEnergia::class, 'id_tipo_energia'); 
    }

    public function tipoInstalacao() { 
        return $this->belongsTo(TipoInstalacao::class, 'id_tipo_instalacao'); 
    }

    public function segmento() { 
        return $this->belongsTo(Segmento::class, 'id_segmento'); 
    }
    
    public function parametro() { 
        return $this->belongsTo(Parametro::class, 'id_parametro'); 
    }
}

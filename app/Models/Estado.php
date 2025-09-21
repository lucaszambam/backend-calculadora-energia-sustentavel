<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model {
    protected $table = 'estados';
    protected $primaryKey = 'id_estado';
    public $timestamps = false;
    protected $fillable = ['sigla','nome'];

    public function cidades() { 
        return $this->hasMany(Cidade::class, 'id_estado'); 
    }
}
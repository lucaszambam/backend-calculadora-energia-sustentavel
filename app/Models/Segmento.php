<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Segmento extends Model {
    protected $table = 'segmentos';
    protected $primaryKey = 'id_segmento';
    public $timestamps = false;
    protected $fillable = ['nome'];
}
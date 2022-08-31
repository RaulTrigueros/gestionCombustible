<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyecto';
    protected $fillable = ['idEncargado', 'nombre', 'descripcion', 'montoEstimado', 'montoReal'];

    public $timestamps = false;

    public function encargado(){
        return $this->hasOne('App\Encargado');
    }

    public function financiamientos(){
        return $this->hasMany("App\Financiamiento");
    }

    public function actividades(){
        return $this->hasMany('App\Actividad');
    }

    public function estatus(){
        return $this->hasMany('App\Estatus');
    }
}

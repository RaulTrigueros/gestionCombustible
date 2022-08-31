<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoEstatus extends Model
{
    protected $table = 'tipoestatus';
    protected $fillable = ['nombre', 'descripcion'];

    public $timestamps = false;

    public function estatusProyecto(){
        return $this->hasMany('App\EstatusProyecto');
    }

    public function estatusActividad()
    {
        return $this->hasMany('App\EstatusActividad');
    }
}

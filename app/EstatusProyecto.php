<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstatusProyecto extends Model
{
    protected $table = "estatus_proyecto";
    protected $fillable = ['idProyecto','idTipoEstatus','actual','fecha', 'hora'];

    public $timestamps = false;

    public function proyecto(){
        return $this->belongsTo('App\Proyecto');
    }

    public function tipoEstatus(){
        return $this->belongsTo("App\TipoEstatus");
    }
}

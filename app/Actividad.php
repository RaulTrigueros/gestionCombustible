<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $fillable = ['idProyecto', 'idEstatus', 'nombre', 'descripcion', 'avance', 'costoEstimado', 'costoReal', 'inicio', 'fin'];

    public $timestamps = false;

    public function proyecto(){
        return $this->belongsTo('App\Proyecto');
    }
}

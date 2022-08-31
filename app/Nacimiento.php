<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nacimiento extends Model
{
  
    protected $table='nacimiento';
    protected $fillable = ['paginas','numeropartida','anio','nombres','sexo','hora','fecha','lugarnacimiento','idpadre','descripcion','idpadre',
    'idmadre','nombres_tutor','apellidos_tutor','manifiesto_tutor','dui_tutor','expedido_tutor','fecha_expedicion']; 
    public $timestamps = false;
}






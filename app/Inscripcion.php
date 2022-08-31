<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    //
    protected $table = 'inscripciones';
    protected $fillable = ['idprograma','nombre','apellido','correo','telefono','edad','comunidad','sexo','dui'];

    public function programa(){
        return $this->hasOne('App\Programa');
    }
}

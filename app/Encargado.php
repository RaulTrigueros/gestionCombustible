<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encargado extends Model
{
    protected $table = 'encargado';
    protected $fillable = ['nombre', 'nit', 'telefono', 'direccion'];

    public $timestamps = false;

    public function proyectos(){
        return $this->hasMany('App\Proyecto');
    }

}

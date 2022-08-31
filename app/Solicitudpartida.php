<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitudpartida extends Model
{
    protected $fillable=[
        'idtipodocumento','nombre','dui'.'email','telefono','anombrede','fecha_realizado','estado'
    ];
    
    public function tipodocumento(){
        return $this->belongsTo('App\Tipodocumento');
    }
    
}

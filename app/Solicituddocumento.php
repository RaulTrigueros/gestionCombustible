<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicituddocumento extends Model
{
    protected $fillable=[
        'nombre','dui'.'email','telefono','informacion_solicitada','formato','estado'
    ];

}

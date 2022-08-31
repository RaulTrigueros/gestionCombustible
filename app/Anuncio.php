<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anuncio extends Model
{
    protected $fillable=[
        'titulo','autor','descripcion','fecha_anuncio','estado','imagen'
    ];
}

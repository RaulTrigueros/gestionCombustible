<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    protected $fillable = ['titulo','autor','fecha_noticia','cuerpo','estado','imagen'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagenCarrusel extends Model
{
    protected $table = 'imagen_carrusel';

    protected $fillable = ['id_carrusel', 'titulo', 'descripcion', 'imagen'];
}

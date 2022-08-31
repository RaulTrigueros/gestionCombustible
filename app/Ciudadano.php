<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudadano extends Model
{
    //
    
    protected $table='ciudadano';
    protected $fillable = ['nombres','apellidos','edad','oficio','lugarorigen','dui'];
    public $timestamps = false;
}

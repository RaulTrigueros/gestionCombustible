<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManualOrganizacion extends Model
{
    protected $table = 'manual_organizacion';
    protected $fillable = ['nombre', 'descripcion', 'archivo'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organigrama extends Model
{
   protected $fillable=[
        'descripcion','imagen','estado'
    ];
}

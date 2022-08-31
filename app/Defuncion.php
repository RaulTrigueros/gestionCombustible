<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Defuncion extends Model
{
    protected $table='defuncion';
     protected $fillable = ['paginas','anio','numeropartida','nombres','sexo','edad','estadofam','oficio','lugarorigen'.'domicilio','nacionalidad',
                            'nombrepadre','nombremadre','hora','fechamuerte','lugarmuerte','causa','duifallecido','nombreregistrador','parentesco','duiregistrador',
                             'nombretestigo','duitestigo','fechapartida']; 
}

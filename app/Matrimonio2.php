<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matrimonio2 extends Model
{
    protected $table='matrimonio';
    protected $fillable = ['pagina','numeropartida','anio','fechaPartida',
    'nombreEsposo','apellido1Esposo','apellido2Esposo','edadEsposo','ocupacionEsposo',
    'estadoCivilEsposo','duiEsposo','nacionalidadEsposo',
    'nombrePadreEsposo','apellidoPadreEsposo','nombreMadreEsposo','apellidoMadreEsposo',
    'nombreEsposa','apellido1Esposa','apellido2Esposa','edadEsposa','ocupacionEsposa',
    'estadoCivilEsposa','duiEsposa','nacionalidadEsposa',
    'nombrePadreEsposa','apellidoPadreEsposa','nombreMadreEsposa','apellidoMadreEsposa',
    'apellidoCasada','regimen',
    'nombreTestigo1','apellidoTestigo1','duiTestigo1',
    'nombreTestigo2','apellidoTestigo2','duiTestigo2'];
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Carrusel;
use Illuminate\Support\Facades\DB;


class CarruselController extends Controller
{
    public function getCarrusel($id_carrusel){
    	$carrusel = Carrusel::findOrFail($id_carrusel);
    	return $carrusel;
    }
}

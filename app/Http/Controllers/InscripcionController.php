<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Inscripcion;
use App\Programa;

class InscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $programa = Programa::findOrFail($request->idprograma);
        
        $numInscripciones = DB::table("inscripciones")->where('idprograma', $programa->id)->count();
        //return $programa->max_participante > $numInscripciones;
         if( $programa->max_participante > $numInscripciones){
            $inscripcion = new Inscripcion();
            $inscripcion->idprograma = $request->idprograma;
            $inscripcion->nombre = $request->nombre;
            $inscripcion->apellido = $request->apellido;
            $inscripcion->sexo = $request->sexo;
            $inscripcion->comunidad = $request->comunidad;
            $inscripcion->dui = $request->dui;
            $inscripcion->edad = $request->edad;
            $inscripcion->telefono = $request->telefono;
            $inscripcion->correo = $request->correo;
            try {
                $inscripcion->save();
                return "Exito";
            } catch (\Throwable $th) {
                return $th;
            }
            
             
         }else{
             return "Fracaso";
         }

        

        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}

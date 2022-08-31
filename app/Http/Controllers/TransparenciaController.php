<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DocumentoTransparencia;
use Illuminate\Support\Facades\DB;

class TransparenciaController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //
        $documentoTransparencia = new DocumentoTransparencia;
        $documentoTransparencia->iduser = \Auth::user()->id;
        $documentoTransparencia->nombre = $request->nombre;
        $documentoTransparencia->nombrearchivo = $request->nombrearchivo;
        $documentoTransparencia->descripcion = $request->descripcion;
        $documentoTransparencia->archivo = $request->archivo;
        $documentoTransparencia->fecha= new \DateTime();

        try {
            $documentoTransparencia->save();
            return "exito";
        } catch (\Throwable $th) {
            return $th;
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

    public function getDocumentos(Request $request){
        $documentos = DB::table('documentotransparencia')->get();
        return $documentos;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        try {
            $documento = DocumentoTransparencia::findOrFail($request->id);
            $documento->nombre = $request->nombre;
            $documento->nombrearchivo =$request->nombrearchivo;
            $documento->descripcion = $request->descripcion;
            $documento->save();
            return "exito";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function delete(Request $request)
    {
        try {
            $documento = DocumentoTransparencia::findOrFail($request->id);
            $documento->delete();
            return "exito";
        } catch (\Throwable $th) {
            return $th;
        }
    }

}

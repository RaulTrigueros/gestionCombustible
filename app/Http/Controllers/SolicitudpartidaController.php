<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Solicitudpartida;

class SolicitudpartidaController extends Controller
{
    public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $solicitudpartidas = Solicitudpartida::join('tipodocumentos','solicitudpartidas.idtipodocumento','=','tipodocumentos.id')
            ->select('solicitudpartidas.id','solicitudpartidas.idtipodocumento','tipodocumentos.nombre as nombre_tipodocumento',
                     'solicitudpartidas.nombre','solicitudpartidas.dui','solicitudpartidas.email','solicitudpartidas.telefono',
                     'solicitudpartidas.anombrede','solicitudpartidas.fecha_realizado','solicitudpartidas.estado')
            ->orderBy('solicitudpartidas.id','desc')->paginate(10);
        }else{
            $solicitudpartidas = Solicitudpartida::join('tipodocumentos','solicitudpartidas.idtipodocumento','=','tipodocumentos.id')
            ->select('solicitudpartidas.id','solicitudpartidas.idtipodocumento','tipodocumentos.nombre as nombre_tipodocumento',
                     'solicitudpartidas.nombre','solicitudpartidas.dui','solicitudpartidas.email','solicitudpartidas.telefono',
                     'solicitudpartidas.anombrede','solicitudpartidas.fecha_realizado','solicitudpartidas.estado')
            ->where('solicitudpartidas.'.$criterio,'like','%'. $buscar .'%')
            ->orderBy('solicitudpartidas.id','desc')->paginate(10);
        }
        
         return [
            'pagination'=> [
                'total' => $solicitudpartidas->total(),
                'current_page' => $solicitudpartidas->currentPage(),
                'per_page' => $solicitudpartidas->perPage(),
                'last_page' => $solicitudpartidas->lastPage(),
                'from' => $solicitudpartidas->firstItem(),
                'to' => $solicitudpartidas->lastItem(),
            ],
            'solicitudpartidas' => $solicitudpartidas
        ];
    }
    
     public function store(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
         
        $solicitudpartida = new Solicitudpartida();
        $solicitudpartida->idtipodocumento = $request->idtipodocumento;
        $solicitudpartida->nombre = $request->nombre;
        $solicitudpartida->dui = $request->dui;
        $solicitudpartida->email = $request->email;
        $solicitudpartida->telefono = $request->telefono;
        $solicitudpartida->anombrede = $request->anombrede;
        $solicitudpartida->fecha_realizado = $request->fecha_realizado;
        $solicitudpartida->estado= '1';
        $solicitudpartida->save();
    }
    
     public function desactivar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $solicitudpartida = Solicitudpartida::findOrFail($request->id);
        $solicitudpartida-> estado = '0';
        $solicitudpartida->save();
    }

    public function activar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $solicitudpartida = Solicitudpartida::findOrFail($request->id);
        $solicitudpartida-> estado = '1';
        $solicitudpartida->save();
    }
    
}

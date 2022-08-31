<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Solicituddocumento;

class SolicituddocumentoController extends Controller
{
    public function index(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $solicituddocumentos = Solicituddocumento::select('id','nombre','dui','email','telefono',
                    'informacion_solicitada','formato','estado')
            ->orderBy('solicituddocumentos.id','desc')->paginate(10);
        }else{
            $solicituddocumentos = Solicituddocumento::select('id','nombre','dui','email','telefono',
                    'informacion_solicitada','formato','estado')
            ->where('solicituddocumentos.'.$criterio,'like','%'. $buscar .'%')
            ->orderBy('solicituddocumentos.id','desc')->paginate(10);
        }

         return [
            'pagination'=> [
                'total' => $solicituddocumentos->total(),
                'current_page' => $solicituddocumentos->currentPage(),
                'per_page' => $solicituddocumentos->perPage(),
                'last_page' => $solicituddocumentos->lastPage(),
                'from' => $solicituddocumentos->firstItem(),
                'to' => $solicituddocumentos->lastItem(),
            ],
            'solicituddocumentos' => $solicituddocumentos
        ];
    }

     public function store(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');

        $solicituddocumento = new Solicituddocumento();
        $solicituddocumento->nombre = $request->nombre;
        $solicituddocumento->dui = $request->dui;
        $solicituddocumento->email = $request->email;
        $solicituddocumento->telefono = $request->telefono;
        $solicituddocumento->informacion_solicitada = $request->informacion_solicitada;
        $solicituddocumento->formato = $request->formato;
        $solicituddocumento->estado= '1';
        $solicituddocumento->save();
    }

     public function desactivar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $solicituddocumento = Solicituddocumento::findOrFail($request->id);
        $solicituddocumento-> estado = '0';
        $solicituddocumento->save();
    }

    public function activar(Request $request)
    {
        //if(!$request->ajax()) return redirect('/');
        $solicituddocumento = Solicituddocumento::findOrFail($request->id);
        $solicituddocumento-> estado = '1';
        $solicituddocumento->save();
    }

}

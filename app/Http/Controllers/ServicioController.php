<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Servicio;

class ServicioController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
//         $anuncios = Anuncio::all();
//        return $anuncios;
 
        $buscar = $request->buscar;
        $criterio = $request->criterio;
         
        if ($buscar==''){
            $servicios =DB::table('servicio')
            ->select('servicio.id','servicio.nombre','servicio.descripcion','servicio.estado')->orderBy('servicio.id', 'desc')->paginate(3);
//            $anuncios = DB::table('anuncios')
//           ->select('anuncios.id','anuncios.titulo','anuncios.autor','anuncios.descripcion','anuncios.fecha_anuncio','anuncios.estado','anuncios.imagen')
//            ->orderBy('anuncios.id', 'desc')->paginate(3);
        }
        else{
            $servicios = DB::table('servicio')
            ->select('servicio.id','servicio.nombre','servicio.descripcion','servicio.estado')
            ->where('servicio.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('servicio.id', 'desc')->paginate(3);
        }
         
 
        return [
            'pagination' => [
                'total'        => $servicios->total(),
                'current_page' => $servicios->currentPage(),
                'per_page'     => $servicios->perPage(),
                'last_page'    => $servicios->lastPage(),
                'from'         => $servicios->firstItem(),
                'to'           => $servicios->lastItem(),
            ],
            'servicios' => $servicios
        ];
    }
     
    public function registroServicio(Request $request)
    {

      //  return 'ingresando ';
       // if (!$request->ajax()) return redirect('/');
         
        
           // DB::beginTransaction();
            $servicio = new Servicio();
            $servicio->nombre = $request->nombre;
            $servicio->descripcion = $request->descripcion;
           
           // DB::commit();
           try {
            $servicio->save();
            return "exito";
        } catch (\Throwable $th) {
            return $th;
        }

         
         
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $servicio = Servicio::findOrFail($request->id);
        $servicio->nombre = $request->nombre;
        $servicio->descripcion = $request->descripcion;
        $servicio->save();
    }


    public function getServicios(Request $request)
    {

            $servicios =DB::table('servicio')->Where('estado', 1)->get();
            return $servicios;

    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $servicio = Servicio::findOrFail($request->id);
        $servicio->estado = '1';
        $servicio->save();
    }
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $servicio = Servicio::findOrFail($request->id);
        $servicio->estado = '0';
        $servicio->save();
    }

}

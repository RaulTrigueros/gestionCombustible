<?php

namespace App\Http\Controllers;

use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Anuncio;

class AnuncioController extends Controller
{
    protected $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
//         $anuncios = Anuncio::all();
//        return $anuncios;

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $anuncios = Anuncio::orderBy('anuncios.id', 'desc')->paginate(3);
//            $anuncios = DB::table('anuncios')
//           ->select('anuncios.id','anuncios.titulo','anuncios.autor','anuncios.descripcion','anuncios.fecha_anuncio','anuncios.estado','anuncios.imagen')
//            ->orderBy('anuncios.id', 'desc')->paginate(3);
        }
        else{
            $anuncios = DB::table('anuncios')
            ->select('anuncios.id','anuncios.titulo','anuncios.autor','anuncios.descripcion','anuncios.fecha_anuncio','anuncios.estado','anuncios.imagen')
            ->where('anuncios.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('anuncios.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total'        => $anuncios->total(),
                'current_page' => $anuncios->currentPage(),
                'per_page'     => $anuncios->perPage(),
                'last_page'    => $anuncios->lastPage(),
                'from'         => $anuncios->firstItem(),
                'to'           => $anuncios->lastItem(),
            ],
            'anuncios' => $anuncios
        ];
    }

    public function registroAnuncio(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            $anuncio = new Anuncio();
            $anuncio->titulo = $request->titulo;
            $anuncio->autor = $request->autor;
            $anuncio->descripcion = $request->descripcion;
            $anuncio->fecha_anuncio = $request->fecha_anuncio;
            $anuncio->estado = '1';
            $anuncio->imagen = $request->imagen;
            $anuncio->save();
            $this->bitacoraService->store('Creación de registro', 'Anuncios');
            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }



    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $anuncio = Anuncio::findOrFail($request->id);
        $anuncio->titulo = $request->titulo;
        $anuncio->autor = $request->autor;
        $anuncio->descripcion = $request->descripcion;
        $anuncio->fecha_anuncio = $request->fecha_anuncio;
        $anuncio->estado = '1';
        $anuncio->imagen = $request->imagen;
        $anuncio->save();
        $this->bitacoraService->store('Actualización de registro', 'Anuncios');
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $anuncio = Anuncio::findOrFail($request->id);
        $anuncio->estado = '0';
        $anuncio->save();
        $this->bitacoraService->store('Actualización de registro', 'Anuncios');
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $anuncio = Anuncio::findOrFail($request->id);
        $anuncio->estado = '1';
        $anuncio->save();
        $this->bitacoraService->store('Actualización de registro', 'Anuncios');
    }

    public function getAnunciosActivos(Request $request){
        $anuncios = DB::table('anuncios')->Where('estado', 1)->orderBy('anuncios.id', 'desc')->get();
        return $anuncios;
    }
}

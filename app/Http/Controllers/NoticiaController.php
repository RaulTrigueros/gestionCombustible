<?php

namespace App\Http\Controllers;

use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Noticia;

class NoticiaController extends Controller
{
    protected $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }

    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $noticias = Noticia::orderBy('id','desc')->paginate(3);
        }else{
            $noticias = Noticia::where('noticias.'.$criterio,'like','%'. $buscar .'%')->orderBy('id','desc')->paginate(3);
        }

         return [
            'pagination'=> [
                'total' => $noticias->total(),
                'current_page' => $noticias->currentPage(),
                'per_page' => $noticias->perPage(),
                'last_page' => $noticias->lastPage(),
                'from' => $noticias->firstItem(),
                'to' => $noticias->lastItem(),
            ],
            'noticias' => $noticias
        ];
    }

    public function store(Request $request)
    {
        $noticia = new Noticia();
        $noticia->titulo=$request->titulo;
        $noticia->autor = $request->autor;
        $noticia->fecha_noticia = $request->fecha_noticia;
        $noticia->cuerpo=$request->cuerpo;
        $noticia->estado= '1';
        $noticia->imagen = $request->imagen; //base64_decode($encoded_image);
        $noticia->save();
        $this->bitacoraService->store('Creación de registro', 'Noticias');
    }

    public function update(Request $request)
    {
        $noticia = Noticia::findOrFail($request->id);
        $noticia->titulo=$request->titulo;
        $noticia->autor = $request->autor;
        $noticia->fecha_noticia = $request->fecha_noticia;
        $noticia->cuerpo=$request->cuerpo;
        $noticia->estado= '1';
        $noticia->imagen = $request->imagen; //base64_decode($encoded_image);
        $noticia->save();
        $this->bitacoraService->store('Actualización de registro', 'Noticias');
    }


    public function desactivar(Request $request)
    {
        $noticia = Noticia::findOrFail($request->id);
        $noticia-> estado = '0';
        $noticia->save();
        $this->bitacoraService->store('Actualización de registro', 'Noticias');
    }

    public function activar(Request $request)
    {
        $noticia = Noticia::findOrFail($request->id);
        $noticia-> estado = '1';
        $noticia->save();
        $this->bitacoraService->store('Actualización de registro', 'Noticias');
    }

    public function getNoticias(Request $request)
    {
        $noticias = DB::table('noticias')->Where('estado', 1)->orderBy('id','asc')->get();
        return $noticias;
    }
}

<?php

namespace App\Http\Controllers;

use App\Encargado;
use App\Services\BitacoraService;
use Illuminate\Http\Request;

class EncargadoController extends Controller
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
            $encargados = Encargado::select('id','nombre','nit','telefono','direccion')
                ->orderBy('nombre','asc')->paginate(10);
        }else{
            $encargados = Encargado::select('id','nombre','nit','telefono','direccion')
                ->where($criterio,'like','%'. $buscar .'%')
                ->orderBy('nombre','asc')->paginate(10);
        }

        return [
            'pagination'=> [
                'total' => $encargados->total(),
                'current_page' => $encargados->currentPage(),
                'per_page' => $encargados->perPage(),
                'last_page' => $encargados->lastPage(),
                'from' => $encargados->firstItem(),
                'to' => $encargados->lastItem(),
            ],
            'encargados' => $encargados
        ];
    }

    public function selectEncargado(Request $request){
        $encargados = Encargado::select('id','nombre')->orderBy('nombre','asc')->get();
        return ['encargados' => $encargados];
    }

    public function store(Request $request){

        try{
            $encargado = new Encargado();
            $encargado->nombre = $request->nombre;
            $encargado->nit = $request->nit;
            $encargado->telefono = $request->telefono;
            $encargado->direccion = $request->direccion;
            $encargado->save();

            $this->bitacoraService->store('Creación de registro', 'Encargado');
            return "Éxito";
        }catch(\Throwable $th){
            return $th;
        }
    }

    public function update(Request $request){
        $encargado = Encargado::findOrFail($request->id);
        $encargado->nombre = $request->nombre;
        $encargado->nit = $request->nit;
        $encargado->telefono = $request->telefono;
        $encargado->direccion = $request->direccion;
        $encargado->save();

        $this->bitacoraService->store('Actualización de registro', 'Encargado');
        return "Éxito";
    }

}

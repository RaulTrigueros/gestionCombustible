<?php

namespace App\Http\Controllers;

use App\Actividad;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActividadController extends Controller
{
    protected $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $actividad = new Actividad();
        $actividad->idProyecto = $request->idProyecto;
        $actividad->idEstatus = $request->idEstatus;
        $actividad->nombre = $request->nombre;
        $actividad->descripcion = $request->descripcion;
        $actividad->avance = $request->avance;
        $actividad->costoEstimado = $request->costoEstimado;
        $actividad->costoReal = $request->costoReal;
        $actividad->inicio = $request->inicio;
        $actividad->fin = $request->fin;

        try {
            $actividad->save();
            $this->bitacoraService->store('Creación de registro', 'Actividades');
        } catch (\Throwable $th) {
            return $th;
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function show($proyecto)
    {
        $actividades = DB::table('actividades')->where('idProyecto', $proyecto)->get();
        return $actividades;
    }

    public function buscar(Request $request){
        try{


        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $idProyecto = $request->idProyecto;

        if($buscar == ''){
            $actividades = DB::table('actividades')
                        ->join('proyecto', 'actividades.idProyecto', '=', 'proyecto.id')
                ->select(
                'actividades.id',
                'actividades.idProyecto',
                'actividades.idEstatus',
                'actividades.nombre',
                'actividades.descripcion',
                'actividades.avance',
                'actividades.costoEstimado',
                'actividades.costoReal',
                'actividades.inicio',
                'actividades.fin'
                )->where('actividades.idProyecto', $idProyecto)
                ->orderBy('actividades.id', 'desc')->paginate(5);
        }else{
            $actividades =  Actividad::join('proyecto', 'actividades.idProyecto', '=', 'proyecto.id')
                ->select(
                'actividades.id',
                'actividades.idProyecto',
                'actividades.idEstatus',
                'actividades.nombre',
                'actividades.descripcion',
                'actividades.avance',
                'actividades.costoEstimado',
                'actividades.costoReal',
                'actividades.inicio',
                'actividades.fin'
                )
                ->where('actividades.' . $criterio, 'like', '%' . $buscar . '%')
                ->where('actividades.idProyecto', $idProyecto)
                ->orderBy('actividades.id', 'desc')->paginate(3);
        }
        return [
            'pagination' => [
                'total' => $actividades->total(),
                'current_page' => $actividades->currentPage(),
                'per_page' => $actividades->perPage(),
                'last_page' => $actividades->lastPage(),
                'from' => $actividades->firstItem(),
                'to' => $actividades->lastItem(),
            ],
            'actividades' => $actividades
        ];
        }
        catch (\Throwable $th){
            return $th;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function edit(Actividad $actividad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $actividad)
    {
        try {
            $actividad = Actividad::findOrFail($actividad);
        } catch (\Throwable $th) {
            return $th;
        }

        $actividad->nombre = $request->nombre;
        $actividad->idEstatus = $request->idEstatus;
        $actividad->descripcion = $request->descripcion;
        $actividad->avance = $request->avance;
        $actividad->costoEstimado = $request->costoEstimado;
        $actividad->costoReal = $request->costoReal;
        $actividad->inicio = $request->inicio;
        $actividad->fin = $request->fin;

        try {
            $actividad->save();
            $this->bitacoraService->store('Actualización de registro', 'Actividades');
            return 'exito';
        } catch (\Throwable $th) {
            return $th;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function destroy($idActividad)
    {
        try {
            $actividad = Actividad::findOrFail($idActividad);
            $actividad->delete();
            $this->bitacoraService->store('Eliminación de registro', 'Actividades');
            return 'exito';
        } catch (\Throwable $th) {
            return $th;
        }
    }


    public function sumar5($numero)
    {
       return $numero + 5;
    }
}

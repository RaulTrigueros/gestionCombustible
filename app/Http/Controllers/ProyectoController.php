<?php

namespace App\Http\Controllers;

use App\Actividad;
use App\Proyecto;
use App\Services\BitacoraService;
use App\EstatusProyecto;
use Cassandra\Decimal;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Polyfill\Ctype\Ctype;

class ProyectoController extends Controller
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
            $proyectos = Proyecto::join('encargado','proyecto.idEncargado','=','encargado.id')
                ->select('proyecto.id','proyecto.idEncargado','encargado.nombre as nombre_encargado',
                    'proyecto.nombre','proyecto.descripcion','proyecto.montoEstimado')
                ->orderBy('proyecto.id','desc')->paginate(10);
        }else{
            $proyectos =  Proyecto::join('encargado','proyecto.idEncargado','=','encargado.id')
                ->select('proyecto.id','proyecto.idEncargado','encargado.nombre as nombre_encargado',
                    'proyecto.nombre','proyecto.descripcion','proyecto.montoEstimado')
                ->where('proyecto.'.$criterio,'like','%'. $buscar .'%')
                ->orderBy('proyecto.id','desc')->paginate(3);
        }

        return [
            'pagination'=> [
                'total' => $proyectos->total(),
                'current_page' => $proyectos->currentPage(),
                'per_page' => $proyectos->perPage(),
                'last_page' => $proyectos->lastPage(),
                'from' => $proyectos->firstItem(),
                'to' => $proyectos->lastItem(),
            ],
            'proyectos' => $proyectos
        ];
    }

    public function store(Request $request)
    {
        $proyecto = new Proyecto();
        $proyecto->idEncargado = $request->idEncargado;
        $proyecto->nombre = $request->nombre;
        $proyecto->descripcion = $request->descripcion;
        $proyecto->montoEstimado = $request->monto;
        $proyecto->montoReal = $request->monto;
        try {
            $proyecto->save();

            //creando status
            $estatusProyecto = new EstatusProyecto();
            $estatusProyecto->idProyecto = $proyecto->id;
            $estatusProyecto->idTipoEstatus = 1;
            $estatusProyecto->actual=true;
            $estatusProyecto->fecha = date("Y-m-d ", time());
            $estatusProyecto->hora = date("H:i:s", time());
            $estatusProyecto->save();

            $this->bitacoraService->store('Creación de registro', 'Proyecto');
            return "Éxito";
        } catch (\Throwable $th) {
            return $th;
        }

    }

    public function finalizarProyecto($idProyecto){
        try {
            DB::table('estatus_proyecto')
                ->select('estatus_proyecto.*')
                ->where('idProyecto',$idProyecto)
                ->update(['idTipoEstatus' => 3]);
            $this->bitacoraService->store('Actualización de registro', 'Estatus_Proyecto');
            return "Éxito";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function getEstatusProyecto($idProyecto)
    {
        try {
            $estatusProyecto = DB::table('estatus_proyecto')
                ->select('estatus_proyecto.*')
                ->where('idProyecto', $idProyecto)
                ->get();
            return $estatusProyecto;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function update(Request $request)
    {
        $proyecto = Proyecto::findOrFail($request->id);

        if($proyecto != null){
            $proyecto->idEncargado = $request->idEncargado;
            $proyecto->nombre = $request->nombre;
            $proyecto->descripcion = $request->descripcion;
            $proyecto->montoEstimado = $request->monto;
            try {
                $proyecto->save();
                $this->bitacoraService->store('Actualización de registro', 'Proyecto');
                return "Éxito";
            } catch (\Throwable $th) {
                return $th;
            }
        }

    }

    public function getProyecto(Request $request){
        $proyecto = Proyecto::findOrFail($request->id);
        return $proyecto;
    }

}

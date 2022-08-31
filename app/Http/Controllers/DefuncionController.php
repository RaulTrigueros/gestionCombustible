<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\BitacoraService;
use App\Defuncion;


class DefuncionController extends Controller
{
    protected $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $defuncion = Defuncion::orderBy('defuncion.id', 'desc')->paginate(3);
        }
        else{
            $defuncion = DB::table('defuncion')->where('defuncion.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('defuncion.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total'        => $defuncion->total(),
                'current_page' => $defuncion->currentPage(),
                'per_page'     => $defuncion->perPage(),
                'last_page'    => $defuncion->lastPage(),
                'from'         => $defuncion->firstItem(),
                'to'           => $defuncion->lastItem(),
            ],
            'defuncion' => $defuncion
        ];

       }
    public function registroDefuncion(Request $request)
    {

       if (!$request->ajax()) return redirect('/');

        //try{
           // DB::beginTransaction();
            $defuncion = new Defuncion();
            $defuncion->paginas = $request->paginas;
            $defuncion->anio = $request->anio;
            $defuncion->numeropartida = $request->numeropartida;
            $defuncion->nombres = $request->nombres;
            $defuncion->sexo = $request->sexo;
            $defuncion->edad = $request->edad;
            $defuncion->estadofam = $request->estadofam;
            $defuncion->oficio = $request->oficio;
            $defuncion->lugarorigen = $request->lugarorigen;
            $defuncion->domicilio = $request->domicilio;
            $defuncion->nacionalidad = $request->nacionalidad;
            $defuncion->nombrepadre = $request->nombrepadre;
            $defuncion->nombremadre = $request->nombremadre;
            $defuncion->hora = $request->hora;
            $defuncion->fechamuerte = $request->fechamuerte;
            $defuncion->lugarmuerte = $request->lugarmuerte;
            $defuncion->causa = $request->causa;
            $defuncion->duifallecido = $request->duifallecido;
            $defuncion->nombreregistrador = $request->nombreregistrador;
            $defuncion->parentesco = $request->parentesco;
            $defuncion->duiregistrador = $request->duiregistrador;
            $defuncion->nombretestigo = $request->nombretestigo;
            $defuncion->duitestigo = $request->duitestigo;
            $defuncion->fechapartida = $request->fechapartida;
           // $defuncion->fechaexpe = $request->fechaexpe;
           // $defuncion->save();
            //DB::commit();

       // } catch (Exception $e){
         //   DB::rollBack();
        //}
             try {
                $defuncion->save();
                 $this->bitacoraService->store('Creación de registro', 'Defuncion');
                return "Exito";
            } catch (\Throwable $th) {
                return $th;
            }


    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
            $defuncion = Defuncion::findOrFail($request->id);
            $defuncion->paginas = $request->paginas;
            $defuncion->anio = $request->anio;
            $defuncion->numeropartida = $request->numeropartida;
            $defuncion->nombres = $request->nombres;
            $defuncion->sexo = $request->sexo;
            $defuncion->edad = $request->edad;
            $defuncion->estadofam = $request->estadofam;
            $defuncion->oficio = $request->oficio;
            $defuncion->lugarorigen = $request->lugarorigen;
            $defuncion->domicilio = $request->domicilio;
            $defuncion->nacionalidad = $request->nacionalidad;
            $defuncion->nombrepadre = $request->nombrepadre;
            $defuncion->nombremadre = $request->nombremadre;
            $defuncion->hora = $request->hora;
            $defuncion->fechamuerte = $request->fechamuerte;
            $defuncion->lugarmuerte = $request->lugarmuerte;
            $defuncion->causa = $request->causa;
            $defuncion->duifallecido = $request->duifallecido;
            $defuncion->nombreregistrador = $request->nombreregistrador;
            $defuncion->parentesco = $request->parentesco;
            $defuncion->duiregistrador = $request->duiregistrador;
            $defuncion->nombretestigo = $request->nombretestigo;
            $defuncion->duitestigo = $request->duitestigo;
            $defuncion->fechapartida = $request->fechapartida;
            //$defuncion->fechaexpe = $request->fechaexpe;

        try {
            $defuncion->save();
            $this->bitacoraService->store('Actualización de registro', 'Defuncion');
            return "exito";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function defuncionPDF(Request $request,$id){

      $defuncion = Defuncion::where('defuncion.id',$id)->get();


        $pdf= \PDF::loadView('pdf.defuncion',['defuncion'=>$defuncion]);

        return $pdf->stream();
    }
}

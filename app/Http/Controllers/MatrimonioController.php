<?php

namespace App\Http\Controllers;

use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Matrimonio;

class MatrimonioController extends Controller
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
            $matrimonio = Matrimonio::orderBy('matrimonio.id','desc')->paginate(3);
        }else{
            $matrimonio = DB::table('matrimonio')->where('matrimonio.'.$criterio,'like','%'. $buscar .'%')->orderBy('id','desc')->paginate(3);
        }

         return [
            'pagination'=> [
                'total' => $matrimonio->total(),
                'current_page' => $matrimonio->currentPage(),
                'per_page' => $matrimonio->perPage(),
                'last_page' => $matrimonio->lastPage(),
                'from' => $matrimonio->firstItem(),
                'to' => $matrimonio->lastItem(),
            ],
            'matrimonio' => $matrimonio
        ];
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $matrimonio = new Matrimonio();
        $matrimonio->pagina=$request->pagina;
        $matrimonio->numeropartida = $request->numeropartida;
        $matrimonio->anio=$request->anio;
        $matrimonio->fechaPartida=$request->fechaPartida;

        $matrimonio->nombreEsposo=$request->nombreEsposo;
        $matrimonio->apellido1Esposo = $request->apellido1Esposo;
        $matrimonio->apellido2Esposo=$request->apellido2Esposo;
        $matrimonio->edadEsposo=$request->edadEsposo;
        $matrimonio->ocupacionEsposo=$request->ocupacionEsposo;
        $matrimonio->estadoCivilEsposo = $request->estadoCivilEsposo;
        $matrimonio->duiEsposo=$request->duiEsposo;
        $matrimonio->nacionalidadEsposo=$request->nacionalidadEsposo;
        $matrimonio->nombrePadreEsposo=$request->nombrePadreEsposo;
        $matrimonio->apellidoPadreEsposo = $request->apellidoPadreEsposo;
        $matrimonio->nombreMadreEsposo=$request->nombreMadreEsposo;
        $matrimonio->apellidoMadreEsposo=$request->apellidoMadreEsposo;

        $matrimonio->nombreEsposa=$request->nombreEsposa;
        $matrimonio->apellido1Esposa = $request->apellido1Esposa;
        $matrimonio->apellido2Esposa=$request->apellido2Esposa;
        $matrimonio->edadEsposa=$request->edadEsposa;
        $matrimonio->ocupacionEsposa=$request->ocupacionEsposa;
        $matrimonio->estadoCivilEsposa = $request->estadoCivilEsposa;
        $matrimonio->duiEsposa=$request->duiEsposa;
        $matrimonio->nacionalidadEsposa=$request->nacionalidadEsposa;
        $matrimonio->nombrePadreEsposa=$request->nombrePadreEsposa;
        $matrimonio->apellidoPadreEsposa = $request->apellidoPadreEsposa;
        $matrimonio->nombreMadreEsposa=$request->nombreMadreEsposa;
        $matrimonio->apellidoMadreEsposa=$request->apellidoMadreEsposa;

        $matrimonio->apellidoCasada=$request->apellidoCasada;
        $matrimonio->regimen=$request->regimen;

        $matrimonio->nombreTestigo1 = $request->nombreTestigo1;
        $matrimonio->apellidoTestigo1=$request->apellidoTestigo1;
        $matrimonio->duiTestigo1=$request->duiTestigo1;

        $matrimonio->nombreTestigo2=$request->nombreTestigo2;
        $matrimonio->apellidoTestigo2 = $request->apellidoTestigo2;
        $matrimonio->duiTestigo2=$request->duiTestigo2;


        try {
            $matrimonio->save();
            $this->bitacoraService->store('Creación de registro', 'Matrimonio');
            return "Exito";
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function update(Request $request)
    {
    if (!$request->ajax()) return redirect('/');
        $matrimonio = Matrimonio::findOrFail($request->id);

        $matrimonio->pagina=$request->pagina;
        $matrimonio->numeropartida = $request->numeropartida;
        $matrimonio->anio=$request->anio;
        $matrimonio->fechaPartida=$request->fechaPartida;

        $matrimonio->nombreEsposo=$request->nombreEsposo;
        $matrimonio->apellido1Esposo = $request->apellido1Esposo;
        $matrimonio->apellido2Esposo=$request->apellido2Esposo;
        $matrimonio->edadEsposo=$request->edadEsposo;
        $matrimonio->ocupacionEsposo=$request->ocupacionEsposo;
        $matrimonio->estadoCivilEsposo = $request->estadoCivilEsposo;
        $matrimonio->duiEsposo=$request->duiEsposo;
        $matrimonio->nacionalidadEsposo=$request->nacionalidadEsposo;
        $matrimonio->nombrePadreEsposo=$request->nombrePadreEsposo;
        $matrimonio->apellidoPadreEsposo = $request->apellidoPadreEsposo;
        $matrimonio->nombreMadreEsposo=$request->nombreMadreEsposo;
        $matrimonio->apellidoMadreEsposo=$request->apellidoMadreEsposo;

        $matrimonio->nombreEsposa=$request->nombreEsposa;
        $matrimonio->apellido1Esposa = $request->apellido1Esposa;
        $matrimonio->apellido2Esposa=$request->apellido2Esposa;
        $matrimonio->edadEsposa=$request->edadEsposa;
        $matrimonio->ocupacionEsposa=$request->ocupacionEsposa;
        $matrimonio->estadoCivilEsposa = $request->estadoCivilEsposa;
        $matrimonio->duiEsposa=$request->duiEsposa;
        $matrimonio->nacionalidadEsposa=$request->nacionalidadEsposa;
        $matrimonio->nombrePadreEsposa=$request->nombrePadreEsposa;
        $matrimonio->apellidoPadreEsposa = $request->apellidoPadreEsposa;
        $matrimonio->nombreMadreEsposa=$request->nombreMadreEsposa;
        $matrimonio->apellidoMadreEsposa=$request->apellidoMadreEsposa;

        $matrimonio->apellidoCasada= $request->apellidoCasada;
        $matrimonio->regimen=$request->regimen;

        $matrimonio->nombreTestigo1 = $request->nombreTestigo1;
        $matrimonio->apellidoTestigo1=$request->apellidoTestigo1;
        $matrimonio->duiTestigo1=$request->duiTestigo1;

        $matrimonio->nombreTestigo2=$request->nombreTestigo2;
        $matrimonio->apellidoTestigo2 = $request->apellidoTestigo2;
        $matrimonio->duiTestigo2=$request->duiTestigo2;

        try {
            $matrimonio->save();
            $this->bitacoraService->store('Actualización de registro', 'Matrimonio');
            return "exito";
        } catch (\Throwable $th) {
            return $th;
        }
    }


    public function matrimonioPDF(Request $request,$id){

        $matrimonio = Matrimonio::where('matrimonio.id',$id)->get();

          $pdf= \PDF::loadView('pdf.matrimonio',['matrimonio'=>$matrimonio]);

          return $pdf->stream();
      }

    public function destroy($id)
    {
        //
    }
}

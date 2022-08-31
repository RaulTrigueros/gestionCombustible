<?php

namespace App\Http\Controllers;

use App\Services\BitacoraService;
use Illuminate\Http\Request;
use App\Nacimiento;
use App\Ciudadano;

class PartidaController extends Controller
{
    protected $bitacoraService;

    public function __construct(BitacoraService $bitacoraService)
    {
        $this->bitacoraService = $bitacoraService;
    }
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
//         $anuncios = Anuncio::all();
//        return $anuncios;

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){

          /*  $nacimientos=DB::table('nacimiento')
         //   ->join('tipoprograma','nacimiento.idpadre','=','tipoprogramas.id')
            ->join('ciudadano','nacimiento.idpadre','=','ciudadano.id')
            ->select('nacimiento.id','nacimiento.idpadre','nacimiento.paginas','nacimiento.numeropartida','nacimiento.anio',
            'nacimiento.nombres',
            'nacimiento.sexo','nacimiento.hora','nacimiento.fecha',
            'nacimiento.lugarnacimiento','nacimiento.descripcion','ciudadano.nombres as nombres_ciudadano',
            'ciudadano.apellidos as apellidos_ciudadano','ciudadano.oficio as oficio','ciudadano.edad as edad','ciudadano.lugarorigen as origen')->orderBy('nacimiento.id', 'desc')->paginate(3);

            */



       //     $nacimientos = Nacimiento::join('tipoprograma','nacimiento.idpadre','=','tipoprogramas.id')
         //   ->select('tipoprogramas.nombre as nombre_tipoprograma')
          //  ->orderBy('nacimiento.id', 'desc')->paginate(3);

    /*     $nacimientos = Nacimiento::join('ciudadano','nacimiento.idpadre','=','ciudadano.id')
         ->join('tipoprogramas','nacimiento.idpadre','=','tipoprogramas.id')
            ->select('nacimiento.id','nacimiento.idpadre','nacimiento.paginas','nacimiento.numeropartida','nacimiento.anio',
            'nacimiento.nombres',
            'nacimiento.sexo','nacimiento.hora','nacimiento.fecha',
            'nacimiento.lugarnacimiento','nacimiento.descripcion','ciudadano.nombres as nombres_ciudadano',
            'ciudadano.apellidos as apellidos_ciudadano','ciudadano.oficio as oficio','ciudadano.edad as edad','ciudadano.lugarorigen as origen'
            ,'tipoprogramas.nombre as nombre_tipoprograma')
            ->orderBy('nacimiento.id', 'desc')->paginate(3);
            */
           $nacimientos = Nacimiento::join('ciudadano','nacimiento.idpadre','=','ciudadano.id')
          ->join('ciudadano as madre','nacimiento.idmadre','=','madre.id')
            ->select('nacimiento.*','ciudadano.nombres as nombres_padre',
            'ciudadano.apellidos as apellidos_padre','ciudadano.oficio as oficio_padre','ciudadano.edad as edad_padre','ciudadano.lugarorigen as lugarorigen_padre','ciudadano.dui as dui_padre'
            ,'madre.nombres as nombres_madre',
            'madre.apellidos as apellidos_madre','madre.oficio as oficio_madre','madre.edad as edad_madre','madre.lugarorigen as lugarorigen_madre', 'madre.dui as dui_madre')->orderBy('nacimiento.id', 'desc')->paginate(3);





//            $anuncios = DB::table('anuncios')
//           ->select('anuncios.id','anuncios.titulo','anuncios.autor','anuncios.descripcion','anuncios.fecha_anuncio','anuncios.estado','anuncios.imagen')
//            ->orderBy('anuncios.id', 'desc')->paginate(3);

        }
        elseif($criterio=='nombres_ciudadano'){

            $nacimientos = Nacimiento::join('ciudadano','nacimiento.idpadre','=','ciudadano.id')
            ->join('ciudadano as madre','nacimiento.idmadre','=','madre.id')
              ->select('nacimiento.id','nacimiento.idpadre','nacimiento.paginas','nacimiento.numeropartida','nacimiento.anio',
              'nacimiento.nombres',
              'nacimiento.sexo','nacimiento.hora','nacimiento.fecha',
              'nacimiento.lugarnacimiento','nacimiento.descripcion','ciudadano.nombres as nombres_padre',
              'ciudadano.apellidos as apellidos_padre','ciudadano.oficio as oficio_padre','ciudadano.edad as edad_padre','ciudadano.lugarorigen as origen_padre'
              ,'madre.nombres as nombres_madre',
              'madre.apellidos as apellidos_madre','madre.oficio as oficio_madre','madre.edad as edad_madre','madre.lugarorigen as origen_madre'
        )->where('ciudadano.nombres','like','%'. $buscar .'%')->orderBy('nacimiento.id', 'desc')->paginate(3);


        }
        elseif($criterio=='nombres_madre'){

            $nacimientos = Nacimiento::join('ciudadano','nacimiento.idpadre','=','ciudadano.id')
            ->join('ciudadano as madre','nacimiento.idmadre','=','madre.id')
              ->select('nacimiento.id','nacimiento.idpadre','nacimiento.paginas','nacimiento.numeropartida','nacimiento.anio',
              'nacimiento.nombres',
              'nacimiento.sexo','nacimiento.hora','nacimiento.fecha',
              'nacimiento.lugarnacimiento','nacimiento.descripcion','ciudadano.nombres as nombres_padre',
              'ciudadano.apellidos as apellidos_padre','ciudadano.oficio as oficio_padre','ciudadano.edad as edad_padre','ciudadano.lugarorigen as origen_padre'
              ,'madre.nombres as nombres_madre',
              'madre.apellidos as apellidos_madre','madre.oficio as oficio_madre','madre.edad as edad_madre','madre.lugarorigen as origen_madre'
        )->where('madre.nombres','like','%'. $buscar .'%')->orderBy('nacimiento.id', 'desc')->paginate(3);


        }
        else{
            $nacimientos = Nacimiento::join('ciudadano','nacimiento.idpadre','=','ciudadano.id')
            ->join('ciudadano as madre','nacimiento.idmadre','=','madre.id')
              ->select('nacimiento.id','nacimiento.idpadre','nacimiento.paginas','nacimiento.numeropartida','nacimiento.anio',
              'nacimiento.nombres',
              'nacimiento.sexo','nacimiento.hora','nacimiento.fecha',
              'nacimiento.lugarnacimiento','nacimiento.descripcion','ciudadano.nombres as nombres_padre',
              'ciudadano.apellidos as apellidos_padre','ciudadano.oficio as oficio_padre','ciudadano.edad as edad_padre','ciudadano.lugarorigen as origen_padre'
              ,'madre.nombres as nombres_madre',
              'madre.apellidos as apellidos_madre','madre.oficio as oficio_madre','madre.edad as edad_madre','madre.lugarorigen as origen_madre'
        )->where('nacimiento.'.$criterio,'like','%'. $buscar .'%')->orderBy('nacimiento.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total'        => $nacimientos->total(),
                'current_page' => $nacimientos->currentPage(),
                'per_page'     => $nacimientos->perPage(),
                'last_page'    => $nacimientos->lastPage(),
                'to'           => $nacimientos->lastItem(),
                'from'         => $nacimientos->firstItem(),
            ],
            'nacimientos' => $nacimientos
        ];
    }
    public function registroPartida(Request $request)
    {

      //  return 'ingresando ';
       // if (!$request->ajax()) return redirect('/');


           // DB::beginTransaction();
           $padre= new Ciudadano();
           $padre->nombres=$request->nombres_padre;
           $padre->apellidos=$request->apellidos_padre;
           $padre->edad=$request->edad_padre;
           $padre->oficio=$request->oficio_padre;
           $padre->lugarorigen=$request->lugarorigen_padre;
           $padre->dui=$request->dui_padre;
           $padre->save();



        $madre= new Ciudadano();
        $madre->nombres=$request->nombres_madre;
        $madre->apellidos=$request->apellidos_madre;
        $madre->edad=$request->edad_madre;
        $madre->oficio=$request->oficio_madre;
        $madre->lugarorigen=$request->lugarorigen_madre;
        $madre->dui=$request->dui_madre;
        $madre->save();



            $nacimiento = new Nacimiento();
            $nacimiento->paginas = $request->paginas;
            $nacimiento->numeropartida = $request->numeropartida;
            $nacimiento->anio = $request->anio;
            $nacimiento->nombres = $request->nombres;
            $nacimiento->sexo = $request->sexo;
            $nacimiento->hora = $request->hora;
            $nacimiento->fecha = $request->fecha;
            $nacimiento->lugarnacimiento=$request->lugarnacimiento;
            $nacimiento->descripcion=$request->descripcion;
            $nacimiento->nombres_tutor=$request->nombres_tutor;
            $nacimiento->apellidos_tutor=$request->apellidos_tutor;
            $nacimiento->manifiesto_tutor=$request->manifiesto_tutor;
            $nacimiento->dui_tutor=$request->dui_tutor;
            $nacimiento->expedido_tutor=$request->expedido_tutor;
            $nacimiento->fecha_expedicion=$request->fecha_expedicion;
            $nacimiento->idpadre=$padre->id;
            $nacimiento->idmadre=$madre->id;


           // DB::commit();
           try {
            $nacimiento->save();
            $this->bitacoraService->store('Creación de registro', 'Nacimiento');
            return "exito";
        } catch (\Throwable $th) {
            return $th;
        }



    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


  // DB::beginTransaction();

        $nacimiento = Nacimiento::findOrFail($request->id);
        $nacimiento->paginas = $request->paginas;
        $nacimiento->numeropartida = $request->numeropartida;
        $nacimiento->anio = $request->anio;
        $nacimiento->nombres = $request->nombres;
        $nacimiento->sexo = $request->sexo;
        $nacimiento->hora = $request->hora;
        $nacimiento->fecha = $request->fecha;
        $nacimiento->lugarnacimiento = $request->lugarnacimiento;
        $nacimiento->descripcion=$request->descripcion;
            $nacimiento->nombres_tutor=$request->nombres_tutor;
            $nacimiento->apellidos_tutor=$request->apellidos_tutor;
            $nacimiento->manifiesto_tutor=$request->manifiesto_tutor;
            $nacimiento->dui_tutor=$request->dui_tutor;
            $nacimiento->expedido_tutor=$request->expedido_tutor;
            $nacimiento->fecha_expedicion=date('d/m/YYYY');

           $padre=Ciudadano::findOrFail($nacimiento->idpadre) ;
           $padre->nombres=$request->nombres_padre;
           $padre->apellidos=$request->apellidos_padre;
           $padre->edad=$request->edad_padre;
           $padre->oficio=$request->oficio_padre;
           $padre->lugarorigen=$request->lugarorigen_padre;
           $padre->dui=$request->dui_padre;
           $padre->save();

           $madre=Ciudadano::findOrFail($nacimiento->idmadre) ;
           $madre->nombres=$request->nombres_madre;
           $madre->apellidos=$request->apellidos_madre;
           $madre->edad=$request->edad_madre;
           $madre->oficio=$request->oficio_madre;
           $madre->lugarorigen=$request->lugarorigen_madre;
           $madre->dui=$request->dui_madre;
           $madre->save();



        try {
            $nacimiento->save();
            $this->bitacoraService->store('Actualización de registro', 'Nacimiento');
           
            return "exito";
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function nacimientoPDF(Request $request,$id){

      $nacimientos = Nacimiento::join('ciudadano','nacimiento.idpadre','=','ciudadano.id')
          ->join('ciudadano as madre','nacimiento.idmadre','=','madre.id')
            ->select('nacimiento.*','ciudadano.nombres as nombres_padre',
            'ciudadano.apellidos as apellidos_padre','ciudadano.oficio as oficio_padre','ciudadano.edad as edad_padre','ciudadano.lugarorigen as origen_padre'
            ,'ciudadano.dui as dui_padre'
            ,'madre.nombres as nombres_madre',
            'madre.apellidos as apellidos_madre','madre.oficio as oficio_madre','madre.edad as edad_madre','madre.lugarorigen as origen_madre','madre.dui as dui_madre'
      )->where('nacimiento.id', $id)->get();


        $pdf= \PDF::loadView('pdf.nacimiento',['nacimientos'=>$nacimientos]);

        return $pdf->stream();
    }
}

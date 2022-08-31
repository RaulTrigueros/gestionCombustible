<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BitacoraService;
use Illuminate\Support\Facades\DB;
use App\Organigrama;


class OrganigramaController extends Controller
{
   public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $organigramas = Organigrama::orderBy('organigramas.id', 'desc')->paginate(3);
//            $anuncios = DB::table('anuncios')
//           ->select('anuncios.id','anuncios.titulo','anuncios.autor','anuncios.descripcion','anuncios.fecha_anuncio','anuncios.estado','anuncios.imagen')
//            ->orderBy('anuncios.id', 'desc')->paginate(3);
        }
        else{
            $organigramas = DB::table('organigramas')
            ->select('organigramas.descripcion','organigramas.imagen')
            ->where('organigramas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('organigramas.id', 'desc')->paginate(3);
        }


        return [
            'pagination' => [
                'total'        => $organigramas->total(),
                'current_page' => $organigramas->currentPage(),
                'per_page'     => $organigramas->perPage(),
                'last_page'    => $organigramas->lastPage(),
                'from'         => $organigramas->firstItem(),
                'to'           => $organigramas->lastItem(),
            ],
            'organigramas' => $organigramas
        ];
    }

    public function registroOrganigrama(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            $organigramas = new Organigrama();
            $organigramas->descripcion = $request->descripcion;
            $organigramas->imagen = $request->imagen;
            $organigramas->estado = '1';
            $organigramas->save();
            //$this->bitacoraService->store('Creación de registro', 'Anuncios');
            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
        }



    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $organigramas = Organigrama::findOrFail($request->id);
        $organigramas->descripcion = $request->descripcion;
        $organigramas->imagen = $request->imagen;
        $organigramas->estado = '1';
        $organigramas->save();
       // $this->bitacoraService->store('Actualización de registro', 'Anuncios');
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $organigramas = Organigrama::findOrFail($request->id);
        $organigramas->estado = '0';
        $organigramas->save();
       // $this->bitacoraService->store('Actualización de registro', 'Anuncios');
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $organigramas = Organigrama::findOrFail($request->id);
        $organigramas->estado = '1';
        $organigramas->save();
        //$this->bitacoraService->store('Actualización de registro', 'Anuncios');
    }

    public function getOrganigramasActivos(Request $request){
        $organigramas = DB::table('organigramas')->Where('estado', 1)->orderBy('organigramas.id', 'desc')->get();
        return $organigramas;
    }
}

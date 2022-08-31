<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\ManualOrganizacion;

class ManualOrganizacionController extends Controller
{
    public function manuales(Request $request){ 
        $manuales = ManualOrganizacion::all();
        return $manuales;
    }
    
    public function getManual($id){
        $manual = ManualOrganizacion::findOrFail($id);
        return $manual;
    }
    
    public function deleteManual($id){
        $manual = ManualOrganizacion::findOrFail($id);
        $manual->delete();
    }
    
    public function updateManual(Request $request, $id){
        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);
        if ($validar->fails()) {
            return response()->json($validar, 400);
        }else{
            $manualUpdate = ManualOrganizacion::where('id', $id)->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
            ]);
            return $manualUpdate;
        }
    }
    
    public function createManual(Request $request){
        $manual = new ManualOrganizacion();
        
        $validar = Validator::make($request->all(), [
            'nombre' => 'required',
            'descripcion' => 'required',
            'archivo' => 'required',
        ]);
        if ($validar->fails()) {
            return response()->json($validar->errors(), 400);
        }else{
            $manual->nombre = $request->nombre;
            $manual->descripcion = $request->descripcion;
            $manual->archivo = $request->archivo;
            
            $manual->save();
            return $manual;
        }
    }
    
    public function buscar(Request $request){
        try{
            $buscar = $request->buscar;
            $criterio = $request->criterio;
            
            if($buscar == ''){
                $manuales = DB::table('manual_organizacion')
                ->select(
                    'manual_organizacion.id',
                    'manual_organizacion.nombre',
                    'manual_organizacion.descripcion',
                    'manual_organizacion.archivo',
                    )
                    ->orderBy('manual_organizacion.id', 'desc')->paginate(5);
            }else{
                $manuales =  DB::table('manual_organizacion')
                ->select(
                    'manual_organizacion.id',
                    'manual_organizacion.nombre',
                    'manual_organizacion.descripcion',
                    'manual_organizacion.archivo',
                    )
                    ->where('manual_organizacion.' . $criterio, 'like', '%' . $buscar . '%')
                    ->orderBy('manual_organizacion.id', 'desc')->paginate(5);
            }
//             return $manuales;
            
            return [
                'pagination' => [
                    'total' => $manuales->total(),
                    'current_page' => $manuales->currentPage(),
                    'per_page' => $manuales->perPage(),
                    'last_page' => $manuales->lastPage(),
                    'from' => $manuales->firstItem(),
                    'to' => $manuales->lastItem(),
                ],
                'manual_organizacion' => $manuales,
            ];
        }
        catch (\Throwable $th){
            return $th;
        }
    }
}

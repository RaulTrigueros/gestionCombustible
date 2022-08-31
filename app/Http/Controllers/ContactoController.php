<?php

namespace App\Http\Controllers;

use App\Contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{

    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $contactos = Contacto::select('id','nombre','email','telefono')
            ->orderBy('nombre','desc')->paginate(5);
        }else{
            $contactos = Contacto::select('id','nombre','email','telefono')
                ->where($criterio,'like','%'. $buscar .'%')
                ->orderBy('nombre','desc')->paginate(5);
        }

        return [
            'pagination'=> [
                'total' => $contactos->total(),
                'current_page' => $contactos->currentPage(),
                'per_page' => $contactos->perPage(),
                'last_page' => $contactos->lastPage(),
                'from' => $contactos->firstItem(),
                'to' => $contactos->lastItem(),
            ],
            'contactos' => $contactos
        ];
    }

    public function selectContacto(Request $request)
    {
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $contactos = Contacto::select('id','nombre','email','telefono')
            ->orderBy('nombre','desc')->paginate(5);
        }else{
            $contactos = Contacto::select('id','nombre','email','telefono')
                ->where($criterio,'like','%'. $buscar .'%')
                ->orderBy('nombre','desc')->paginate(5);
        }

        return [
            'pagination'=> [
                'total' => $contactos->total(),
                'current_page' => $contactos->currentPage(),
                'per_page' => $contactos->perPage(),
                'last_page' => $contactos->lastPage(),
                'from' => $contactos->firstItem(),
                'to' => $contactos->lastItem(),
            ],
            'contactos' => $contactos
        ];

    }

    public function store(Request $request)
    {
        try {
            $contacto = new Contacto();
            $contacto->nombre = $request->nombre;
            $contacto->email = $request->email;
            $contacto->telefono = $request->telefono;
            $contacto->save();

        }catch (\Throwable $th){
            return $th;
        }
    }

    public function update(Request $request)
    {
        try{
            $contacto = Contacto::findOrFail($request->id);
            $contacto->nombre = $request->nombre;
            $contacto->email = $request->email;
            $contacto->telefono = $request->telefono;
            $contacto->save();

        }catch (\Throwable $th){
            return $th;
        }
    }

    public function destroy(Request $request)
    {
        try{
            $contacto = Contacto::findOrFail($request->id);
            $contacto->delete();

        }catch (\Throwable $th){
            return $th;
        }

    }

}

<?php

namespace App\Http\Controllers;

use App\Pregunta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $preguntas = Pregunta::select('id','pregunta','respuesta')
            ->orderBy('id','desc')->paginate(5);
        }else{
            $preguntas = Pregunta::select('id','pregunta','respuesta')
                ->where($criterio,'like','%'. $buscar .'%')
                ->orderBy('id','desc')->paginate(5);
        }

        return [
            'pagination'=> [
                'total' => $preguntas->total(),
                'current_page' => $preguntas->currentPage(),
                'per_page' => $preguntas->perPage(),
                'last_page' => $preguntas->lastPage(),
                'from' => $preguntas->firstItem(),
                'to' => $preguntas->lastItem(),
            ],
            'preguntas' => $preguntas
        ];
    }

    public function selectPregunta(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $preguntas = Pregunta::select('id','pregunta','respuesta')
        ->orderBy('pregunta','asc')->get();
       
        return ['preguntas' => $preguntas];
    }

    public function store(Request $request)
    {
        try {
            if(!$request->ajax()) return redirect('/');

            $pregunta = new Pregunta();
            $pregunta->pregunta = $request->pregunta;
            $pregunta->respuesta = $request->respuesta;
            $pregunta->save();

        }catch (\Throwable $th){
            return $th;
        }
    }

    public function update(Request $request)
    {
        try{
            if(!$request->ajax()) return redirect('/');

            $pregunta = Pregunta::findOrFail($request->id);
            $pregunta->pregunta = $request->pregunta;
            $pregunta->respuesta = $request->respuesta;
            $pregunta->save();

        }catch (\Throwable $th){
            return $th;
        }
    }

    public function destroy(Request $request)
    {
        try{
            if(!$request->ajax()) return redirect('/');
            
            $pregunta = Pregunta::findOrFail($request->id);
            $pregunta->delete();

        }catch (\Throwable $th){
            return $th;
        }

    }

}

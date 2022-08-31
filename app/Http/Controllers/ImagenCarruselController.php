<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ImagenCarrusel;
use Illuminate\Http\Response;

class ImagenCarruselController extends Controller {

    public function getImagenes($id_carrusel) {
        $imagenes = ImagenCarrusel::where('id_carrusel', $id_carrusel)
                ->orderBy('orden')
                ->get();
        foreach ($imagenes as $imagen) {
            $imagen['estado'] = false;
        }
        return $imagenes;
    }

    public function getImagen($id_imagen) {
        $imagen = ImagenCarrusel::where('id', $id_imagen)->get();
        return $imagen;
    }
    
    public function updateOrdenTable(){
        DB::update('update imagen_carrusel ic set orden = ic.orden + 1');
    }
    
    public function updateOrden($id_imagen, $orden){
       $res =  ImagenCarrusel::where('id', $id_imagen)->update(['orden' => $orden]);
       return $res;
    }
    
    public function getImage($nombre_img){
        $existe = \Storage::disk('carrusel')->exists($nombre_img);
        if($existe){
            $file = \Storage::disk('carrusel')->get($nombre_img);
            return new Response($file, 200);
        }else{
            return new Response('no exite img', 400);
        }
    }
    
    public function create(Request $request, $id_carrusel) {
        $imagen = new ImagenCarrusel();
        $imagenCliente = $request->file('imagen');
        $validate = \Validator::make($request->all(), [
                    'imagen' => 'required|image|mimes:jpg,jpeg,gif,png',
        ]);

        if ($imagenCliente && !$validate->fails()) {
            $imagen_name = time() . $imagenCliente->getClientOriginalName();
            //guardando imagen en server
            \Storage::disk('carrusel')->put($imagen_name, \File::get($imagenCliente));
        } else {
            $imagen_name = '';
        }

        try {
            $this->updateOrdenTable();
            
            $imagen->id_carrusel = $id_carrusel;
            $imagen->titulo = $request->titulo;
            $imagen->descripcion = $request->descripcion;
            $imagen->imagen = $imagen_name;
            $imagen->orden = $request->orden;

            $imagen->save();
            return $imagen;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function delete($id_imagen) {
        $img = ImagenCarrusel::findOrFail($id_imagen);
        $img_name = $img->imagen;
        $img->delete();
        // $res = ImagenCarrusel::where('id', $id_imagen)->delete();
        \Storage::disk('carrusel')->delete($img_name);
        return 'exito';
    }


    public function update(Request $request, $id_imagen) {
        try {
            $res = ImagenCarrusel::where('id', $id_imagen)->update(['titulo' => $request->titulo, 'descripcion' => $request->descripcion]);
            return 'exito';
        } catch (Exception $e) {
            return 'fracaso';
        }
    }

}

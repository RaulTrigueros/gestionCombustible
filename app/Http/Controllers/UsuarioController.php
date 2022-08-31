<?php
 
namespace App\Http\Controllers;
use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;
use Hash; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate \ Support \ Facades \ Auth;
use App\Services\BitacoraService;
 
class UsuarioController extends Controller
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
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.role_id','=','roles.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','personas.cargo','users.usuario','users.password',
            'users.condicion','users.role_id','roles.name as rol')
            ->orderBy('personas.id', 'desc')->paginate(3);
        }
        else{
            $personas = User::join('personas','users.id','=','personas.id')
            ->join('roles','users.role_id','=','roles.id')
            ->select('personas.id','personas.nombre','personas.tipo_documento',
            'personas.num_documento','personas.direccion','personas.telefono',
            'personas.email','personas.cargo','users.usuario','users.password',
            'users.condicion','users.role_id','roles.name as rol')         
            ->where('personas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('personas.id', 'desc')->paginate(3);
        }
         
 
        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }
 
    public function registroUsuario(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         
        try{
            DB::beginTransaction();
            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->cargo = $request->cargo;
            $persona->save();
 
            $usuario1 = new User();
            $usuario1->usuario = $request->usuario;
            $usuario1->password = bcrypt( $request->password);
            $usuario1->condicion = '1';
            $usuario1->role_id = $request->role_id;          
 
            $usuario1->id = $persona->id;
 
            $usuario1->save();
 
            DB::commit();

            $this->bitacoraService->store('Creación de registro', 'Usuario');
            return "Éxito";
 
        } catch (Exception $e){
            DB::rollBack();
        }
 
         
         
    }
 
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
         
        try{
            DB::beginTransaction();
 
            //Buscar primero el usuario a modificar
            $usuario1 = User::findOrFail($request->id);
 
            $persona = Persona::findOrFail($usuario1->id);
 
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;
            $persona->cargo = $request->cargo;
            $persona->save();
 
             
            $usuario1->usuario = $request->usuario;
           // $usuario1->password = bcrypt( $request->password);
            $usuario1->condicion = '1';
            $usuario1->role_id = $request->role_id;
            $usuario1->save();
            
            $this->bitacoraService->store('Actualización de registro', 'Usuario');
            return "Éxito";
 
            DB::commit();
 
        } catch (Exception $e){
            DB::rollBack();
        }
 
    }
    //funcion para eliminar usuario
    public function eliminar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $usuario1 = User::findOrFail($request->id);
        $usuario1->delete();

        $this->bitacoraService->store('Eliminación de registro', 'Usuario');
        return "Éxito";
    }
 
    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $usuario1 = User::findOrFail($request->id);
        $usuario1->condicion = '0';
        $usuario1->save();

        $this->bitacoraService->store('Desactivación de registro', 'Usuario');
        return "Éxito";
    }
 
    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $usuario1 = User::findOrFail($request->id);
        $usuario1->condicion = '1';
        $usuario1->save();

        $this->bitacoraService->store('Activación de registro', 'Usuario');
        return "Éxito";
    }
    
    //Modificar contraseña
    public function password(){
        return View('contenido/contrasena');
       
    }
    
    public function updatePassword(Request $request){
        $rules = [
            'mypassword' => 'required',
            'password' => 'required|confirmed',
        ];
        
        $messages = [
            'mypassword.required' => '**El campo es requerido**',
            'password.required' => '**El campo es requerido**',
            'password.confirmed' => '**Los passwords no coinciden**',
//            'password.min' => 'El mínimo permitido son 6 caracteres',
//            'password.max' => 'El máximo permitido son 18 caracteres',
        ];
        
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()){
            return redirect('user/password')->withErrors($validator);
        }
        else{
            if (Hash::check($request->mypassword, Auth::user()->password)){
                $user = new User;
                $user->where('usuario', '=', Auth::user()->usuario)
                     ->update(['password' => bcrypt($request->password)]);
                return redirect('/main')->with('alert-success', 'La contraseña ha sido modificada Exitosamente!'); 
                           
            }
            else
            {
                return redirect('user/password')->with('message', 'Credenciales incorrectas');
            }
        }

        
        
    }
 
 
}

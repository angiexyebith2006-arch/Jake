<?php 
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usuario;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
   
    public function showLoginForm()
    {
        return view('auth.login');
    }

    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
        
            $user = Auth::user();
        
            $usuarioJake = Usuario::where('correo', $user->email)->first();
            
            if ($usuarioJake) {
                
                if (!$usuarioJake->roles()->exists()) {
                   
                    $this->assignDefaultRole($usuarioJake);
                }
                
               
                Session::put('usuario_jake', $usuarioJake);
            }
            
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    
    private function assignDefaultRole(Usuario $usuario)
    {
        $email = strtolower($usuario->correo);
        
  
        if (str_contains($email, 'admin') || str_contains($email, 'administrador')) {
            $role = Role::where('nombre', 'admin')->first();
        } elseif (str_contains($email, 'lider') || str_contains($email, 'coordinador')) {
            $role = Role::where('nombre', 'lider')->first();
        } elseif (str_contains($email, 'tesorero') || str_contains($email, 'finanza')) {
            $role = Role::where('nombre', 'tesorero')->first();
        } else {
            $role = Role::where('nombre', 'usuario')->first();
        }
        
        if ($role) {
            $usuario->roles()->attach($role->id);
        }
    }


    public function logout()
    {
        Session::forget('usuario_jake');
        Auth::logout();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}
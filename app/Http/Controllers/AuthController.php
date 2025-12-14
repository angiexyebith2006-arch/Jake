<?php // app/Http/Controllers/AuthController.php
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
    // Mostrar formulario
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar inicio de sesión
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Obtener usuario Laravel
            $user = Auth::user();
            
            // Obtener usuario del sistema Jake
            $usuarioJake = Usuario::where('correo', $user->email)->first();
            
            if ($usuarioJake) {
                // Verificar si tiene roles asignados
                if (!$usuarioJake->roles()->exists()) {
                    // Asignar rol automático basado en el email
                    $this->assignDefaultRole($usuarioJake);
                }
                
                // Guardar información del usuario Jake en sesión
                Session::put('usuario_jake', $usuarioJake);
            }
            
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    /**
     * Asignar rol por defecto basado en email
     */
    private function assignDefaultRole(Usuario $usuario)
    {
        $email = strtolower($usuario->correo);
        
        // Buscar rol apropiado
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

    // Cerrar sesión
    public function logout()
    {
        Session::forget('usuario_jake');
        Auth::logout();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}
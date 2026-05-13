<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Validation\Rules;

class UsuariosController extends Controller
{
    protected string $apiUrl = 'http://127.0.0.1:5431'; 

    
    protected function checkAuth()
    {
        if (!Session::has('usuario_api')) {
            return redirect()->route('login')->with('error', 'Por favor, inicie sesión para continuar.');
        }
        return null;
    }

    
    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    
    public function index()
    {
     
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
          
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrl . '/usuarios');

            if (!$response->successful()) {
                \Log::error('Error al obtener usuarios', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return back()->withErrors('Error al obtener usuarios: ' . $response->status());
            }

            $data = $response->json();
            
            
            $usuarios = collect($data['data'] ?? $data ?? [])->map(function ($item) {
                return (object) [
                    'id_usuario' => $item['idUsuario'] ?? $item['id_usuario'] ?? null,
                    'nombre'     => $item['nombre'] ?? '',
                    'correo'     => $item['correo'] ?? '',
                    'telefono'   => $item['telefono'] ?? '',
                    'activo'     => $item['activo'] ?? false,
                ];
            });

            return view('perfil.index', compact('usuarios'));
            
        } catch (\Exception $e) {
            \Log::error('Excepción en index de usuarios', [
                'error' => $e->getMessage()
            ]);
            return back()->withErrors('Error al conectar con el servidor: ' . $e->getMessage());
        }
    }

  
    public function show($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
          
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrl . '/usuarios/' . $id);

            if ($response->status() === 404) {
                abort(404, 'Usuario no encontrado');
            }

            if (!$response->successful()) {
                return back()->withErrors('Error al obtener el usuario');
            }

            return view('perfil.show', [
                'usuario' => $response->json()
            ]);
            
        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

 
    public function create()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $usuarios = (object)[
            'nombre' => '',
            'correo' => '',
            'telefono' => '',
            'activo' => false,
        ];

        return view('perfil.create', compact('usuarios'));
    }


    public function store(Request $request)
{
    $request->validate([
        'nombre'   => ['required', 'string', 'max:100'],
        'correo'   => ['required', 'string', 'email', 'max:100'],
        'telefono' => ['nullable', 'regex:/^\d{7,15}$/'], // ← Valida solo dígitos
        'activo'   => ['boolean'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    try {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->apiUrl . '/usuarios', [
                'nombre'   => $request->nombre,
                'correo'   => $request->correo,
                'telefono' => $request->telefono,
                'activo'   => $request->boolean('activo', true),
                'clave'    => $request->password,
            ]);

        // Manejar error de correo duplicado
        if ($response->status() === 409) {
            return back()->withInput()->withErrors([
                'correo' => 'Ya existe un usuario registrado con ese correo.'
            ]);
        }

        if (!$response->successful()) {
            $erroresJava = $response->json();
            
            // Log para depuración
            \Log::info('Error response from Java', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $erroresJava
            ]);
            
            if ($response->status() === 400 && is_array($erroresJava)) {
                // Mapear nombres de campos si es necesario
                $erroresMapeados = [];
                foreach ($erroresJava as $campo => $mensaje) {
                    if ($campo === 'clave') {
                        $erroresMapeados['password'] = $mensaje;
                    } else {
                        $erroresMapeados[$campo] = $mensaje;
                    }
                }
                return back()->withInput()->withErrors($erroresMapeados);
            }
            
            return back()->withInput()->withErrors([
                'error' => 'No se pudo crear el usuario. Código: ' . $response->status()
            ]);
        }

        return redirect()->route('perfil.index')
            ->with('success', 'Usuario creado correctamente');
            
    } catch (\Exception $e) {
        \Log::error('Error en store: ' . $e->getMessage());
        return back()->withInput()->withErrors([
            'error' => 'Error de conexión: ' . $e->getMessage()
        ]);
    }
}
   
    public function edit($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
         
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrl . '/usuarios/' . $id);
                
            if (!$response->successful()) {
                abort(404, 'Usuario no encontrado');
            }
            
            $usuarioData = $response->json();

            $usuarios = (object) [
                'id_usuario' => $usuarioData['idUsuario'] ?? $usuarioData['id_usuario'] ?? null,
                'nombre'     => $usuarioData['nombre'] ?? '',
                'correo'     => $usuarioData['correo'] ?? '',
                'telefono'   => $usuarioData['telefono'] ?? '',
                'activo'     => $usuarioData['activo'] ?? false,
            ];

            return view('perfil.edit', compact('usuarios'));
            
        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

   
        public function update(Request $request, $id)
{
    $redirect = $this->checkAuth();
    if ($redirect) return $redirect;

    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'correo' => 'required|email',
        'telefono' => 'nullable|string|max:20',
        'activo' => 'nullable|boolean',
        'clave' => 'nullable|string|min:8', // ← Agrega validación de clave
    ]);

    $payload = [
        'nombre'   => $validated['nombre'],
        'correo'   => $validated['correo'],
        'telefono' => $validated['telefono'] ?? null,
        'activo'   => isset($validated['activo']) ? (bool)$validated['activo'] : false,
    ];

    // ← IMPORTANTE: Incluir la clave solo si se proporcionó
    if (!empty($validated['clave'])) {
        $payload['clave'] = $validated['clave'];
    }

    try {
        $response = Http::withHeaders($this->getHeaders())
            ->put($this->apiUrl . '/usuarios/' . $id, $payload);

        if ($response->status() === 404) {
            return back()->withErrors(['error' => 'Usuario no encontrado']);
        }

        // Manejar error de correo duplicado
        if ($response->status() === 409) {
            return back()->withInput()->withErrors([
                'correo' => 'Ya existe un usuario con ese correo electrónico'
            ]);
        }

        if (!$response->successful()) {
            $errorData = $response->json();
            
            // Manejar errores de validación de Java
            if ($response->status() === 400 && is_array($errorData)) {
                return back()->withInput()->withErrors($errorData);
            }
            
            return back()->withInput()->withErrors([
                'error' => 'Error al actualizar el usuario. Código: ' . $response->status()
            ]);
        }

        return redirect()->route('perfil.index')
            ->with('success', 'Usuario actualizado correctamente');

    } catch (\Exception $e) {
        \Log::error('Error en update: ' . $e->getMessage());
        return back()->withInput()->withErrors([
            'error' => 'Error de conexión: ' . $e->getMessage()
        ]);
    }
}

   
    public function destroy($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
           
            $response = Http::withHeaders($this->getHeaders())
                ->delete($this->apiUrl . '/usuarios/' . $id);

            if (!$response->successful()) {
                return back()->withErrors('Error al eliminar usuario');
            }

            return redirect()->route('perfil.index')
                ->with('success', 'Usuario eliminado correctamente');
                
        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre'    => 'required|string|max:255',
            'correo'    => 'required|email|max:255',
            'telefono'  => 'nullable|string|max:20',
            'clave'     => 'required|string|min:6|confirmed',
        ]);

        $payload = [
            'nombre'   => $validated['nombre'],
            'correo'   => $validated['correo'],
            'telefono' => $validated['telefono'] ?? null,
            'activo'   => true,
            'clave'    => $validated['clave'],
        ];

        try {
          
            $response = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->post($this->apiUrl . '/usuarios', $payload);

            if (!$response->successful()) {
                return back()->withInput()->withErrors([
                    'api_error' => "Error al registrar usuario: {$response->status()}"
                ]);
            }

            return redirect()->route('login')
                ->with('success', 'Usuario registrado correctamente. Ya puedes iniciar sesión.');
                
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'api_error' => 'Error al conectar con el servidor: ' . $e->getMessage()
            ]);
        }
    }
}
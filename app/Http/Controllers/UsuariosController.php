<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;

class UsuariosController extends Controller
{
    protected string $apiUrl = 'http://127.0.0.1:5431'; 

    /**
     * Verificar autenticación
     */
    protected function checkAuth()
    {
        if (!Session::has('usuario_api')) {
            return redirect()->route('login')->with('error', 'Por favor, inicie sesión para continuar.');
        }
        return null;
    }

    /**
     * Obtener headers con autenticación
     */
    protected function getHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    // listar usuarios
    public function index()
    {
        // Verificar autenticación
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            // URL CORREGIDA: /usuarios (sin /api)
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
            
            // Mapear usuarios según la estructura de tu API
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

    // mostrar un usuario
    public function show($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            // URL CORREGIDA
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

    // formulario crear
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

    // crear usuario
    public function store(StoreUsuarioRequest $request)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $validated = $request->validated();

        $payload = [
            'nombre'   => $validated['nombre'],
            'correo'   => $validated['correo'],
            'telefono' => $validated['telefono'] ?? null,
            'activo'   => isset($validated['activo']) ? (bool)$validated['activo'] : false,
            'clave'    => $validated['clave'],
        ];

        try {
            // URL CORREGIDA
            $response = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->post($this->apiUrl . '/usuarios', $payload);

            if (!$response->successful()) {
                \Log::error('Error al crear usuario', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return back()->withInput()->withErrors('Error al crear usuario: ' . $response->status());
            }

            return redirect()->route('perfil.index')
                ->with('success', 'Usuario creado correctamente');
                
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error: ' . $e->getMessage());
        }
    }

    // formulario editar
    public function edit($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            // URL CORREGIDA
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

    // actualizar usuario
    public function update(UpdateUsuarioRequest $request, $id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $validated = $request->validated();

        $payload = [
            'nombre'   => $validated['nombre'],
            'correo'   => $validated['correo'],
            'telefono' => $validated['telefono'] ?? null,
            'activo'   => isset($validated['activo']) ? (bool)$validated['activo'] : false,
        ];

        try {
            // URL CORREGIDA
            $response = Http::withHeaders($this->getHeaders())
                ->asJson()
                ->put($this->apiUrl . '/usuarios/' . $id, $payload);

            if (!$response->successful()) {
                return back()->withInput()->withErrors('Error al actualizar usuario');
            }

            return redirect()->route('perfil.index')
                ->with('success', 'Usuario actualizado correctamente');
                
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Error: ' . $e->getMessage());
        }
    }

    // eliminar usuario
    public function destroy($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            // URL CORREGIDA
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

    // registrar nuevo usuario (público)
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
            // URL CORREGIDA
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
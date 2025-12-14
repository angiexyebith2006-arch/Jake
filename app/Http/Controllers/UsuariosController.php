<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Services\JavaApiService;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;

class UsuariosController extends Controller
{
    protected string $apiUrl;

    public function __construct()
    {
         $this->apiUrl = 'http://localhost:5431/api/usuarios';
    }

    // LISTAR
        public function index()
        {
            $response = Http::get($this->apiUrl);

            if (!$response->successful()) {
                return back()->withErrors('Error al obtener usuarios');
            }

            //  ENTRAMOS A "data"
            $usuarios = collect($response->json()['data'] ?? [])->map(function ($item) {
                return (object) [
                    'id_usuario' => $item['idUsuario'] ?? $item['id_usuario'] ?? null,
                    'nombre'     => $item['nombre'] ?? '',
                    'correo'     => $item['correo'] ?? '',
                    'telefono'   => $item['telefono'] ?? '',
                    'activo'     => $item['activo'] ?? false,
                ];
            });

            return view('perfil.index', compact('usuarios'));
        }


    // VER UNO
    public function show($id)
    {
        $response = Http::get("{$this->apiUrl}/{$id}");

        if ($response->status() === 404) {
            abort(404, 'Usuario no encontrado');
        }

        return view('perfil.show', [
            'usuario' => $response->json()
        ]);
    }

        public function create()
        {
            // Solo devuelve la vista del formulario
            return view('perfil.create');
        }

    // CREAR


public function store(StoreUsuarioRequest $request)
{
    // Los datos ya vienen validados por StoreUsuarioRequest
    $validated = $request->validated();

    // Ajustar el formato que espera la API
    $payload = [
        'nombre'   => $validated['nombre'],
        'correo'   => $validated['correo'],
        'telefono' => $validated['telefono'] ?? null,
        'activo'   => isset($validated['activo']) ? (bool)$validated['activo'] : true,
        'clave'    => $validated['clave'],
    ];

    // Enviar a la API
    $response = Http::post($this->apiUrl, $payload);

    // Depuración: mostrar respuesta
    if (!$response->successful()) {
        // Ver la respuesta exacta de la API
        dd($response->status(), $response->body());
    }

    return redirect()->route('perfil.index')->with('success', 'Usuario creado correctamente');
}

public function edit($id)
{
    $response = Http::get("{$this->apiUrl}/{$id}");

    if (!$response->successful()) {
        abort(404, 'Usuario no encontrado');
    }

    // Aquí 'data' es directamente un objeto con el usuario
    $usuarioData = $response->json()['data'];
    $usuarios = (object) $usuarioData;

    return view('perfil.edit', compact('usuarios'));
}


public function update(UpdateUsuarioRequest $request, $id)
{
    // Obtener datos validados
    $validated = $request->validated();

    // Verificar que el usuario exista usando el endpoint por ID
    $responseGet = Http::get("{$this->apiUrl}/{$id}");

    if (!$responseGet->successful()) {
        $data = $responseGet->json();
        $mensaje = $data['message'] ?? 'Usuario no encontrado';
        return back()->withInput()->withErrors($mensaje);
    }

    // Preparar datos a enviar a la API
    $usuarioData = [
        'nombre'   => $validated['nombre'],
        'correo'   => $validated['correo'],
        'telefono' => $validated['telefono'] ?? null,
        'activo'   => isset($validated['activo']) ? (bool)$validated['activo'] : false,
        // 'clave' => $validated['clave'] ?? null, // solo si quieres permitir cambiar
    ];

    // Actualizar usuario
    $responsePut = Http::put("{$this->apiUrl}/{$id}", $usuarioData);

    if (!$responsePut->successful()) {
        return back()->withInput()->withErrors('Error al actualizar usuario');
    }

    return redirect()->route('perfil.index')->with('success', 'Usuario actualizado correctamente');
}

    // ELIMINAR
    public function destroy($id)
    {
        $response = Http::delete("{$this->apiUrl}/{$id}");

        if (!$response->successful()) {
            return back()->withErrors('Error al eliminar usuario');
        }

        return redirect()->route('perfil.index')
            ->with('success', 'Usuario eliminado');
    }
}

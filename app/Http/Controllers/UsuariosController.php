<?php

namespace App\Http\Controllers;

use App\Http\Controller\Controllers;
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
         $this->apiFuncionesUrl = 'http://localhost:5431/api/funciones';

    }

    // LISTAR
        public function index()
        {
            $response = Http::get($this->apiUrl);

            if (!$response->successful()) {
                return back()->withErrors('Error al obtener usuarios');
            }
        
        
            $usuarios = collect($response->json()['data'] ?? [])->map(function ($item) {
                return (object) [
                    'id_usuario' => $item['idUsuario'] ?? $item['id_usuario'] ?? null,
                    'nombre'     => $item['nombre'] ?? '',
                    'correo'     => $item['correo'] ?? '',
                    'telefono'   => $item['telefono'] ?? '',
                    'activo'     => $item['activo'] ?? false,
                    'funcion'    => $item['funcion']['nombreFuncion'] ?? '', // ← nuevo atributo
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
            $funcionesResponse = Http::get($this->apiFuncionesUrl);
            $funciones = $funcionesResponse->successful() ? $funcionesResponse->json()['data'] ?? [] : [];

            $usuarios = (object)[
                'nombre' => '',
                'correo' => '',
                'telefono' => '',
                'activo' => false,
                'id_funcion' => null
            ];

            return view('perfil.create', compact('usuarios', 'funciones'));
        }

    // CREAR


public function store(StoreUsuarioRequest $request)
{
       $validated = $request->validated();

       $activo = isset($validated['activo']) ? (bool)$validated['activo'] : false;

    $payload = [
    'nombre'     => $validated['nombre'],
    'correo'     => $validated['correo'],
    'telefono'   => $validated['telefono'] ?? null,
    'activo'     => $activo,
    'clave'      => $validated['clave'],
    'idFuncion'  => (int) $validated['id_funcion'], // <- directo
];



    $response = Http::post($this->apiUrl, $payload);

    if (!$response->successful()) {
        dd($response->status(), $response->body());
    }

    return redirect()->route('perfil.index')
        ->with('success', 'Usuario creado correctamente');
    }

public function edit($id)
{
    // Traer usuario por ID
    $response = Http::get("{$this->apiUrl}/{$id}");
    if (!$response->successful()) {
        abort(404, 'Usuario no encontrado');
    }
    $usuarioData = $response->json()['data'];

    // Mapear usuario y función
    $usuarios = (object) [
        'id_usuario' => $usuarioData['idUsuario'] ?? null,
        'nombre'     => $usuarioData['nombre'] ?? '',
        'correo'     => $usuarioData['correo'] ?? '',
        'telefono'   => $usuarioData['telefono'] ?? '',
        'activo'     => $usuarioData['activo'] ?? false,
        'funcion'    => $usuarioData['funcion']['nombreFuncion'] ?? '',
        'id_funcion' => $usuarioData['funcion']['idFuncion'] ?? null, // clave para el select
    ];

    // Traer todas las funciones desde la API
    $funcionesResponse = Http::get($this->apiFuncionesUrl);
    $funciones = $funcionesResponse->successful() ? $funcionesResponse->json()['data'] ?? [] : [];

    return view('perfil.edit', compact('usuarios', 'funciones'));
}

public function update(UpdateUsuarioRequest $request, $id)
{
    $validated = $request->validated();

    $activo = isset($validated['activo']) ? (bool)$validated['activo'] : false;

    // Preparar datos para la API
        $usuarioData = [
            'nombre'     => $validated['nombre'],
            'correo'     => $validated['correo'],
            'telefono'   => $validated['telefono'] ?? null,
            'activo'     => $activo,
            'idFuncion'  => (int) $validated['id_funcion'],
            // 'clave' si quieres actualizarla
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
        if (!$response->successful()) {
            return back()->withErrors('Error al eliminar usuario');
        }

        return redirect()->route('perfil.index')
            ->with('success', 'Usuario eliminado');
    }
}

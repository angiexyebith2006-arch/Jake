<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Illuminate\Validation\Rules;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Barryvdh\DomPDF\Facade\Pdf;

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

            // Calcular estadísticas para reportes
            $total = $usuarios->count();
            $activos = $usuarios->where('activo', true)->count();
            $inactivos = $usuarios->where('activo', false)->count();

            return view('perfil.index', compact('usuarios', 'total', 'activos', 'inactivos'));
            
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


 public function reportes()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrl . '/usuarios');

            if (!$response->successful()) {
                return back()->withErrors('Error al obtener usuarios para reportes');
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

            $total = $usuarios->count();
            $activos = $usuarios->where('activo', true)->count();
            $inactivos = $usuarios->where('activo', false)->count();

            return view('perfil.reportes', compact('usuarios', 'total', 'activos', 'inactivos'));
            
        } catch (\Exception $e) {
            return back()->withErrors('Error: ' . $e->getMessage());
        }
    }

    /**
     * Generar reporte en CSV (compatible con Excel)
     */
    public function reporteCsv()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrl . '/usuarios');

            if (!$response->successful()) {
                return back()->withErrors('Error al obtener datos para el reporte');
            }

            $data = $response->json();
            $usuarios = collect($data['data'] ?? $data ?? []);

            $filename = 'reporte_usuarios_' . date('Y-m-d_His') . '.csv';
            
            $handle = fopen('php://output', 'w');
            
            // Headers para Excel
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            // UTF-8 BOM para caracteres especiales
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($handle, ['ID', 'Nombre', 'Correo Electrónico', 'Teléfono', 'Estado', 'Fecha de Reporte']);
            
            // Datos
            foreach ($usuarios as $usuario) {
                fputcsv($handle, [
                    $usuario['idUsuario'] ?? $usuario['id_usuario'] ?? '',
                    $usuario['nombre'] ?? '',
                    $usuario['correo'] ?? '',
                    $usuario['telefono'] ?? 'N/A',
                    ($usuario['activo'] ?? false) ? 'Activo' : 'Inactivo',
                    now()->format('d/m/Y H:i:s')
                ]);
            }
            
            // Agregar resumen
            fputcsv($handle, []);
            fputcsv($handle, ['RESUMEN', '', '', '', '', '']);
            fputcsv($handle, ['Total de Usuarios:', $usuarios->count(), '', '', '', '']);
            fputcsv($handle, ['Usuarios Activos:', $usuarios->where('activo', true)->count(), '', '', '', '']);
            fputcsv($handle, ['Usuarios Inactivos:', $usuarios->where('activo', false)->count(), '', '', '', '']);
            fputcsv($handle, ['Fecha de generación:', now()->format('d/m/Y H:i:s'), '', '', '', '']);
            
            fclose($handle);
            exit;
            
        } catch (\Exception $e) {
            \Log::error('Error generando CSV: ' . $e->getMessage());
            return back()->withErrors('Error al generar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Generar reporte en PDF usando HTML y CSS (sin librerías externas)
     */
    public function reportePdf()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get($this->apiUrl . '/usuarios');

            if (!$response->successful()) {
                return back()->withErrors('Error al obtener datos para el reporte');
            }

            $data = $response->json();
            $usuarios = collect($data['data'] ?? $data ?? []);
            
            $total = $usuarios->count();
            $activos = $usuarios->where('activo', true)->count();
            $inactivos = $usuarios->where('activo', false)->count();

            // Generar HTML del reporte
            $html = $this->generarHtmlReporte($usuarios, $total, $activos, $inactivos);
            
            // Usar la librería nativa de PHP para PDF (requiere escribir archivo)
            // Pero como no tenemos librerías, creamos un HTML que el navegador puede imprimir como PDF
            
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'inline; filename="reporte_usuarios_' . date('Y-m-d_His') . '.html"');
            
        } catch (\Exception $e) {
            \Log::error('Error generando PDF: ' . $e->getMessage());
            return back()->withErrors('Error al generar el reporte: ' . $e->getMessage());
        }
    }

    /**
     * Generar HTML para el reporte (el usuario puede usar "Imprimir > Guardar como PDF")
     */
    private function generarHtmlReporte($usuarios, $total, $activos, $inactivos)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Reporte de Usuarios</title>
            <style>
                @media print {
                    body { margin: 0; padding: 20px; }
                    .no-print { display: none; }
                    table { page-break-inside: avoid; }
                }
                body {
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    margin: 0;
                    padding: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #2C3E50;
                    padding-bottom: 10px;
                }
                .header h1 {
                    color: #2C3E50;
                    margin: 0;
                    font-size: 20px;
                }
                .header p {
                    color: #7F8C8D;
                    margin: 5px 0;
                }
                .stats {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                    gap: 10px;
                }
                .stat-box {
                    background: #F8F9FA;
                    padding: 10px;
                    border-radius: 5px;
                    width: 33%;
                    text-align: center;
                    border: 1px solid #E1E8ED;
                }
                .stat-box h3 {
                    margin: 0;
                    color: #3498DB;
                    font-size: 20px;
                }
                .stat-box p {
                    margin: 5px 0 0;
                    color: #7F8C8D;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 15px;
                }
                th {
                    background: #2C3E50;
                    color: white;
                    padding: 8px;
                    text-align: left;
                }
                td {
                    padding: 8px;
                    border-bottom: 1px solid #BDC3C7;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    padding-top: 10px;
                    border-top: 1px solid #BDC3C7;
                    font-size: 10px;
                    color: #7F8C8D;
                }
                .activo {
                    color: #27AE60;
                    font-weight: bold;
                }
                .inactivo {
                    color: #E74C3C;
                    font-weight: bold;
                }
                button {
                    background: #3498DB;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                    margin-bottom: 20px;
                }
                button:hover {
                    background: #2980B9;
                }
            </style>
        </head>
        <body>
            <div class="no-print" style="text-align: center; margin-bottom: 20px;">
                <button onclick="window.print();">🖨️ Imprimir / Guardar como PDF</button>
                <p style="color: #7F8C8D; margin-top: 5px;">Presione el botón y luego seleccione "Guardar como PDF"</p>
            </div>
            
            <div class="header">
                <h1>📊 Reporte de Usuarios del Sistema</h1>
                <p>Fecha de generación: ' . now()->format('d/m/Y H:i:s') . '</p>
            </div>
        
            <div class="stats">
                <div class="stat-box">
                    <h3>' . $total . '</h3>
                    <p>📋 Total Usuarios</p>
                </div>
                <div class="stat-box">
                    <h3>' . $activos . '</h3>
                    <p>✅ Usuarios Activos</p>
                </div>
                <div class="stat-box">
                    <h3>' . $inactivos . '</h3>
                    <p>❌ Usuarios Inactivos</p>
                </div>
            </div>
        
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($usuarios as $usuario) {
            $estado = ($usuario['activo'] ?? false) ? 'Activo' : 'Inactivo';
            $claseEstado = ($usuario['activo'] ?? false) ? 'activo' : 'inactivo';
            
            $html .= '
                    <tr>
                        <td>' . ($usuario['idUsuario'] ?? $usuario['id_usuario'] ?? '') . '</td>
                        <td>' . htmlspecialchars($usuario['nombre'] ?? '') . '</td>
                        <td>' . htmlspecialchars($usuario['correo'] ?? '') . '</td>
                        <td>' . htmlspecialchars($usuario['telefono'] ?? 'N/A') . '</td>
                        <td class="' . $claseEstado . '">' . $estado . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
        
            <div class="footer">
                <p>Reporte generado automáticamente por el Sistema de Gestión de Usuarios</p>
                <p>© ' . date('Y') . ' - Todos los derechos reservados</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /**
     * Alias para Excel (usa el mismo CSV)
     */
    public function reporteExcel()
    {
        return $this->reporteCsv();
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
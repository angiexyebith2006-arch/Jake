<x-app-layout>
    <main class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-2xl rounded-2xl border border-gray-200 overflow-hidden">

            <!-- HEADER -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-white">Programaciones</h2>
                    <p class="text-blue-100 text-sm">Calendario de programaciones</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('programacion.reportes') }}"
                       class="bg-purple-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-purple-700 transition">
                        <i class="fas fa-chart-bar mr-2"></i> Reportes
                    </a>
                    <a href="{{ route('actividades.index') }}"
                       class="bg-green-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-700 transition">
                        <i class="fas fa-tasks mr-2"></i> Actividad
                    </a>
                    <a href="{{ route('programacion.create') }}"
                       class="bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                        <i class="fas fa-plus mr-2"></i> Nueva Programación
                    </a>
                </div>
            </div>

            <!-- MENSAJES -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4 rounded">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4 rounded">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- FILTROS MINISTERIO (botones rápidos) -->
            <div class="px-6 pt-6">
                <div class="flex gap-4 flex-wrap">
                    <button type="button" id="filterDecom"
                            class="filter-ministerio-btn bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow-md">
                        <i class="fas fa-video mr-1"></i> DECOM
                    </button>
                    <button type="button" id="filterAlabanza"
                            class="filter-ministerio-btn bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700 transition shadow-md">
                        <i class="fas fa-music mr-1"></i> Alabanza
                    </button>
                    <button type="button" id="filterEscuela"
                            class="filter-ministerio-btn bg-yellow-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition shadow-md">
                        <i class="fas fa-child mr-1"></i> Escuela Dominical
                    </button>
                    <button type="button" id="filterGeneral"
                            class="filter-ministerio-btn bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition shadow-md">
                        <i class="fas fa-users mr-1"></i> General
                    </button>
                    <button type="button" id="clearFilters"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                        <i class="fas fa-times mr-1"></i> Limpiar
                    </button>
                </div>
            </div>

            <!-- FILTROS FORMULARIO -->
            <div class="p-6 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('programacion.index') }}" id="filterForm" class="space-y-4">
                    <input type="hidden" name="ministerio" id="ministerioField" value="{{ request('ministerio') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-1"></i> Buscar
                            </label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Actividad o asignación..."
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-filter mr-1"></i> Estado
                            </label>
                            <select name="estado" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos los estados</option>
                                <option value="pendiente"            {{ request('estado') == 'pendiente'            ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmado"           {{ request('estado') == 'confirmado'           ? 'selected' : '' }}>Confirmado</option>
                                <option value="reemplazado"          {{ request('estado') == 'reemplazado'          ? 'selected' : '' }}>Reemplazado</option>
                                <option value="cancelado"            {{ request('estado') == 'cancelado'            ? 'selected' : '' }}>Cancelado</option>
                                <option value="reemplazo_solicitado" {{ request('estado') == 'reemplazo_solicitado' ? 'selected' : '' }}>Reemplazo Solicitado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i> Desde
                            </label>
                            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i> Hasta
                            </label>
                            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        @if(request('search') || request('estado') || request('fecha_desde') || request('fecha_hasta') || request('ministerio'))
                            <a href="{{ route('programacion.index') }}"
                               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                                <i class="fas fa-times mr-2"></i> Limpiar filtros
                            </a>
                        @endif
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-2"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>

            <!-- CONTADOR -->
            <div class="px-6 py-3 bg-gray-100 border-b border-gray-200">
                <div class="text-sm text-gray-600 flex items-center gap-3 flex-wrap">
                    <span>
                        <i class="fas fa-calendar mr-1"></i>
                        Mostrando <span class="font-semibold">{{ $programaciones->count() }}</span> programaciones
                    </span>
                    @if(request('ministerio'))
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">
                            <i class="fas fa-filter mr-1"></i> Ministerio: {{ request('ministerio') }}
                        </span>
                    @endif
                    @if(request('estado'))
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs">
                            <i class="fas fa-circle mr-1"></i> Estado: {{ request('estado') }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- ===== TARJETAS AGRUPADAS POR FECHA + ACTIVIDAD ===== -->
            @php
                $agrupadas    = $programaciones->groupBy(fn($p) => $p->fecha . '||' . $p->nombre_actividad);
                $paginaActual = max(1, (int) request('pagina', 1));
                $porPagina    = 6;
                $totalGrupos  = $agrupadas->count();
                $totalPaginas = max(1, ceil($totalGrupos / $porPagina));
                $paginaActual = min($paginaActual, $totalPaginas);
                $gruposPagina = $agrupadas->slice(($paginaActual - 1) * $porPagina, $porPagina);
            @endphp

            <div class="p-6 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-700 mb-4">
                    <i class="fas fa-th-large mr-2 text-blue-600"></i> Vista por Actividad
                </h3>

                @if($agrupadas->isEmpty())
                    <div class="text-center py-16 text-gray-400">
                        <i class="fas fa-calendar-times text-5xl mb-4"></i>
                        <p class="text-lg font-medium">No hay programaciones</p>
                        <p class="text-sm mt-1">Prueba ajustando los filtros o crea una nueva programación.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($gruposPagina as $clave => $grupo)
                            @php
                                [$fecha, $actividad] = explode('||', $clave);

                                // Separar por ministerio (comparación insensible a mayúsculas)
                                $alabanzaItems = $grupo->filter(fn($p) => strtolower($p->nombre_ministerio ?? '') === 'alabanza');
                                $decomItems    = $grupo->filter(fn($p) => strtolower($p->nombre_ministerio ?? '') === 'decom');
                                $escuelaItems  = $grupo->filter(fn($p) => str_contains(strtolower($p->nombre_ministerio ?? ''), 'escuela'));
                                $generalItems  = $grupo->filter(fn($p) =>
                                    strtolower($p->nombre_ministerio ?? '') !== 'alabanza' &&
                                    strtolower($p->nombre_ministerio ?? '') !== 'decom' &&
                                    !str_contains(strtolower($p->nombre_ministerio ?? ''), 'escuela')
                                );

                                // Un solo representante del grupo para las acciones
                                $primerItem = $grupo->first();
                            @endphp

                            <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200 flex flex-col">

                                <!-- Cabecera: fecha + nombre actividad -->
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3">
                                    <p class="text-white font-bold text-sm leading-tight">
                                        {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                                    </p>
                                    <p class="text-blue-100 text-xs mt-0.5 truncate" title="{{ $actividad }}">
                                        {{ $actividad }}
                                    </p>
                                </div>

                                <!-- Cuerpo de bloques -->
                                <div class="flex-1 divide-y divide-gray-100">

                                    <!-- Bloque ALABANZA -->
                                    <div class="p-3">
                                        <p class="flex items-center gap-1.5 font-semibold text-xs text-purple-700 uppercase tracking-wide mb-2">
                                            <i class="fas fa-music text-purple-400"></i> Alabanza
                                        </p>
                                        @if($alabanzaItems->isEmpty())
                                            <p class="text-gray-400 text-xs italic px-1">Sin asignaciones</p>
                                        @else
                                            @foreach($alabanzaItems as $item)
                                                @php
                                                    $badge = match(strtolower($item->estado)) {
                                                        'confirmado'           => 'bg-green-100 text-green-700 border-green-200',
                                                        'reemplazado'          => 'bg-blue-100 text-blue-700 border-blue-200',
                                                        'reemplazo_solicitado' => 'bg-orange-100 text-orange-700 border-orange-200',
                                                        'cancelado'            => 'bg-gray-100 text-gray-500 border-gray-200',
                                                        default                => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    };
                                                    $cargo = $item->nombre_asignacion; // "Ana Varon - Voz lider"
                                                @endphp
                                                <div class="flex items-center justify-between gap-2 py-1.5 px-2 rounded-lg bg-purple-50 mb-1 last:mb-0">
                                                    <span class="text-xs text-gray-800 font-medium truncate flex-1" title="{{ $cargo }}">
                                                        {{ $cargo }}
                                                    </span>
                                                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold whitespace-nowrap {{ $badge }}">
                                                        {{ ucfirst(strtolower($item->estado)) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- Bloque DECOM -->
                                    <div class="p-3">
                                        <p class="flex items-center gap-1.5 font-semibold text-xs text-blue-700 uppercase tracking-wide mb-2">
                                            <i class="fas fa-video text-blue-400"></i> DECOM
                                        </p>
                                        @if($decomItems->isEmpty())
                                            <p class="text-gray-400 text-xs italic px-1">Sin asignaciones</p>
                                        @else
                                            @foreach($decomItems as $item)
                                                @php
                                                    $badge = match(strtolower($item->estado)) {
                                                        'confirmado'           => 'bg-green-100 text-green-700 border-green-200',
                                                        'reemplazado'          => 'bg-blue-100 text-blue-700 border-blue-200',
                                                        'reemplazo_solicitado' => 'bg-orange-100 text-orange-700 border-orange-200',
                                                        'cancelado'            => 'bg-gray-100 text-gray-500 border-gray-200',
                                                        default                => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    };
                                                @endphp
                                                <div class="flex items-center justify-between gap-2 py-1.5 px-2 rounded-lg bg-blue-50 mb-1 last:mb-0">
                                                    <span class="text-xs text-gray-800 font-medium truncate flex-1" title="{{ $item->nombre_asignacion }}">
                                                        {{ $item->nombre_asignacion }}
                                                    </span>
                                                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold whitespace-nowrap {{ $badge }}">
                                                        {{ ucfirst(strtolower($item->estado)) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- Bloque ESCUELA DOMINICAL -->
                                    <div class="p-3">
                                        <p class="flex items-center gap-1.5 font-semibold text-xs text-yellow-700 uppercase tracking-wide mb-2">
                                            <i class="fas fa-child text-yellow-400"></i> Escuela Dominical
                                        </p>
                                        @if($escuelaItems->isEmpty())
                                            <p class="text-gray-400 text-xs italic px-1">Sin asignaciones</p>
                                        @else
                                            @foreach($escuelaItems as $item)
                                                @php
                                                    $badge = match(strtolower($item->estado)) {
                                                        'confirmado'           => 'bg-green-100 text-green-700 border-green-200',
                                                        'reemplazado'          => 'bg-blue-100 text-blue-700 border-blue-200',
                                                        'reemplazo_solicitado' => 'bg-orange-100 text-orange-700 border-orange-200',
                                                        'cancelado'            => 'bg-gray-100 text-gray-500 border-gray-200',
                                                        default                => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    };
                                                @endphp
                                                <div class="flex items-center justify-between gap-2 py-1.5 px-2 rounded-lg bg-yellow-50 mb-1 last:mb-0">
                                                    <span class="text-xs text-gray-800 font-medium truncate flex-1" title="{{ $item->nombre_asignacion }}">
                                                        {{ $item->nombre_asignacion }}
                                                    </span>
                                                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold whitespace-nowrap {{ $badge }}">
                                                        {{ ucfirst(strtolower($item->estado)) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- Bloque GENERAL (solo si hay items) -->
                                    @if($generalItems->isNotEmpty())
                                        <div class="p-3">
                                            <p class="flex items-center gap-1.5 font-semibold text-xs text-gray-600 uppercase tracking-wide mb-2">
                                                <i class="fas fa-users text-gray-400"></i> General
                                            </p>
                                            @foreach($generalItems as $item)
                                                @php
                                                    $badge = match(strtolower($item->estado)) {
                                                        'confirmado'           => 'bg-green-100 text-green-700 border-green-200',
                                                        'reemplazado'          => 'bg-blue-100 text-blue-700 border-blue-200',
                                                        'reemplazo_solicitado' => 'bg-orange-100 text-orange-700 border-orange-200',
                                                        'cancelado'            => 'bg-gray-100 text-gray-500 border-gray-200',
                                                        default                => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                                    };
                                                @endphp
                                                <div class="flex items-center justify-between gap-2 py-1.5 px-2 rounded-lg bg-gray-50 mb-1 last:mb-0">
                                                    <span class="text-xs text-gray-800 font-medium truncate flex-1" title="{{ $item->nombre_asignacion }}">
                                                        {{ $item->nombre_asignacion }}
                                                    </span>
                                                    <span class="text-xs px-2 py-0.5 rounded-full border font-semibold whitespace-nowrap {{ $badge }}">
                                                        {{ ucfirst(strtolower($item->estado)) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>

                                {{--
                                    ACCIONES: una sola vez por tarjeta (por grupo fecha+actividad).
                                    Usamos $primerItem solo para tener una URL de referencia general.
                                    Si la tarjeta tiene varias programaciones (varias personas),
                                    los botones individuales de editar/eliminar por persona
                                    aparecen en el listado expandido debajo.
                                --}}
                                <div class="bg-gray-50 px-3 py-2 border-t border-gray-200 flex gap-2 flex-wrap items-center">
                                    {{-- Botones individuales por cada programación del grupo --}}
                                    @foreach($grupo as $item)
                                        <div class="flex items-center gap-1 w-full border-b border-gray-100 pb-1 last:border-0 last:pb-0">
                                            <span class="text-xs text-gray-500 flex-1 truncate">
                                                #{{ $item->id_programacion }}
                                                — {{ $item->nombre_ministerio ?? 'General' }}
                                            </span>
                                            <a href="{{ route('programacion.edit', $item->id_programacion) }}"
                                               class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition whitespace-nowrap">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <button
                                                onclick="eliminarProg({{ $item->id_programacion }}, '{{ route('programacion.destroy', $item->id_programacion) }}')"
                                                class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 transition whitespace-nowrap">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    @if($totalPaginas > 1)
                        <div class="flex justify-center items-center gap-2 mt-8 flex-wrap">
                            @if($paginaActual > 1)
                                <a href="{{ request()->fullUrlWithQuery(['pagina' => $paginaActual - 1]) }}"
                                   class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif
                            @for($i = 1; $i <= $totalPaginas; $i++)
                                <a href="{{ request()->fullUrlWithQuery(['pagina' => $i]) }}"
                                   class="px-3 py-2 rounded-lg text-sm font-semibold transition
                                          {{ $i == $paginaActual ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                            @if($paginaActual < $totalPaginas)
                                <a href="{{ request()->fullUrlWithQuery(['pagina' => $paginaActual + 1]) }}"
                                   class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @endif
                            <span class="text-xs text-gray-500 ml-2">
                                Página {{ $paginaActual }} de {{ $totalPaginas }}
                                ({{ $totalGrupos }} {{ $totalGrupos === 1 ? 'actividad' : 'actividades' }})
                            </span>
                        </div>
                    @endif
                @endif
            </div>

            <!-- ===== CALENDARIO ===== -->
            <div class="p-6">
                <h3 class="text-base font-bold text-gray-700 mb-4">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i> Vista Calendario
                </h3>
                <div id="calendar"></div>
            </div>

        </div>
    </main>

    <!-- LIBRERÍAS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .fc .fc-toolbar-title { font-size: 1.5rem; font-weight: bold; }
        .fc-button { background-color: #2563eb !important; border: none !important; }
        .fc-button:hover { background-color: #1d4ed8 !important; }
        .fc-event { border: none !important; border-radius: 8px !important; padding: 3px !important; font-size: 12px !important; }
        .fc-daygrid-event { white-space: normal !important; }
        .fc-day-today { background: #eff6ff !important; }
        .filter-ministerio-btn.active { opacity: 0.75; transform: scale(0.97); box-shadow: inset 0 2px 4px rgba(0,0,0,0.2); }
    </style>

    <script>
    // ── Eliminar con confirmación ──────────────────────────────────────────────
    function eliminarProg(id, url) {
        Swal.fire({
            title: '¿Eliminar programación?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {

        // ── Botones de ministerio ──────────────────────────────────────────────
        const filterDecom     = document.getElementById('filterDecom');
        const filterAlabanza  = document.getElementById('filterAlabanza');
        const filterEscuela   = document.getElementById('filterEscuela');
        const filterGeneral   = document.getElementById('filterGeneral');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const ministerioField = document.getElementById('ministerioField');
        const filterForm      = document.getElementById('filterForm');

        function applyMinisterioFilter(v) {
            ministerioField.value = v;
            filterForm.submit();
        }

        if (filterDecom)    filterDecom.addEventListener('click',    () => applyMinisterioFilter('DECOM'));
        if (filterAlabanza) filterAlabanza.addEventListener('click', () => applyMinisterioFilter('Alabanza'));
        if (filterEscuela)  filterEscuela.addEventListener('click',  () => applyMinisterioFilter('Escuela Dominical'));
        if (filterGeneral)  filterGeneral.addEventListener('click',  () => applyMinisterioFilter('General'));

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function () {
                ministerioField.value = '';
                filterForm.querySelector('input[name="search"]').value      = '';
                filterForm.querySelector('select[name="estado"]').value     = '';
                filterForm.querySelector('input[name="fecha_desde"]').value = '';
                filterForm.querySelector('input[name="fecha_hasta"]').value = '';
                filterForm.submit();
            });
        }

        // Marcar botón activo según filtro actual
        const cur = '{{ request('ministerio') }}';
        if (cur === 'DECOM'             && filterDecom)    filterDecom.classList.add('active');
        if (cur === 'Alabanza'          && filterAlabanza) filterAlabanza.classList.add('active');
        if (cur === 'Escuela Dominical' && filterEscuela)  filterEscuela.classList.add('active');
        if (cur === 'General'           && filterGeneral)  filterGeneral.classList.add('active');

        // ── Calendario FullCalendar ────────────────────────────────────────────
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            height: 'auto',
            headerToolbar: {
                left:   'prev,next today',
                center: 'title',
                right:  'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día' },
            events: [
                @foreach($programaciones as $prog)
                {
                    id:    '{{ $prog->id_programacion }}',
                    title: '{{ addslashes($prog->nombre_actividad) }} — {{ addslashes($prog->nombre_asignacion) }}',
                    start: '{{ $prog->fecha }}',
                    backgroundColor: '{{ strtolower($prog->estado) }}' === 'confirmado'           ? '#16a34a' :
                                     '{{ strtolower($prog->estado) }}' === 'pendiente'            ? '#eab308' :
                                     '{{ strtolower($prog->estado) }}' === 'reemplazado'          ? '#2563eb' :
                                     '{{ strtolower($prog->estado) }}' === 'reemplazo_solicitado' ? '#ea580c' : '#6b7280',
                    borderColor:     '{{ strtolower($prog->estado) }}' === 'confirmado'           ? '#16a34a' :
                                     '{{ strtolower($prog->estado) }}' === 'pendiente'            ? '#eab308' :
                                     '{{ strtolower($prog->estado) }}' === 'reemplazado'          ? '#2563eb' :
                                     '{{ strtolower($prog->estado) }}' === 'reemplazo_solicitado' ? '#ea580c' : '#6b7280',
                    textColor: '#ffffff',
                    extendedProps: {
                        actividad:  '{{ addslashes($prog->nombre_actividad) }}',
                        asignacion: '{{ addslashes($prog->nombre_asignacion) }}',
                        ministerio: '{{ addslashes($prog->nombre_ministerio ?? 'General') }}',
                        estado:     '{{ strtolower($prog->estado) }}',
                        editUrl:    '{{ route("programacion.edit",    $prog->id_programacion) }}',
                        deleteUrl:  '{{ route("programacion.destroy", $prog->id_programacion) }}'
                    }
                },
                @endforeach
            ],
            eventClick: function(info) {
                const e = info.event;
                const estadoLabel = {
                    confirmado:           'Confirmado',
                    pendiente:            'Pendiente',
                    reemplazado:          'Reemplazado',
                    cancelado:            'Cancelado',
                    reemplazo_solicitado: 'Reemplazo solicitado'
                }[e.extendedProps.estado] ?? e.extendedProps.estado;

                Swal.fire({
                    title: e.extendedProps.actividad,
                    html: `
                        <div class="text-left space-y-2 text-sm">
                            <p><i class="fas fa-user mr-1 text-gray-500"></i>
                               <strong>Asignación:</strong> ${e.extendedProps.asignacion}</p>
                            <p><i class="fas fa-church mr-1 text-gray-500"></i>
                               <strong>Ministerio:</strong> ${e.extendedProps.ministerio}</p>
                            <p><i class="fas fa-circle mr-1 text-gray-500"></i>
                               <strong>Estado:</strong> ${estadoLabel}</p>
                            <p><i class="fas fa-calendar mr-1 text-gray-500"></i>
                               <strong>Fecha:</strong> ${e.start.toLocaleDateString('es-CO')}</p>
                        </div>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: '<i class="fas fa-edit mr-1"></i> Editar',
                    denyButtonText:    '<i class="fas fa-trash mr-1"></i> Eliminar',
                    cancelButtonText:  'Cerrar',
                    confirmButtonColor: '#2563eb',
                    denyButtonColor:    '#dc2626'
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = e.extendedProps.editUrl;
                    if (result.isDenied)    eliminarProg(e.id, e.extendedProps.deleteUrl);
                });
            }
        });

        calendar.render();
    });
    </script>

</x-app-layout>
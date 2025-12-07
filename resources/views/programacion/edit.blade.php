@extends('layouts.app')

@section('content')
<div class="container">

    <h2>Editar Programación</h2>

    <form action="{{ route('programacion.update', $programacion->id_programacion) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Actividad</label>
            <select name="id_actividad" class="form-control" required>
                @foreach($actividades as $a)
                <option value="{{ $a->id_actividad }}"
                    {{ $a->id_actividad == $programacion->id_actividad ? 'selected' : '' }}>
                    {{ $a->nombre }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Asignación</label>
            <select name="id_asignacion" class="form-control" required>
                @foreach($asignaciones as $x)
                <option value="{{ $x->id_asignacion }}"
                    {{ $x->id_asignacion == $programacion->id_asignacion ? 'selected' : '' }}>
                    {{ $x->id_asignacion }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Fecha</label>
            <input type="date" name="fecha" class="form-control"
                   value="{{ $programacion->fecha }}" required>
        </div>

        <div class="mb-3">
            <label>Hora Inicio</label>
            <input type="time" name="hora_inicio" class="form-control"
                   value="{{ $programacion->hora_inicio }}" required>
        </div>

        <div class="mb-3">
            <label>Hora Fin</label>
            <input type="time" name="hora_fin" class="form-control"
                   value="{{ $programacion->hora_fin }}" required>
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control">
                <option value="Programado" {{ $programacion->estado == 'Programado' ? 'selected':'' }}>Programado</option>
                <option value="Reemplazado" {{ $programacion->estado == 'Reemplazado' ? 'selected':'' }}>Reemplazado</option>
                <option value="Cancelado" {{ $programacion->estado == 'Cancelado' ? 'selected':'' }}>Cancelado</option>
            </select>
        </div>

        <button class="btn btn-primary">Actualizar</button>

    </form>
</div>
@endsection

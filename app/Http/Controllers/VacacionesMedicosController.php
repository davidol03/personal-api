<?php

namespace App\Http\Controllers;

use App\Models\VacacionesMedico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VacacionesMedicosController extends Controller
{
    // Mostrar todas las vacaciones de médicos con sus relaciones
    public function index()
    {
        $vacaciones = VacacionesMedico::with(['medico', 'estatus', 'estatusUsuario'])->get();

        // Mapea los resultados para mostrar campos específicos
        $vacaciones = $vacaciones->map(function ($vacacion) {
            return [
                'id_vacacion' => $vacacion->id_vacacion,
                'nombre_medico' => $vacacion->medico->nombre_medico ?? 'No asignado',
                'apellido_medico' => $vacacion->medico->apellido_medico ?? 'No asignado',
                'fecha_inicio' => $vacacion->fecha_inicio,
                'fecha_fin' => $vacacion->fecha_fin,
                'motivo_vacacion' => $vacacion->motivo_vacacion ?? 'No especificado',
                'estatus' => $vacacion->estatus->nombre_estatus ?? 'Sin estatus',
                'estatus_usuario' => $vacacion->estatusUsuario->nombre_usuario ?? 'Sin estatus de usuario',
            ];
        });

        return response()->json($vacaciones);
    }

    // Crear una nueva vacación para un médico
    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all());

        if (!is_array($validar)) {
            // Crear la vacación con los datos validados
            VacacionesMedico::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Mostrar una vacación específica por su ID
    public function show($id)
    {
        $vacacion = VacacionesMedico::with(['medico', 'estatus', 'estatusUsuario'])->find($id);

        if (!$vacacion) {
            return response()->json(['message' => 'Vacación no encontrada'], 404);
        }

        return response()->json($vacacion);
    }

    // Actualizar una vacación existente
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $validar = $this->validarSinId($request->all()); // Validación sin `unique` en `id_vacacion`

        if (!is_array($validar)) {
            $vacacion = VacacionesMedico::find($id);
            if ($vacacion) {
                $vacacion->fill($request->all())->save();
                array_push($respuesta, ['status' => 'success']);
            } else {
                array_push($respuesta, ['status' => 'error']);
                array_push($respuesta, ['errors' => 'No existe el ID']);
            }
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Eliminar una vacación
    public function destroy($id)
    {
        $vacacion = VacacionesMedico::find($id);

        if (!$vacacion) {
            return response()->json(['message' => 'Vacación no encontrada'], 404);
        }

        $vacacion->delete();
        return response()->json(['message' => 'Vacación eliminada con éxito']);
    }

    // Validación para la creación de una nueva vacación
    public function validar($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
            'date' => 'El campo :attribute debe ser una fecha válida',
        ];

        $validacion = Validator::make($parametros, [
            'id_vacacion' => 'required|integer|unique:vacaciones_medico,id_vacacion',
            'id_medico' => 'required|integer|exists:medico,id_medico',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo_vacacion' => 'nullable|string|max:255',
            'id_estatus' => 'required|integer|exists:estatus,id_estatus',
            'id_estatus_usuario' => 'required|integer|exists:estatus_usuario,id_estatus_usuario',
        ], $messages);

        if ($validacion->fails()) {
            array_push($respuesta, ['status' => 'error']);
            array_push($respuesta, ['errors' => $validacion->errors()]);
            return $respuesta;
        } else {
            return true;
        }
    }

    // Validación para la actualización sin `unique` en `id_vacacion`
    public function validarSinId($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'date' => 'El campo :attribute debe ser una fecha válida',
        ];

        $validacion = Validator::make($parametros, [
            'id_medico' => 'required|integer|exists:medico,id_medico',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo_vacacion' => 'nullable|string|max:255',
            'id_estatus' => 'required|integer|exists:estatus,id_estatus',
            'id_estatus_usuario' => 'required|integer|exists:estatus_usuario,id_estatus_usuario',
        ], $messages);

        if ($validacion->fails()) {
            array_push($respuesta, ['status' => 'error']);
            array_push($respuesta, ['errors' => $validacion->errors()]);
            return $respuesta;
        } else {
            return true;
        }
    }
}


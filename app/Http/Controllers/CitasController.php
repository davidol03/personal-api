<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitasController extends Controller
{
    // Mostrar todas las citas con el nombre del médico, el estatus y el estatus del usuario
    public function index()
    {
        // Obtiene todas las citas junto con sus relaciones de médico, estatus y estatus_usuario
        $citas = Cita::with(['medico','consultorio', 'estatus', 'estatusUsuario'])->get();

        // Mapea el resultado para incluir los nombres de los estatus y médicos en lugar de sus IDs
        $citas = $citas->map(function ($cita) {
            return [
                'id_cita' => $cita->id_cita,
                'fecha_hora_cita' => $cita->fecha_hora_cita,
                'medico' => $cita->medico->nombre_medico ?? 'Sin médico',
                'consultorio' => $cita->consultorio->ubicacion_consultorio ?? 'Sin consultorio',
                'estatus' => $cita->estatus->nombre_estatus ?? 'Sin estatus',
                'estatus_usuario' => $cita->estatusUsuario->nombre_usuario ?? 'Sin estatus de usuario',
            ];
        });

        return response()->json($citas);
    }

    // Crear una nueva cita
    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all());

        if (!is_array($validar)) {
            // Crea la cita con los datos validados
            Cita::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Mostrar una cita específica por su ID
    public function show($id)
    {
        $cita = Cita::with(['medico', 'estatus', 'estatusUsuario'])->find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        return response()->json($cita);
    }

    // Actualizar una cita existente
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $validar = $this->validarSinId($request->all()); // Usamos la nueva función sin validación de ID

        if (!is_array($validar)) {
            $cita = Cita::find($id);
            if ($cita) {
                $cita->fill($request->all())->save();
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

    // Eliminar una cita
    public function destroy($id)
    {
        $cita = Cita::find($id);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        // Elimina la cita
        $cita->delete();

        return response()->json(['message' => 'Cita eliminada con éxito']);
    }

    // Función de validación para crear
    public function validar($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
        ];

        // Validación de los parámetros de la cita
        $validacion = Validator::make($parametros, [
            'id_cita' => 'required|integer|unique:cita,id_cita',
            'fecha_hora_cita' => 'required|date',
            'id_medico' => 'required|integer|exists:medico,id_medico',
            'id_consultorio' => 'required|integer|exists:consultorio,id_consultorio',
            'id_estatus' => 'required|integer|exists:estatus,id_estatus',
            'id_estatus_usuario' => 'required|integer|exists:estatus_usuario,id_estatus_usuario',
            'id_paciente' => 'nullable|exists:pacientes,id_paciente',  // Si el campo es opcional

        ], $messages);

        if ($validacion->fails()) {
            array_push($respuesta, ['status' => 'error']);
            array_push($respuesta, ['errors' => $validacion->errors()]);
            return $respuesta;
        } else {
            return true;
        }
    }

    // Nueva función de validación para actualizar, sin la regla 'unique' en id_cita
    public function validarSinId($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
        ];

        // Validación de los parámetros para actualizar la cita
        $validacion = Validator::make($parametros, [
            'fecha_hora_cita' => 'required|date',
            'id_medico' => 'required|integer|exists:medico,id_medico',
            'id_consultorio' => 'required|integer|exists:consultorio,id_consultorio',
            'id_estatus' => 'required|integer|exists:estatus,id_estatus',
            'id_estatus_usuario' => 'required|integer|exists:estatus_usuario,id_estatus_usuario',
            'id_paciente' => 'nullable|exists:pacientes,id_paciente',  // Si el campo es opcional

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

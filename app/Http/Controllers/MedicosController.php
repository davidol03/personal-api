<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicosController extends Controller
{
    // Mostrar todos los médicos con su especialidad, consultorio, estatus y estatus del usuario
    public function index()
    {
        // Obtiene todos los médicos junto con sus relaciones de especialidad, consultorio, estatus y estatus_usuario
        $medicos = Medico::with(['especialidad', 'consultorio', 'estatus', 'estatusUsuario'])->get();

        // Mapea el resultado para incluir los nombres de las especialidades, consultorios, estatus y médicos
        $medicos = $medicos->map(function ($medico) {
            return [
                'id_medico' => $medico->id_medico,
                'nombre_medico' => $medico->nombre_medico,
                'apellido_medico' => $medico->apellido_medico,
                'telefono' => $medico->telefono ?? 'No disponible',
                'email_medico' => $medico->email_medico ?? 'No disponible',
                'especialidad' => $medico->especialidad->nombre_especialidad ?? 'Sin especialidad',
                'consultorio' => $medico->consultorio->ubicacion_consultorio ?? 'Sin consultorio',
                'estatus' => $medico->estatus->nombre_estatus ?? 'Sin estatus',
                'estatus_usuario' => $medico->estatusUsuario->nombre_usuario ?? 'Sin estatus de usuario',
            ];
        });

        return response()->json($medicos);
    }

    // Crear un nuevo médico
    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all());

        if (!is_array($validar)) {
            // Crea el médico con los datos validados
            Medico::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Mostrar un médico específico por su ID
    public function show($id)
    {
        $medico = Medico::with(['especialidad', 'consultorio', 'estatus', 'estatusUsuario'])->find($id);

        if (!$medico) {
            return response()->json(['message' => 'Médico no encontrado'], 404);
        }

        return response()->json($medico);
    }

    // Actualizar un médico existente
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $validar = $this->validarSinId($request->all()); // Usamos la nueva función sin validación de ID

        if (!is_array($validar)) {
            $medico = Medico::find($id);
            if ($medico) {
                $medico->fill($request->all())->save();
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

    // Eliminar un médico
    public function destroy($id)
    {
        $medico = Medico::find($id);

        if (!$medico) {
            return response()->json(['message' => 'Médico no encontrado'], 404);
        }

        // Elimina el médico
        $medico->delete();

        return response()->json(['message' => 'Médico eliminado con éxito']);
    }

    // Función de validación para crear
    public function validar($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
        ];

        // Validación de los parámetros del médico
        $validacion = Validator::make($parametros, [
            'id_medico' => 'required|integer|unique:medico,id_medico',
            'nombre_medico' => 'required|string|max:100',
            'apellido_medico' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:15',
            'email_medico' => 'nullable|email|max:100',
            'id_especialidad' => 'required|integer|exists:especialidad,id_especialidad',
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

    // Nueva función de validación para actualizar, sin la regla 'unique' en id_medico
    public function validarSinId($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
        ];

        // Validación de los parámetros para actualizar el médico
        $validacion = Validator::make($parametros, [
            'nombre_medico' => 'required|string|max:100',
            'apellido_medico' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:15',
            'email_medico' => 'nullable|email|max:100',
            'id_especialidad' => 'required|integer|exists:especialidad,id_especialidad',
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

<?php

namespace App\Http\Controllers;

use App\Models\EquipoMedico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EquipomedicosController extends Controller
{
    // Mostrar todos los equipos médicos con su estatus y estatus del usuario
    public function index()
    {
        // Obtiene todos los equipos médicos junto con sus relaciones de estatus y estatusUsuario
        $equipos = EquipoMedico::with(['estatus', 'estatusUsuario'])->get();

        // Mapea el resultado para incluir los nombres de estatus y estatus de usuario
        $equipos = $equipos->map(function ($equipo) {
            return [
                'id_equipomedico' => $equipo->id_equipomedico,
                'nombre_equipo' => $equipo->nombre_equipo,
                'descripcion' => $equipo->descripcion,
                'fecha_uso' => $equipo->fecha_uso,
                'estatus' => $equipo->estatus->nombre_estatus ?? 'Sin estatus',
                'estatus_usuario' => $equipo->estatusUsuario->nombre_usuario ?? 'Sin estatus de usuario',
            ];
        });

        return response()->json($equipos);
    }

    // Crear un nuevo equipo médico
    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all());

        if (!is_array($validar)) {
            // Crea el equipo con los datos validados
            EquipoMedico::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Mostrar un equipo específico por su ID
    public function show($id)
    {
        $equipo = EquipoMedico::with(['estatus', 'estatusUsuario'])->find($id);

        if (!$equipo) {
            return response()->json(['message' => 'Equipo médico no encontrado'], 404);
        }

        return response()->json($equipo);
    }

    // Actualizar un equipo médico existente
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $validar = $this->validarSinId($request->all());

        if (!is_array($validar)) {
            $equipo = EquipoMedico::find($id);
            if ($equipo) {
                $equipo->fill($request->all())->save();
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

    // Eliminar un equipo médico
    public function destroy($id)
    {
        $equipo = EquipoMedico::find($id);

        if (!$equipo) {
            return response()->json(['message' => 'Equipo médico no encontrado'], 404);
        }

        // Elimina el equipo
        $equipo->delete();

        return response()->json(['message' => 'Equipo médico eliminado con éxito']);
    }

    // Función de validación para crear
    public function validar($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
        ];

        // Validación de los parámetros del equipo médico
        $validacion = Validator::make($parametros, [
            'id_equipomedico' => 'required|integer|unique:equipo_medico,id_equipomedico',
            'nombre_equipo' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'fecha_uso' => 'required|date',
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

    // Función de validación para actualizar, sin la regla 'unique' en id_equipomedico
    public function validarSinId($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
        ];

        // Validación de los parámetros para actualizar el equipo médico
        $validacion = Validator::make($parametros, [
            'nombre_equipo' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
            'fecha_uso' => 'required|date',
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


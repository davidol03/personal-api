<?php

namespace App\Http\Controllers;

use App\Models\Consultorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultoriosController extends Controller
{
    // Mostrar todos los consultorios con su estatus y estatus de usuario
    public function index()
    {
        // Obtiene todos los consultorios con sus relaciones de estatus y estatus_usuario
        $consultorios = Consultorio::with(['estatus', 'estatusUsuario'])->get();

        // Mapea el resultado para incluir los nombres de los estatus en lugar de sus IDs
        $consultorios = $consultorios->map(function ($consultorio) {
            return [
                'id_consultorio' => $consultorio->id_consultorio,
                'ubicacion_consultorio' => $consultorio->ubicacion_consultorio,
                'capacidad_consultorio' => $consultorio->capacidad_consultorio,
                'estatus' => $consultorio->estatus->nombre_estatus ?? 'Sin estatus',
                'estatus_usuario' => $consultorio->estatusUsuario->nombre_usuario ?? 'Sin estatus de usuario',
            ];
        });

        return response()->json($consultorios);
    }

    // Crear un nuevo consultorio
    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all());

        if (!is_array($validar)) {
            // Crea el consultorio con los datos validados
            Consultorio::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Mostrar un consultorio específico por su ID
    public function show($id)
    {
        $consultorio = Consultorio::with(['estatus', 'estatusUsuario'])->find($id);

        if (!$consultorio) {
            return response()->json(['message' => 'Consultorio no encontrado'], 404);
        }

        return response()->json($consultorio);
    }

    // Actualizar un consultorio existente
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $validar = $this->validarSinId($request->all()); // Usamos la nueva función sin validación de ID

        if (!is_array($validar)) {
            $consultorio = Consultorio::find($id);
            if ($consultorio) {
                $consultorio->fill($request->all())->save();
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

    // Eliminar un consultorio
    public function destroy($id)
    {
        $consultorio = Consultorio::find($id);

        if (!$consultorio) {
            return response()->json(['message' => 'Consultorio no encontrado'], 404);
        }

        // Elimina el consultorio
        $consultorio->delete();

        return response()->json(['message' => 'Consultorio eliminado con éxito']);
    }

    // Función de validación para crear
    public function validar($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
        ];

        // Validación de los parámetros del consultorio
        $validacion = Validator::make($parametros, [
            'id_consultorio' => 'required|integer|unique:consultorio,id_consultorio',
            'ubicacion_consultorio' => 'required|string',
            'capacidad_consultorio' => 'required|integer',
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

    // Nueva función de validación para actualizar, sin la regla 'unique' en id_consultorio
    public function validarSinId($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
        ];

        // Validación de los parámetros para actualizar el consultorio
        $validacion = Validator::make($parametros, [
            'ubicacion_consultorio' => 'required|string',
            'capacidad_consultorio' => 'required|integer',
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

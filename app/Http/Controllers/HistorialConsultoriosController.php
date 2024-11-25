<?php

namespace App\Http\Controllers;

use App\Models\HistorialConsultorio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistorialConsultoriosController extends Controller
{
    // Mostrar todos los registros de historial de consultorios
    public function index()
    {
        $historial = HistorialConsultorio::with(['consultorio', 'medico', 'estatus', 'estatusUsuario'])->get();

        // Formateo para mostrar campos específicos
        $historial = $historial->map(function ($item) {
            return [
                'id_historial' => $item->id_historial,
                'consultorio' => $item->consultorio->ubicacion_consultorio ?? 'No asignado',
                'nombre_medico' => $item->medico->nombre_medico ?? 'No asignado',
                'fecha_uso' => $item->fecha_uso,
                'estatus' => $item->estatus->nombre_estatus ?? 'Sin estatus',
                'estatus_usuario' => $item->estatusUsuario->nombre_usuario ?? 'Sin estatus de usuario',
            ];
        });

        return response()->json($historial);
    }

    // Crear un nuevo registro en el historial de consultorios
    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all());

        if (!is_array($validar)) {
            // Crear historial con los datos validados
            HistorialConsultorio::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Mostrar un registro específico por su ID
    public function show($id)
    {
        $historial = HistorialConsultorio::with(['consultorio', 'medico', 'estatus', 'estatusUsuario'])->find($id);

        if (!$historial) {
            return response()->json(['message' => 'Registro de historial no encontrado'], 404);
        }

        return response()->json($historial);
    }

    // Actualizar un registro de historial existente
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $validar = $this->validarSinId($request->all());

        if (!is_array($validar)) {
            $historial = HistorialConsultorio::find($id);
            if ($historial) {
                $historial->fill($request->all())->save();
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

    // Eliminar un registro de historial
    public function destroy($id)
    {
        $historial = HistorialConsultorio::find($id);

        if (!$historial) {
            return response()->json(['message' => 'Historial de consultorio no encontrado'], 404);
        }

        $historial->delete();
        return response()->json(['message' => 'Historial de consultorio eliminado con éxito']);
    }

    // Validación para la creación de un nuevo registro
    public function validar($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
            'date' => 'El campo :attribute debe ser una fecha válida',
        ];

        $validacion = Validator::make($parametros, [
            'id_historial' => 'required|integer|unique:historial_consultorio,id_historial',
            'id_consultorio' => 'required|string|exists:consultorio,id_consultorio',
            'id_medico' => 'required|string|exists:medico,id_medico',
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

    // Validación para la actualización sin `unique` en `id_historial`
    public function validarSinId($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'date' => 'El campo :attribute debe ser una fecha válida',
        ];

        $validacion = Validator::make($parametros, [
            'id_consultorio' => 'required|string|exists:consultorio,id_consultorio',
            'id_medico' => 'required|string|exists:medico,id_medico',
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

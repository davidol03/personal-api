<?php

namespace App\Http\Controllers;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EspecialidadesController extends Controller
{
    // Mostrar todas las especialidades con el nombre del estatus y el estatus del usuario
    public function index()
    {
        // Obtiene todas las especialidades junto con sus estatus y estatus_usuario
        $especialidades = Especialidad::with(['estatus', 'estatusUsuario'])->get();

        // Mapea el resultado para incluir los nombres de los estatus en lugar de sus IDs
        $especialidades = $especialidades->map(function ($especialidad) {
            return [
                'id_especialidad' => $especialidad->id_especialidad,
                'nombre_especialidad' => $especialidad->nombre_especialidad,
                'descripcion' => $especialidad->descripcion,
                'fecha_modificacion' => $especialidad->fecha_modificacion,
                'estatus' => $especialidad->estatus->nombre_estatus ?? 'Sin estatus', // Nombre del estatus
                'estatus_usuario' => $especialidad->estatusUsuario->nombre_usuario ?? 'Sin estatus de usuario', // Nombre del estatus de usuario
            ];
        });

        return response()->json($especialidades);
    }

    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all());
        if (!is_array($validar)) {
            // Crea la especialidad con los datos validados
            Especialidad::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Mostrar una especialidad específica por su ID
    public function show($id)
    {
        $especialidad = Especialidad::with(['estatus', 'estatusUsuario'])->find($id);

        if (!$especialidad) {
            return response()->json(['message' => 'Especialidad no encontrada'], 404);
        }

        return response()->json($especialidad);
    }

    // Actualizar una especialidad existente
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $validar = $this->validarSinId($request->all()); // Usamos la nueva función sin validación de ID
        if (!is_array($validar)) {
            $especialidad = Especialidad::find($id);
            if ($especialidad) {
                $especialidad->fill($request->all())->save();
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

    // Eliminar una especialidad
    public function destroy($id)
    {
        $especialidad = Especialidad::find($id);

        if (!$especialidad) {
            return response()->json(['message' => 'Especialidad no encontrada'], 404);
        }

        // Elimina la especialidad
        $especialidad->delete();

        return response()->json(['message' => 'Especialidad eliminada con éxito']);
    }

    // Función de validación para crear
    public function validar($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
        ];
        $validacion = Validator::make($parametros, [
            'id_especialidad' => 'required|integer|unique:especialidad,id_especialidad',
            'nombre_especialidad' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
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

    // Nueva función de validación para actualizar, sin la regla 'unique' en id_especialidad
    public function validarSinId($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
        ];
        $validacion = Validator::make($parametros, [
            'nombre_especialidad' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
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

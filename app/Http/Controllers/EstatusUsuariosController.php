<?php

namespace App\Http\Controllers;

use App\Models\EstatusUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstatusUsuariosController extends Controller
{
    // Mostrar todos los estatus de usuario
    public function index()
    {
        $estatusUsuarios = EstatusUsuario::all();
        return response()->json($estatusUsuarios);
    }

    // Crear un nuevo estatus de usuario
    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all()); // Validación para creación
        if (!is_array($validar)) {
            // Crea el estatus de usuario con los datos validados
            EstatusUsuario::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Mostrar un estatus de usuario específico por su ID
    public function show($id)
    {
        $estatusUsuario = EstatusUsuario::find($id);
        if (!$estatusUsuario) {
            return response()->json(['message' => 'Estatus de usuario no encontrado'], 404);
        }
        return response()->json($estatusUsuario);
    }

    // Actualizar un estatus de usuario existente
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $validar = $this->validarSinId($request->all()); // Validación sin ID para actualización
        if (!is_array($validar)) {
            $estatusUsuario = EstatusUsuario::find($id);
            if ($estatusUsuario) {
                $estatusUsuario->fill($request->all())->save();
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

    // Eliminar un estatus de usuario
    public function destroy($id)
    {
        $estatusUsuario = EstatusUsuario::find($id);
        if (!$estatusUsuario) {
            return response()->json(['message' => 'Estatus de usuario no encontrado'], 404);
        }

        // Elimina el estatus de usuario
        $estatusUsuario->delete();
        return response()->json(['message' => 'Estatus de usuario eliminado con éxito']);
    }

    // Función de validación para creación (incluye ID)
    public function validar($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
            'unique' => 'El campo :attribute debe ser único',
        ];
        $validacion = Validator::make($parametros, [
            'id_estatus_usuario' => 'required|integer|unique:estatus_usuario,id_estatus_usuario',
            'nombre_usuario' => 'required|string|max:100',
        ], $messages);

        if ($validacion->fails()) {
            array_push($respuesta, ['status' => 'error']);
            array_push($respuesta, ['errors' => $validacion->errors()]);
            return $respuesta;
        } else {
            return true;
        }
    }

    // Función de validación para actualización (sin validar ID)
    public function validarSinId($parametros)
    {
        $respuesta = [];
        $messages = [
            'required' => 'El campo :attribute NO debe estar vacío',
        ];
        $validacion = Validator::make($parametros, [
            'nombre_usuario' => 'required|string|max:100',
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

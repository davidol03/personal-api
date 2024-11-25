<?php

namespace App\Http\Controllers;

use App\Models\Estatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstatusController extends Controller
{
    // Mostrar todos los estatus
    public function index()
    {
        $estatus = Estatus::all();
        return response()->json($estatus);
    }

    // Crear un nuevo estatus
    public function store(Request $request)
    {
        $respuesta = [];
        $validar = $this->validar($request->all()); // Validación para creación
        if (!is_array($validar)) {
            // Crea el estatus con los datos validados
            Estatus::create($request->all());
            array_push($respuesta, ['status' => 'success']);
            return response()->json($respuesta);
        } else {
            return response()->json($validar);
        }
    }

    // Mostrar un estatus específico por su ID
    public function show($id)
    {
        $estatus = Estatus::find($id);
        if (!$estatus) {
            return response()->json(['message' => 'Estatus no encontrado'], 404);
        }
        return response()->json($estatus);
    }

    // Actualizar un estatus existente
    public function update(Request $request, $id)
    {
        $respuesta = [];
        $validar = $this->validarSinId($request->all()); // Validación sin ID para actualización
        if (!is_array($validar)) {
            $estatus = Estatus::find($id);
            if ($estatus) {
                $estatus->fill($request->all())->save();
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

    // Eliminar un estatus
    public function destroy($id)
    {
        $estatus = Estatus::find($id);
        if (!$estatus) {
            return response()->json(['message' => 'Estatus no encontrado'], 404);
        }

        // Elimina el estatus
        $estatus->delete();
        return response()->json(['message' => 'Estatus eliminado con éxito']);
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
            'id_estatus' => 'required|integer|unique:estatus,id_estatus',
            'nombre_estatus' => 'required|string|max:100',
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
            'nombre_estatus' => 'required|string|max:100',
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

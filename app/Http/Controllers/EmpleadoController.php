<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Http\Response;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Listar todos los empleados
        return Empleado::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar y crear un nuevo empleado
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo_electronico' => 'required|email|unique:empleados,correo_electronico',
            'puesto' => 'required|string|max:255',
        ]);
        $empleado = Empleado::create($validated);
        return response()->json($empleado, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Mostrar un empleado específico
        $empleado = Empleado::findOrFail($id);
        return $empleado;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar y actualizar un empleado
        $empleado = Empleado::findOrFail($id);
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'correo_electronico' => 'sometimes|required|email|unique:empleados,correo_electronico,' . $empleado->id,
            'puesto' => 'sometimes|required|string|max:255',
        ]);
        $empleado->update($validated);
        return response()->json($empleado);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Eliminar un empleado
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Mostrar la vista Blade con el listado de empleados.
     */
    public function vistaEmpleados()
    {
        $empleados = \App\Models\Empleado::paginate(6);
        $hoy = date('Y-m-d');
        // Añadir estado de fichaje a cada empleado
        foreach ($empleados as $empleado) {
            $registro = \App\Models\RegistroAsistencia::where('empleado_id', $empleado->id)
                ->where('fecha', $hoy)
                ->first();
            if (!$registro) {
                $empleado->estado_fichaje = 'ninguno'; // No ha fichado hoy
            } elseif ($registro && !$registro->hora_salida) {
                $empleado->estado_fichaje = 'entrada'; // Ha fichado entrada pero no salida
            } else {
                $empleado->estado_fichaje = 'completo'; // Ha fichado entrada y salida
            }
        }
        // Totales globales
        $totalEmpleados = \App\Models\Empleado::count();
        $empleadosHoy = \App\Models\Empleado::all();
        $totalEntrada = 0;
        $totalCompleto = 0;
        $totalPendiente = 0;
        foreach ($empleadosHoy as $emp) {
            $registro = \App\Models\RegistroAsistencia::where('empleado_id', $emp->id)
                ->where('fecha', $hoy)
                ->first();
            if (!$registro) {
                $totalPendiente++;
            } elseif ($registro && !$registro->hora_salida) {
                $totalEntrada++;
            } else {
                $totalCompleto++;
            }
        }
        return view('empleados', compact('empleados', 'totalEmpleados', 'totalEntrada', 'totalCompleto', 'totalPendiente'));
    }
}

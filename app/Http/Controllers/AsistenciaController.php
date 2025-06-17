<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroAsistencia;
use App\Models\Empleado;
use Illuminate\Http\Response;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    // POST /asistencia/fichar
    public function fichar(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
        ]);
        $empleadoId = $request->empleado_id;
        $hoy = Carbon::today();
        $ahora = Carbon::now();

        $registro = RegistroAsistencia::where('empleado_id', $empleadoId)
            ->where('fecha', $hoy)
            ->first();

        if (!$registro) {
            // Registrar hora de entrada
            $registro = RegistroAsistencia::create([
                'empleado_id' => $empleadoId,
                'fecha' => $hoy,
                'hora_entrada' => $ahora->format('H:i:s'),
            ]);
            return response()->json(['mensaje' => 'Entrada registrada', 'registro' => $registro], Response::HTTP_CREATED);
        } elseif (!$registro->hora_salida) {
            // Registrar hora de salida
            $entrada = Carbon::parse($registro->hora_entrada);
            if ($ahora->lessThanOrEqualTo($entrada)) {
                return response()->json(['error' => 'La hora de salida no puede ser antes o igual que la entrada. Si ves este error, revisa la hora del sistema.'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $horas = $entrada->diffInMinutes($ahora) / 60;
            $registro->hora_salida = $ahora->format('H:i:s');
            $registro->total_horas = round($horas, 2);
            $registro->save();
            return response()->json(['mensaje' => 'Salida registrada', 'registro' => $registro]);
        } else {
            return response()->json(['error' => 'Ya se ha fichado entrada y salida hoy'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // GET /asistencia/historial/{empleadoId}
    public function historial(Request $request, $empleadoId)
    {
        $request->validate([
            'fecha_inicio' => 'nullable|date|before_or_equal:today',
            'fecha_fin' => 'nullable|date|before_or_equal:today|after_or_equal:fecha_inicio',
        ], [
            'fecha_inicio.before_or_equal' => 'La fecha de inicio no puede ser posterior a hoy',
            'fecha_fin.before_or_equal' => 'La fecha final no puede ser posterior a hoy',
            'fecha_fin.after_or_equal' => 'La fecha final debe ser posterior o igual a la fecha de inicio'
        ]);

        $query = RegistroAsistencia::where('empleado_id', $empleadoId);
        
        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }
        
        $historial = $query->orderBy('fecha', 'desc')->get();
        return response()->json($historial);
    }

    // GET /asistencia/resumen/{empleadoId}
    public function resumen(Request $request, $empleadoId)
    {
        $request->validate([
            'mes' => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:2000',
        ]);
        $mes = $request->mes;
        $anio = $request->anio;
        $total = RegistroAsistencia::where('empleado_id', $empleadoId)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->sum('total_horas');
        return response()->json(['total_horas' => round($total, 2)]);
    }

    /**
     * Muestra la vista Blade con el historial de fichajes de un empleado.
     */
    public function vistaHistorial(Request $request, $empleadoId)
    {
        $request->validate([
            'fecha_inicio' => 'nullable|date|before_or_equal:today',
            'fecha_fin' => 'nullable|date|before_or_equal:today|after_or_equal:fecha_inicio',
        ], [
            'fecha_inicio.before_or_equal' => 'La fecha de inicio no puede ser posterior a hoy',
            'fecha_fin.before_or_equal' => 'La fecha final no puede ser posterior a hoy',
            'fecha_fin.after_or_equal' => 'La fecha final debe ser posterior o igual a la fecha de inicio'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'tabla' => '<table class="w-full"><tr><td colspan="5">Prueba OK - AJAX responde</td></tr></table>',
                'paginacion' => '',
            ]);
        }

        $empleado = Empleado::findOrFail($empleadoId);
        $query = RegistroAsistencia::where('empleado_id', $empleadoId);
        
        if ($request->filled('fecha_inicio')) {
            $query->where('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->filled('fecha_fin')) {
            $query->where('fecha', '<=', $request->fecha_fin);
        }
        
        $historial = $query->orderBy('fecha', 'desc')
                          ->paginate(10)
                          ->withQueryString();
        
        return view('historial', compact('empleado', 'historial'));
    }

    /**
     * Muestra la vista Blade con el resumen de horas trabajadas de un empleado.
     */
    public function vistaResumen(Request $request, $empleadoId)
    {
        $empleado = Empleado::findOrFail($empleadoId);
        $mes = (int) $request->input('mes', date('m'));
        $anio = (int) $request->input('anio', date('Y'));
        $total_horas = RegistroAsistencia::where('empleado_id', $empleadoId)
            ->whereMonth('fecha', $mes)
            ->whereYear('fecha', $anio)
            ->sum('total_horas');
        return view('resumen', compact('empleado', 'mes', 'anio', 'total_horas'));
    }

    /**
     * Fichar desde la web y redirigir con mensajes.
     */
    public function ficharWeb(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
        ]);
        $empleadoId = $request->empleado_id;
        $hoy = date('Y-m-d');
        $ahora = date('H:i:s');

        $registro = \App\Models\RegistroAsistencia::where('empleado_id', $empleadoId)
            ->where('fecha', $hoy)
            ->first();

        if (!$registro) {
            // Registrar hora de entrada
            \App\Models\RegistroAsistencia::create([
                'empleado_id' => $empleadoId,
                'fecha' => $hoy,
                'hora_entrada' => $ahora,
            ]);
            return redirect('/')->with('mensaje', 'Entrada registrada correctamente.');
        } elseif (!$registro->hora_salida) {
            $entrada = strtotime($registro->hora_entrada);
            $salida = strtotime($ahora);
            $horas = ($salida - $entrada) / 3600;
            $registro->hora_salida = $ahora;
            $registro->total_horas = round($horas, 2);
            $registro->save();
            return redirect('/')->with('mensaje', 'Salida registrada correctamente.');
        } else {
            return redirect('/')->withErrors(['Ya se ha fichado entrada y salida hoy.']);
        }
    }

    /**
     * Fichar desde AJAX y devolver JSON siempre.
     */
    public function ficharAjax(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
        ]);
        $empleadoId = $request->empleado_id;
        $hoy = date('Y-m-d');
        $ahora = date('H:i:s');

        $registro = \App\Models\RegistroAsistencia::where('empleado_id', $empleadoId)
            ->where('fecha', $hoy)
            ->first();

        if (!$registro) {
            \App\Models\RegistroAsistencia::create([
                'empleado_id' => $empleadoId,
                'fecha' => $hoy,
                'hora_entrada' => $ahora,
            ]);
            return response()->json(['status' => 'success', 'tipo' => 'entrada', 'mensaje' => 'Entrada registrada correctamente.']);
        } elseif (!$registro->hora_salida) {
            $entrada = strtotime($registro->hora_entrada);
            $salida = strtotime($ahora);
            $horas = ($salida - $entrada) / 3600;
            $registro->hora_salida = $ahora;
            $registro->total_horas = round($horas, 2);
            $registro->save();
            return response()->json(['status' => 'success', 'tipo' => 'salida', 'mensaje' => 'Salida registrada correctamente.']);
        } else {
            return response()->json(['status' => 'error', 'mensaje' => 'Ya se ha fichado entrada y salida hoy.'], 422);
        }
    }
}

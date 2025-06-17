<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AsistenciaController;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('empleados', EmpleadoController::class)->names([
    'destroy' => 'empleados.destroy',
]);

// Ruta para ver todos los empleados
Route::get('empleados', [EmpleadoController::class, 'index']);

// Ruta para mostrar la vista Blade de empleados
Route::get('/', [App\Http\Controllers\EmpleadoController::class, 'vistaEmpleados']);

// Ruta para mostrar la vista Blade del historial de fichajes
Route::get('vista/historial/{empleadoId}', [App\Http\Controllers\AsistenciaController::class, 'vistaHistorial']);

// Ruta para mostrar la vista Blade del resumen de horas trabajadas
Route::get('vista/resumen/{empleadoId}', [App\Http\Controllers\AsistenciaController::class, 'vistaResumen']);

// Ruta para fichar desde la web
Route::post('fichar-web', [App\Http\Controllers\AsistenciaController::class, 'ficharWeb']);

// Ruta para fichar por AJAX
Route::post('fichar-ajax', [App\Http\Controllers\AsistenciaController::class, 'ficharAjax']);

// Rutas de fichaje SIN CSRF usando el grupo 'api'
Route::middleware('api')->group(function () {
    Route::post('asistencia/fichar', [AsistenciaController::class, 'fichar']);
    Route::get('asistencia/historial/{empleadoId}', [AsistenciaController::class, 'historial']);
    Route::get('asistencia/resumen/{empleadoId}', [AsistenciaController::class, 'resumen']);
});

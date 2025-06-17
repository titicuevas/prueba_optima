<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RegistroAsistencia;
use Carbon\Carbon;

class RegistroAsistenciaSeeder extends Seeder
{
    public function run(): void
    {
        // Generar registros para los últimos 7 días
        $fechaInicio = Carbon::now()->subDays(7);
        $fechaFin = Carbon::now();

        for ($fecha = $fechaInicio; $fecha <= $fechaFin; $fecha->addDay()) {
            // Para cada empleado (IDs del 1 al 6)
            for ($empleadoId = 1; $empleadoId <= 6; $empleadoId++) {
                // Solo generar registros para días laborables (lunes a viernes)
                if ($fecha->isWeekday()) {
                    // Para días pasados, generar entrada y salida
                    if ($fecha->isPast()) {
                        // Hora de entrada entre 8:00 y 9:00, minutos 00, 15, 30, 45
                        $horaEntrada = Carbon::createFromTime(
                            rand(8, 9),
                            [0, 15, 30, 45][array_rand([0, 15, 30, 45])],
                            0
                        );

                        // Jornada entre 7 y 9 horas, en múltiplos de 15 minutos
                        $jornadaCuartos = rand(28, 36); // 28*15=420min=7h, 36*15=540min=9h
                        $jornadaMinutos = $jornadaCuartos * 15;
                        $horaSalida = (clone $horaEntrada)->addMinutes($jornadaMinutos);

                        // Calcular total de horas y redondear al cuarto de hora (0.25)
                        $horasDecimales = $jornadaMinutos / 60;
                        $totalHoras = round($horasDecimales / 0.25) * 0.25;

                        RegistroAsistencia::create([
                            'empleado_id' => $empleadoId,
                            'fecha' => $fecha->format('Y-m-d'),
                            'hora_entrada' => $horaEntrada->format('H:i:s'),
                            'hora_salida' => $horaSalida->format('H:i:s'),
                            'total_horas' => $totalHoras,
                        ]);
                    } 
                    // Para el día actual, generar solo entrada si es antes de las 16:00
                    else if ($fecha->isToday()) {
                        $horaActual = Carbon::now();
                        if ($horaActual->hour < 16) {
                            $horaEntrada = Carbon::createFromTime(
                                rand(8, min($horaActual->hour, 9)),
                                [0, 15, 30, 45][array_rand([0, 15, 30, 45])],
                                0
                            );
                            RegistroAsistencia::create([
                                'empleado_id' => $empleadoId,
                                'fecha' => $fecha->format('Y-m-d'),
                                'hora_entrada' => $horaEntrada->format('H:i:s'),
                                'hora_salida' => null,
                                'total_horas' => null,
                            ]);
                        } else {
                            $horaEntrada = Carbon::createFromTime(
                                rand(8, 9),
                                [0, 15, 30, 45][array_rand([0, 15, 30, 45])],
                                0
                            );
                            $jornadaCuartos = rand(28, 36);
                            $jornadaMinutos = $jornadaCuartos * 15;
                            $horaSalida = (clone $horaEntrada)->addMinutes($jornadaMinutos);
                            $horasDecimales = $jornadaMinutos / 60;
                            $totalHoras = round($horasDecimales / 0.25) * 0.25;
                            RegistroAsistencia::create([
                                'empleado_id' => $empleadoId,
                                'fecha' => $fecha->format('Y-m-d'),
                                'hora_entrada' => $horaEntrada->format('H:i:s'),
                                'hora_salida' => $horaSalida->format('H:i:s'),
                                'total_horas' => $totalHoras,
                            ]);
                        }
                    }
                }
            }
        }
    }
} 
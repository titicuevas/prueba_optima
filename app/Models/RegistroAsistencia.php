<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroAsistencia extends Model
{
    protected $fillable = [
        'empleado_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'total_horas',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}

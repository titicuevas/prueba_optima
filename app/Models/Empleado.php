<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empleado extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre', 'correo_electronico', 'puesto'];

    public function registros()
    {
        return $this->hasMany(\App\Models\RegistroAsistencia::class, 'empleado_id');
    }
}
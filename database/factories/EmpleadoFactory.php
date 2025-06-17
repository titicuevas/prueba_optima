<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoFactory extends Factory
{
    public function definition(): array
    {
        $nombres = [
            'Maria Garcia', 'Juan Perez', 'Lucia Fernandez', 'Carlos Lopez', 'Ana Torres', 'Miguel Sanchez',
            'Sofia Romero', 'David Martin', 'Elena Ruiz', 'Pablo Ortega', 'Laura Navarro', 'Javier Ramos',
            'Carmen Morales', 'Alberto Castillo', 'Patricia Herrera', 'Raul Delgado', 'Isabel Soto', 'Francisco Molina',
            'Cristina Vargas', 'Alejandro Suarez', 'Beatriz Ramos', 'Manuel Gil', 'Paula Aguirre', 'Sergio Dominguez',
            'Marta Cabrera', 'Victor Campos', 'Nuria Peña', 'Andres Rivas', 'Silvia Lozano', 'Hugo Paredes',
            'Teresa Salas', 'Oscar Cordero', 'Clara Benitez', 'Ruben Pastor', 'Eva Carrillo', 'Adrian Fuentes',
            'Lorena Bravo', 'Ignacio Ponce', 'Monica Duran', 'Guillermo Arias', 'Rosa Zamora', 'Felipe Cano',
            'Natalia Varela', 'Emilio Sanz', 'Irene Parra', 'Pedro Castaño', 'Susana Ferrer', 'Diego Valero',
            'Angela Soler', 'Tomas Barrios'
        ];
        $puestos = [
            'Desarrollador Backend', 'Desarrollador Frontend', 'Analista Funcional', 'Project Manager',
            'QA Tester', 'DevOps Engineer', 'Consultor SAP', 'Scrum Master', 'Arquitecto de Software',
            'Product Owner', 'Soporte Técnico', 'Líder Técnico'
        ];
        $nombre = $this->faker->unique()->randomElement($nombres);
        // Eliminar tildes y caracteres especiales
        $base = strtolower(str_replace([' ', 'ñ'], ['.', 'n'], iconv('UTF-8', 'ASCII//TRANSLIT', $nombre)));
        $base = preg_replace('/[^a-z0-9.]/', '', $base); // Solo letras, números y puntos
        $correo = $base . '@optimagrupo.com';
        $contador = 1;
        while (\App\Models\Empleado::where('correo_electronico', $correo)->exists()) {
            $correo = $base . $contador . '@optimagrupo.com';
            $contador++;
        }
        return [
            'nombre' => $nombre,
            'correo_electronico' => $correo,
            'puesto' => $this->faker->randomElement($puestos),
        ];
    }
}
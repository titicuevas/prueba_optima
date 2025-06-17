SELECT 
    empleados.id AS empleado_id,
    empleados.nombre,
    empleados.correo_electronico,
    empleados.puesto,
    registro_asistencias.fecha,
    registro_asistencias.hora_entrada,
    registro_asistencias.hora_salida,
    registro_asistencias.total_horas
FROM empleados
LEFT JOIN registro_asistencias
    ON empleados.id = registro_asistencias.empleado_id
ORDER BY empleados.nombre ASC, registro_asistencias.fecha DESC;
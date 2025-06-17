document.addEventListener('DOMContentLoaded', function () {
    // Ocultar el mensaje de Laravel si existe (si la petición es AJAX, nunca se mostrará)
    const mensaje = document.getElementById('mensaje-laravel');
    if (mensaje) {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: mensaje.textContent.trim(),
            timer: 1800,
            showConfirmButton: false
        });
        mensaje.style.display = 'none';
    }
    document.querySelectorAll('.fichar-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = form.querySelector('.fichar-btn');
            const empleadoId = form.getAttribute('data-empleado-id');
            const fila = form.closest('tr');
            const badgeEstado = fila.querySelector('.badge');
            // Guardar el estado actual del botón
            const estadoActual = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="animate-spin">⏳</span> Procesando...';
            fetch('/fichar-ajax', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                    'Accept': 'application/json',
                },
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    let titulo = '¡Buen día!';
                    if (data.tipo === 'salida') {
                        titulo = '¡Buen trabajo, hasta mañana!';
                    }
                    Swal.fire({
                        icon: 'success',
                        title: titulo,
                        text: data.mensaje,
                        timer: 1800,
                        showConfirmButton: false
                    });
                    if (data.tipo === 'entrada') {
                        // Cambiar botón a "Fichar salida"
                        btn.innerHTML = '<i class="fa-solid fa-arrow-right-from-bracket"></i> Fichar salida';
                        btn.disabled = false;
                        // Cambiar badge a "Fichado entrada"
                        if (badgeEstado) {
                            badgeEstado.className = 'inline-flex items-center gap-1 bg-yellow-400 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm badge';
                            badgeEstado.innerHTML = '<i class="fa-solid fa-arrow-right-to-bracket"></i> Fichado entrada';
                        }
                        // Actualizar contadores
                        actualizarContadores('entrada');
                    } else if (data.tipo === 'salida') {
                        // Cambiar botón a "Hasta mañana" y deshabilitar
                        btn.innerHTML = '<i class="fa-solid fa-moon"></i> Hasta mañana';
                        btn.className = 'fichar-btn flex items-center gap-1 font-bold py-1.5 px-3 rounded-full shadow bg-gray-200 text-gray-400 cursor-not-allowed text-xs md:text-sm';
                        btn.disabled = true;
                        // Cambiar badge a "Completado"
                        if (badgeEstado) {
                            badgeEstado.className = 'inline-flex items-center gap-1 bg-purple-700 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm badge';
                            badgeEstado.innerHTML = '<i class="fa-solid fa-circle-check"></i> Completado';
                        }
                        // Actualizar contadores
                        actualizarContadores('salida');
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.mensaje || 'Ocurrió un error al fichar.'
                    });
                    btn.disabled = false;
                    btn.innerHTML = estadoActual;
                }
            })
            .catch((error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo conectar con el servidor.'
                });
                btn.disabled = false;
                btn.innerHTML = estadoActual;
            });
        });
    });
    // Función para actualizar los contadores de la cabecera
    function actualizarContadores(tipo) {
        const entrada = document.getElementById('total-entrada');
        const completo = document.getElementById('total-completo');
        const pendiente = document.getElementById('total-pendiente');
        if (tipo === 'entrada') {
            if (entrada) entrada.textContent = parseInt(entrada.textContent) + 1;
            if (pendiente) pendiente.textContent = parseInt(pendiente.textContent) - 1;
        } else if (tipo === 'salida') {
            if (completo) completo.textContent = parseInt(completo.textContent) + 1;
            if (entrada) entrada.textContent = parseInt(entrada.textContent) - 1;
        }
    }
    // Confirmación con SweetAlert para eliminar empleados
    document.querySelectorAll('.eliminar-empleado-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará al empleado. ¡No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario por AJAX para eliminar y recargar la página si es exitoso
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                            'Accept': 'application/json',
                        },
                        body: new FormData(form)
                    })
                    .then(response => {
                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: 'El empleado ha sido eliminado correctamente.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                            setTimeout(() => window.location.reload(), 1600);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'No se pudo eliminar el empleado.'
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo conectar con el servidor.'
                        });
                    });
                }
            });
        });
    });
}); 
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const inicio = form.querySelector('input[name="fecha_inicio"]').value;
            const fin = form.querySelector('input[name="fecha_fin"]').value;
            if (inicio && fin && fin < inicio) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Fechas incorrectas',
                    text: 'La fecha de fin no puede ser anterior a la fecha de inicio.',
                });
            }
        });
    }
});

/*
// --- CÓDIGO AJAX PARA FILTRO DINÁMICO (DESACTIVADO PARA LA DEMO) ---
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetchRecords(page);
    });
    function fetchRecords(page) {
        let url = window.location.pathname;
        let params = new URLSearchParams(window.location.search);
        params.set('page', page);
        $.ajax({
            url: url + '?' + params.toString(),
            type: 'GET',
            beforeSend: function() {
                $('#tablaHistorial').html('<tr><td colspan="5" class="py-4 text-center"><div class="flex justify-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div></div></td></tr>');
            },
            success: function(data) {
                if (data.tabla && data.paginacion) {
                    $('#tablaHistorial').html(data.tabla);
                    $('#paginacionHistorial').html(data.paginacion);
                }
                window.history.pushState({}, '', url + '?' + params.toString());
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al cargar los registros. Por favor, intente nuevamente.'
                });
            }
        });
    }
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        fetchRecords(1);
    });
});
*/ 
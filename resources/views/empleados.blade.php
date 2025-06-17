<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados - Sistema de Fichaje</title>
    @vite(['resources/css/app.css', 'resources/css/custom.css'])
    @vite(['resources/js/empleados.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gradient-to-br from-blue-100 to-purple-200 min-h-screen flex flex-col font-['Inter',sans-serif]">
    <header class="bg-white shadow-md py-4 sticky top-0 z-20">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center px-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-blue-700 tracking-tight mb-1 md:mb-0">Sistema de Fichaje</h1>
            <span class="text-gray-500 font-semibold text-base">Panel de Empleados</span>
        </div>
    </header>
    <main class="flex-1 flex flex-col items-center justify-start w-full">
        <div class="w-full max-w-5xl mx-auto px-2 md:px-4 mt-12">
            @if(session('mensaje'))
                <div id="mensaje-laravel" class="feedback-success">
                    {{ session('mensaje') }}
                </div>
            @endif
            @if($errors->any())
                <div class="feedback-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <div class="flex justify-center w-full mt-10">
                <div class="w-full max-w-5xl flex flex-col items-stretch">
                    <h2 class="text-2xl md:text-3xl font-extrabold py-2 mb-8 text-center text-blue-700 tracking-tight">Listado de Empleados</h2>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 justify-center items-center mb-6 mt-2 text-center">
                <div class="bg-blue-100 text-blue-800 font-bold rounded-xl px-4 py-2 flex items-center gap-2 shadow-sm justify-center">
                    <i class="fa-solid fa-users"></i> Total: <span id="total-empleados">{{ $totalEmpleados }}</span>
                </div>
                <div class="bg-yellow-100 text-yellow-800 font-bold rounded-xl px-4 py-2 flex items-center gap-2 shadow-sm justify-center">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Entrada: <span id="total-entrada">{{ $totalEntrada }}</span>
                </div>
                <div class="bg-purple-100 text-purple-800 font-bold rounded-xl px-4 py-2 flex items-center gap-2 shadow-sm justify-center">
                    <i class="fa-solid fa-circle-check"></i> Completado: <span id="total-completo">{{ $totalCompleto }}</span>
                </div>
                <div class="bg-gray-100 text-gray-800 font-bold rounded-xl px-4 py-2 flex items-center gap-2 shadow-sm justify-center">
                    <i class="fa-solid fa-hourglass-start"></i> Pendiente: <span id="total-pendiente">{{ $totalPendiente }}</span>
                </div>
            </div>
            <div class="flex justify-center w-full mt-0">
                <div class="bg-white rounded-2xl shadow-2xl border border-blue-100 w-full flex flex-col items-stretch my-6 px-2 sm:px-6 lg:px-24 py-6 card-responsive" style="box-shadow: 0 12px 32px 0 rgba(31, 38, 135, 0.15);">
                    <div class="w-full overflow-x-auto px-0 sm:px-6">
                        <table class="min-w-[800px] w-full bg-white text-base table-responsive">
                            <thead>
                                <tr class="bg-gradient-to-r from-blue-50 via-purple-50 to-blue-100 text-blue-900">
                                    <th class="py-3 px-4 text-left font-bold text-base whitespace-nowrap">Nombre</th>
                                    <th class="py-3 px-4 text-left font-bold text-base whitespace-nowrap">Estado</th>
                                    <th class="py-3 px-4 text-left font-bold text-base whitespace-nowrap">Correo Electrónico</th>
                                    <th class="py-3 px-4 text-left font-bold text-base whitespace-nowrap">Puesto</th>
                                    <th class="py-3 px-4 font-bold text-base whitespace-nowrap col-acciones text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($empleados as $empleado)
                                <tr class="bg-white hover:bg-blue-50 transition border-b border-blue-50 last:border-b-0">
                                    <td class="py-2 px-4 font-semibold whitespace-nowrap text-gray-800 text-base">{{ $empleado->nombre }}</td>
                                    <td class="py-2 px-4 text-center">
                                        @if($empleado->estado_fichaje == 'ninguno')
                                            <span class="inline-flex items-center gap-1 bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm badge"><i class="fa-solid fa-hourglass-start"></i> Pendiente</span>
                                        @elseif($empleado->estado_fichaje == 'entrada')
                                            <span class="inline-flex items-center gap-1 bg-yellow-400 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm badge"><i class="fa-solid fa-arrow-right-to-bracket"></i> Fichado entrada</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-purple-700 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm badge"><i class="fa-solid fa-circle-check"></i> Completado</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 whitespace-nowrap text-gray-700">{{ $empleado->correo_electronico }}</td>
                                    <td class="py-2 px-4 whitespace-nowrap text-gray-700">{{ $empleado->puesto }}</td>
                                    <td class="py-2 px-4 whitespace-nowrap col-acciones text-center">
                                        <div class="flex flex-row gap-1 md:gap-2 justify-center items-center">
                                            <form action="/fichar-web" method="POST" class="inline fichar-form" data-empleado-id="{{ $empleado->id }}">
                                                @csrf
                                                <input type="hidden" name="empleado_id" value="{{ $empleado->id }}">
                                                @if($empleado->estado_fichaje == 'ninguno')
                                                    <button type="submit"
                                                        class="fichar-btn hover-scale flex items-center gap-1 font-bold py-1.5 px-3 rounded-full shadow-md transition text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 hover:scale-105 duration-150 bg-gradient-to-r from-blue-500 to-purple-500 text-white hover:from-blue-600 hover:to-purple-600"
                                                        title="Fichar entrada"
                                                        role="button"
                                                        aria-label="Fichar entrada">
                                                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                                        Fichar entrada
                                                    </button>
                                                @elseif($empleado->estado_fichaje == 'entrada')
                                                    <button type="submit"
                                                        class="fichar-btn hover-scale flex items-center gap-1 font-bold py-1.5 px-3 rounded-full shadow-md transition text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 hover:scale-105 duration-150 bg-gradient-to-r from-blue-500 to-purple-500 text-white hover:from-blue-600 hover:to-purple-600"
                                                        title="Fichar salida"
                                                        role="button"
                                                        aria-label="Fichar salida">
                                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                                        Fichar salida
                                                    </button>
                                                @else
                                                    <button type="button" class="fichar-btn flex items-center gap-1 font-bold py-1.5 px-3 rounded-full shadow bg-gray-200 text-gray-400 cursor-not-allowed text-xs md:text-sm" disabled title="Hasta mañana">
                                                        <i class="fa-solid fa-moon"></i>
                                                        Hasta mañana
                                                    </button>
                                                @endif
                                            </form>
                                            <a href="/vista/historial/{{ $empleado->id }}" 
                                               class="hover-scale flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white font-bold py-1.5 px-3 rounded-full shadow-md transition text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-green-400 hover:scale-105 duration-150" 
                                               title="Ver historial de fichajes"
                                               role="link"
                                               aria-label="Ver historial de fichajes de {{ $empleado->nombre }}">
                                                <i class="fa-solid fa-clock-rotate-left"></i>
                                                Historial
                                            </a>
                                            <a href="/vista/resumen/{{ $empleado->id }}?mes={{ date('m') }}&anio={{ date('Y') }}" 
                                               class="hover-scale flex items-center gap-1 bg-purple-500 hover:bg-purple-600 text-white font-bold py-1.5 px-3 rounded-full shadow-md transition text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 hover:scale-105 duration-150" 
                                               title="Ver resumen de horas"
                                               role="link"
                                               aria-label="Ver resumen de horas de {{ $empleado->nombre }}">
                                                <i class="fa-solid fa-chart-column"></i>
                                                Resumen
                                            </a>
                                            <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" class="inline eliminar-empleado-form ml-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="hover-scale flex items-center gap-1 bg-red-500 hover:bg-red-700 text-white font-bold py-1.5 px-3 rounded-full shadow-md transition text-xs md:text-sm focus:outline-none focus:ring-2 focus:ring-red-400 hover:scale-105 duration-150" title="Eliminar empleado">
                                                    <i class="fa-solid fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($empleados->lastPage() > 1)
                    <div class="flex flex-col items-center justify-center mt-8 mb-2">
                        <div class="flex justify-center gap-4 pb-0">
                            <a href="{{ $empleados->previousPageUrl() }}" class="px-8 py-2 rounded-full bg-blue-600 text-white font-bold shadow hover:bg-blue-800 transition disabled:opacity-50 disabled:cursor-not-allowed @if($empleados->onFirstPage()) opacity-50 cursor-not-allowed pointer-events-none @endif" @if($empleados->onFirstPage()) disabled aria-disabled="true" tabindex="-1" @endif>Anterior</a>
                            <a href="{{ $empleados->nextPageUrl() }}" class="px-8 py-2 rounded-full bg-blue-600 text-white font-bold shadow hover:bg-blue-800 transition disabled:opacity-50 disabled:cursor-not-allowed @if(!$empleados->hasMorePages()) opacity-50 cursor-not-allowed pointer-events-none @endif" @if(!$empleados->hasMorePages()) disabled aria-disabled="true" tabindex="-1" @endif>Siguiente</a>
                        </div>
                        <div class="flex justify-center mt-2 text-gray-600 text-base">Página {{ $empleados->currentPage() }} de {{ $empleados->lastPage() }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <footer class="w-full mt-auto py-4 bg-gradient-to-r from-blue-900 to-purple-900 shadow-inner text-center text-white text-base rounded-t-3xl flex flex-col items-center gap-2 border-t border-blue-800">
        <div class="flex flex-col items-center gap-1 justify-center mb-1">
            <span class="font-semibold text-2xl tracking-wide flex items-center gap-2">
                <svg xmlns='http://www.w3.org/2000/svg' class='h-8 w-8 text-blue-300 opacity-90' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6 0a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>
                Sistema de Fichaje
            </span>
        </div>
        <span class="opacity-90 text-sm">&copy; {{ date('Y') }}. Desarrollado para entrevista técnica.</span>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Ocultar el mensaje de Laravel si existe
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
        });
    </script>
    <style>
    .resaltado-fila {
        background-color: #fef9c3 !important;
        transition: background-color 0.7s;
    }
    </style>
</body>
</html> 
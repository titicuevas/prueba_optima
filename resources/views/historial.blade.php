<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Fichajes</title>
    @vite(['resources/css/app.css', 'resources/css/custom.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/js/historial.js'])
</head>
<body class="bg-gradient-to-br from-blue-100 to-purple-200 min-h-screen flex flex-col">
    <header class="bg-white shadow-md py-4 sticky top-0 z-10">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center px-4">
            <h1 class="text-2xl md:text-3xl font-extrabold text-blue-700 tracking-tight mb-1 md:mb-0">Historial de Fichajes</h1>
            <span class="text-gray-500 font-semibold text-sm md:text-base">{{ $empleado->nombre }}</span>
        </div>
    </header>
    <main class="flex-1 flex flex-col items-center justify-start w-full">
        <div class="w-full max-w-3xl mx-auto px-2 md:px-4 mt-6">
            @if(session('mensaje'))
                <div class="feedback-success">
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
            <form method="GET" action="" class="mb-6 flex flex-wrap justify-center gap-4 text-center" id="filterForm">
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="border rounded px-2 py-1" max="{{ date('Y-m-d') }}">
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="border rounded px-2 py-1" max="{{ date('Y-m-d') }}">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-lg shadow">Filtrar</button>
                <a href="/" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-4 rounded-lg shadow transition">Volver</a>
            </form>
            <div class="bg-white rounded-2xl shadow-2xl border border-blue-100 w-full flex flex-col items-stretch my-6 px-2 sm:px-6 lg:px-24 py-6 card-responsive" style="box-shadow: 0 12px 32px 0 rgba(31, 38, 135, 0.15);">
                <div class="mb-6">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-blue-700 tracking-tight text-center">Historial de Fichajes</h2>
                </div>
                <div class="w-full overflow-x-auto">
                    <div id="tablaHistorial">
                        @if($historial->count() > 0)
                        <table class="w-full bg-white text-xs md:text-sm table-responsive">
                            <thead>
                                <tr class="bg-gradient-to-r from-blue-50 via-purple-50 to-blue-100 text-blue-900">
                                    <th class="py-1 px-1 md:py-2 md:px-2 text-left font-bold whitespace-nowrap">Fecha</th>
                                    <th class="py-1 px-1 md:py-2 md:px-2 text-left font-bold whitespace-nowrap">Hora de Entrada</th>
                                    <th class="py-1 px-1 md:py-2 md:px-2 text-left font-bold whitespace-nowrap">Hora de Salida</th>
                                    <th class="py-1 px-1 md:py-2 md:px-2 text-left font-bold whitespace-nowrap">Total Horas</th>
                                    <th class="py-1 px-1 md:py-2 md:px-2 text-left font-bold whitespace-nowrap">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historial as $registro)
                                <tr class="bg-white hover:bg-blue-50 transition border-b border-blue-50 last:border-b-0">
                                    <td class="py-0.5 px-1 md:py-1 md:px-2 whitespace-nowrap text-gray-800">{{ \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') }}</td>
                                    <td class="py-0.5 px-1 md:py-1 md:px-2 whitespace-nowrap text-gray-800">{{ $registro->hora_entrada }}</td>
                                    <td class="py-0.5 px-1 md:py-1 md:px-2 whitespace-nowrap text-gray-800">{{ $registro->hora_salida }}</td>
                                    <td class="py-0.5 px-1 md:py-1 md:px-2 whitespace-nowrap text-gray-800">
                                        @if(!is_null($registro->total_horas))
                                            @php
                                                $horas = floor($registro->total_horas);
                                                $minutos = ($registro->total_horas - $horas) * 60;
                                            @endphp
                                            {{ $horas }}h {{ sprintf('%02d', $minutos) }}min
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-0.5 px-1 md:py-1 md:px-2 whitespace-nowrap text-center">
                                        @if($registro->hora_salida)
                                            <span class="inline-flex items-center gap-1 bg-purple-700 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm badge">Completado</span>
                                        @else
                                            <span class="inline-flex items-center gap-1 bg-yellow-400 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-sm badge">Entrada</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="w-full flex flex-col items-center justify-center py-12">
                            <span class="text-xl font-bold text-blue-700 mb-2">No hay registros de fichaje para el rango de fechas seleccionado.</span>
                            <span class="text-base text-gray-500">Prueba con otro rango de fechas.</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mt-4" id="paginacionHistorial">
                    {{ $historial->links() }}
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
</body>
</html> 
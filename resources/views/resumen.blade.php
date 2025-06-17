<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Horas Trabajadas</title>
    @vite(['resources/css/app.css', 'resources/css/custom.css'])
    @vite(['resources/js/resumen.js'])
</head>
<body class="bg-gradient-to-br from-blue-100 to-purple-200 min-h-screen flex flex-col">
    <header class="bg-white shadow-md py-4 sticky top-0 z-10">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center px-4">
            <h1 class="text-2xl md:text-3xl font-extrabold text-blue-700 tracking-tight mb-1 md:mb-0">Resumen de Horas</h1>
            <span class="text-gray-500 font-semibold text-sm md:text-base">{{ $empleado->nombre }}</span>
        </div>
    </header>
    <main class="flex-1 flex flex-col items-center justify-start w-full">
        <div class="w-full max-w-2xl mx-auto px-2 md:px-4 mt-6">
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
            <form method="GET" action="" class="mb-6 flex flex-wrap justify-center gap-4 text-center">
                @php
                    $anioActual = date('Y');
                    $mesActual = date('n');
                    $anioSeleccionado = request('anio', $anio);
                    $maxMes = ($anioSeleccionado == $anioActual) ? $mesActual : 12;
                @endphp
                <select name="mes" class="border rounded px-2 py-1">
                    @for($i = 1; $i <= $maxMes; $i++)
                        <option value="{{ $i }}" @if(request('mes', $mes) == $i) selected @endif>{{ $i }}</option>
                    @endfor
                </select>
                <select name="anio" class="border rounded px-2 py-1">
                    @for($y = date('Y')-5; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" @if(request('anio', $anio) == $y) selected @endif>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-lg shadow">Consultar</button>
                <a href="/" class="ml-2 bg-gray-400 hover:bg-gray-600 text-white font-bold py-1 px-4 rounded-lg shadow">Volver</a>
            </form>
            <div class="bg-white rounded-2xl shadow-xl p-6 text-center card-responsive">
                <p class="text-xl">Total de horas trabajadas en <span class="font-bold">{{ $mes }}/{{ $anio }}</span>:</p>
                @php
                    $horas = floor($total_horas);
                    $minutos = round(($total_horas - $horas) * 60);
                @endphp
                <p class="text-4xl font-extrabold mt-2 text-blue-700">
                    {{ $horas }} horas y {{ $minutos }} minutos
                </p>
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
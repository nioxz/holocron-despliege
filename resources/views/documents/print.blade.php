<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento #{{ $document->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 1cm; size: A4; }
        }
        
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; }
        
        .table-bordered { 
            width: 100%; 
            border-collapse: collapse; 
            border: 1px solid #000;
        }
        
        .table-bordered th, .table-bordered td { 
            border: 1px solid #000; 
            padding: 4px 6px; 
            vertical-align: middle;
        }
        
        .header-cell { 
            background-color: #e5e7eb !important; 
            font-weight: bold; 
            text-transform: uppercase;
            font-size: 10px;
        }
        
        .title-cell { 
            font-size: 16px; 
            font-weight: bold; 
            text-align: center; 
            text-transform: uppercase;
        }

        .signature-box {
            height: 60px;
            border-bottom: 1px solid #000;
            margin-bottom: 4px;
        }
    </style>
</head>
<body class="bg-white text-black" onload="window.print()">

    <table class="table-bordered mb-4">
        <tr>
            <td rowspan="3" class="w-32 text-center p-2">
                <img src="{{ asset('img/logo-transparente.png') }}" style="max-height: 50px; max-width: 100px; margin: 0 auto;">
            </td>
            <td rowspan="3" class="title-cell">
                @if($document->type === 'IPERC')
                    MATRIZ DE IDENTIFICACIÓN DE PELIGROS, EVALUACIÓN DE RIESGOS Y CONTROLES (IPERC)
                @elseif($document->type === 'ATS')
                    ANÁLISIS DE TRABAJO SEGURO (ATS)
                @elseif(str_contains($document->type, 'Checklist'))
                    CHECKLIST DE INSPECCIÓN: {{ str_replace('Checklist ', '', $document->type) }}
                @else
                    {{ strtoupper($document->type) }}
                @endif
            </td>
            <td class="header-cell w-24">CÓDIGO:</td>
            <td class="w-32 text-center">SST-{{ strtoupper(substr($document->type, 0, 3)) }}-{{ str_pad($document->id, 4, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="header-cell">VERSIÓN:</td>
            <td class="text-center">01</td>
        </tr>
        <tr>
            <td class="header-cell">FECHA:</td>
            <td class="text-center">{{ $document->created_at->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table class="table-bordered mb-4">
        <tr>
            <td class="header-cell w-32">ACTIVIDAD / TAREA:</td>
            <td colspan="3">{{ $document->content['actividad'] ?? $document->content['trabajo'] ?? $document->content['tarea'] ?? '-' }}</td>
            <td class="header-cell w-32">LUGAR / ÁREA:</td>
            <td>{{ $document->content['lugar'] ?? $document->content['area'] ?? '-' }}</td>
        </tr>
        <tr>
            <td class="header-cell">RESPONSABLE:</td>
            <td colspan="3">{{ $document->user->name }} {{ $document->user->surname ?? '' }}</td>
            <td class="header-cell">HORA:</td>
            <td>{{ $document->created_at->format('H:i') }}</td>
        </tr>
        <tr>
            <td class="header-cell">ESTADO:</td>
            <td colspan="5">
                <span class="font-bold uppercase px-2 py-1 rounded text-xs 
                    {{ $document->status == 'Aprobado' ? 'border border-green-600 text-green-800' : ($document->status == 'Rechazado' ? 'border border-red-600 text-red-800' : 'border border-gray-400') }}">
                    {{ $document->status }}
                </span>
            </td>
        </tr>
    </table>

    @if($document->type === 'IPERC')
        <div class="mb-4">
            <h3 class="font-bold text-xs mb-1 uppercase border-b border-black">Análisis de Riesgos</h3>
            <table class="table-bordered">
                <tr class="header-cell">
                    <th class="w-1/4">PELIGRO</th>
                    <th class="w-1/4">RIESGO</th>
                    <th class="w-1/12">EVAL.</th>
                    <th class="w-1/3">MEDIDAS DE CONTROL</th>
                </tr>
                <tr>
                    <td>{{ $document->content['peligro'] ?? '-' }}</td>
                    <td>{{ $document->content['riesgo'] ?? '-' }}</td>
                    
                    @php
                        $nivel = $document->content['evaluacion']['nivel'] ?? '';
                        $bg_color = '';
                        if(str_contains($nivel, 'ALTO')) $bg_color = 'background-color: #ef4444; color: white;';
                        elseif(str_contains($nivel, 'MEDIO')) $bg_color = 'background-color: #eab308;';
                        elseif(str_contains($nivel, 'BAJO')) $bg_color = 'background-color: #22c55e; color: white;';
                    @endphp
                    <td class="text-center font-bold" style="{{ $bg_color }}">{{ $nivel }}</td>
                    
                    <td>{{ $document->content['medidas'] ?? '-' }}</td>
                </tr>
            </table>
            
            <div class="mt-4 flex justify-center">
                <div class="text-center">
                    <p class="text-[9px] font-bold text-gray-500 mb-1">REFERENCIA DE EVALUACIÓN APLICADA:</p>
                    <img src="{{ asset('img/matriz.png') }}" style="max-width: 500px; border: 1px solid #ccc;">
                </div>
            </div>
        </div>

    @elseif($document->type === 'ATS')
        <div class="mb-4">
            <h3 class="font-bold text-xs mb-1 uppercase border-b border-black">Paso a Paso de la Tarea</h3>
            <table class="table-bordered">
                <tr class="header-cell">
                    <th class="w-10">N°</th>
                    <th>PASOS DE LA TAREA</th>
                    <th>PELIGROS / RIESGOS</th>
                    <th>MEDIDAS DE CONTROL</th>
                </tr>
                @if(isset($document->content['pasos']) && is_array($document->content['pasos']))
                    @foreach($document->content['pasos'] as $index => $paso)
                    <tr>
                        <td class="text-center font-bold">{{ $index + 1 }}</td>
                        <td>{{ $paso['paso'] }}</td>
                        <td>{{ $paso['peligro'] }}</td>
                        <td>{{ $paso['control'] }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr><td colspan="4" class="text-center">No se registraron pasos.</td></tr>
                @endif
            </table>

            @if(isset($document->content['epps']))
            <div class="mt-2 border border-black p-2 text-xs">
                <span class="font-bold">EPPs REQUERIDOS:</span> 
                {{ implode(', ', $document->content['epps']) }}
            </div>
            @endif
        </div>

    @elseif(str_contains($document->type, 'Checklist'))
        <div class="mb-4">
            <h3 class="font-bold text-xs mb-1 uppercase border-b border-black">Puntos de Inspección</h3>
            <table class="table-bordered">
                <tr class="header-cell">
                    <th class="w-1/2">ÍTEM</th>
                    <th class="w-20">ESTADO</th>
                    <th>OBSERVACIONES</th>
                </tr>
                @if(isset($document->content['checks']))
                    @foreach($document->content['checks'] as $item => $estado)
                    <tr>
                        <td>
                            {{ is_array($estado) && isset($estado['name']) ? $estado['name'] : $item }}
                        </td>
                        <td class="text-center font-bold">
                            @php
                                $status = is_array($estado) ? ($estado['status'] ?? '-') : $estado;
                                $color = $status === 'Mal' ? 'color: red;' : ($status === 'Bien' ? 'color: green;' : '');
                            @endphp
                            <span style="{{ $color }}">{{ $status }}</span>
                        </td>
                        <td>
                            {{ is_array($estado) ? ($estado['comment'] ?? '') : '' }}
                        </td>
                    </tr>
                    @endforeach
                @endif
            </table>
        </div>

    @elseif($document->type === 'PETAR')
        <div class="mb-4 border-2 border-red-600 p-2 rounded">
            <h3 class="text-center text-red-700 font-bold text-sm uppercase mb-2">PERMISO DE ALTO RIESGO: {{ $document->content['tipo_trabajo'] ?? 'GENERAL' }}</h3>
            
            <table class="table-bordered mb-2">
                <tr>
                    <td class="header-cell w-32">HORARIO:</td>
                    <td>{{ $document->content['horario'] ?? '-' }}</td>
                    <td class="header-cell w-32">LUGAR EXACTO:</td>
                    <td>{{ $document->content['lugar'] ?? '-' }}</td>
                </tr>
            </table>

            <div class="grid grid-cols-3 gap-4 mt-8 mb-4 text-center">
                <div>
                    <div class="signature-box"></div>
                    <p class="text-[9px] font-bold">RESPONSABLE TRABAJO</p>
                    <p class="text-[8px]">{{ $document->content['autorizaciones']['responsable'] ?? '' }}</p>
                </div>
                <div>
                    <div class="signature-box"></div>
                    <p class="text-[9px] font-bold">SUPERVISOR ÁREA</p>
                    <p class="text-[8px]">{{ $document->content['autorizaciones']['supervisor'] ?? '' }}</p>
                </div>
                <div>
                    <div class="signature-box"></div>
                    <p class="text-[9px] font-bold">INGENIERO SEGURIDAD</p>
                    <p class="text-[8px]">{{ $document->content['autorizaciones']['hse'] ?? '' }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(isset($document->content['participantes']) && is_array($document->content['participantes']) && count($document->content['participantes']) > 0)
    <div class="mb-4 avoid-break">
        <h4 class="font-bold text-xs mb-1 uppercase bg-gray-200 p-1">Personal Involucrado (Cuadrilla)</h4>
        <table class="table-bordered">
            <tr class="header-cell">
                <th class="w-10">N°</th>
                <th>APELLIDOS Y NOMBRES</th>
                <th class="w-32">DNI</th>
                <th class="w-40">FIRMA</th>
            </tr>
            @foreach($document->content['participantes'] as $index => $persona)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ is_array($persona) ? ($persona['nombre'] ?? '-') : $persona }}</td>
                <td class="text-center">{{ is_array($persona) ? ($persona['dni'] ?? '-') : '-' }}</td>
                <td></td> </tr>
            @endforeach
        </table>
    </div>
    @endif

    @if(isset($document->content['observaciones']) && $document->content['observaciones'])
    <div class="mb-4 border border-black p-2">
        <p class="font-bold text-xs underline">OBSERVACIONES ADICIONALES:</p>
        <p class="text-xs">{{ $document->content['observaciones'] }}</p>
    </div>
    @endif

    <div class="mt-8 pt-4">
        <table class="w-full">
            <tr>
                <td class="w-1/2 px-8 text-center">
                    <div class="signature-box"></div>
                    <p class="font-bold text-xs uppercase">{{ $document->user->name }} {{ $document->user->surname ?? '' }}</p>
                    <p class="text-[9px]">ELABORADO POR</p>
                    <p class="text-[8px] text-gray-500">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                </td>
                <td class="w-1/2 px-8 text-center">
                    <div class="signature-box relative">
                        @if($document->status == 'Aprobado')
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Sello_aprobado.svg/1200px-Sello_aprobado.svg.png" 
                                 class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-2 opacity-20 w-32">
                        @endif
                    </div>
                    <p class="font-bold text-xs uppercase">{{ $document->supervisor->name ?? 'PENDIENTE DE REVISIÓN' }}</p>
                    <p class="text-[9px]">VISTO BUENO / SUPERVISOR</p>
                    @if($document->supervisor_comments)
                        <p class="text-[9px] italic mt-1">"{{ $document->supervisor_comments }}"</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="fixed bottom-0 left-0 w-full text-center border-t border-gray-400 pt-1">
        <p class="text-[8px] text-gray-500">
            Documento generado digitalmente por plataforma HOLOCRON | ID Único: {{ $document->id }} | Fecha Impresión: {{ date('d/m/Y H:i') }}
        </p>
    </div>

    <div class="no-print fixed bottom-5 right-5 flex gap-2">
        <button onclick="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded shadow font-bold hover:bg-gray-600">
            Cerrar
        </button>
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow font-bold hover:bg-blue-700">
            🖨️ Imprimir
        </button>
    </div>

</body>
</html>
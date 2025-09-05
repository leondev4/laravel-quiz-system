<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Resultados del Quiz: {{ $test->quiz->title }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Completado el {{ $test->created_at->setTimezone('America/Mexico_City')->format('d \d\e F, Y \a \l\a\s g:i A') }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $percentage = ($test->result / $questions_count) * 100;
                    $statusConfig = [
                        'excellent' => ['bg-green-100', 'text-green-800', '🏆'],
                        'good' => ['bg-blue-100', 'text-blue-800', '👍'],
                        'fair' => ['bg-yellow-100', 'text-yellow-800', '⚠️'],
                        'poor' => ['bg-red-100', 'text-red-800', '❌']
                    ];
                    
                    $status = $percentage >= 90 ? 'excellent' : 
                             ($percentage >= 75 ? 'good' : 
                             ($percentage >= 60 ? 'fair' : 'poor'));
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusConfig[$status][0] }} {{ $statusConfig[$status][1] }}">
                    {{ $statusConfig[$status][2] }} {{ number_format($percentage, 1) }}%
                </span>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        Resultados - {{ $test->quiz->title }}
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Resumen de Resultados - Layout Horizontal -->
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-16">
                    
                    <!-- Resultado Principal -->
                    <div class="flex items-center space-x-8">
                        <div class="space-y-2">
                            <h1 class="text-4xl font-bold text-gray-900">
                                {{ $test->result }}/{{ $questions_count }}
                            </h1>
                            <p class="text-xl text-gray-600">
                                {{ number_format($percentage, 1) }}% de aciertos
                            </p>
                        </div>
                    </div>

                    <!-- Estadísticas en fila -->
                    <div class="flex flex-wrap gap-12 lg:gap-16">
                        <div class="text-center bg-green-50 rounded-lg p-4 min-w-24">
                            <div class="text-2xl font-bold text-green-600 mb-2">{{ $test->result }}</div>
                            <div class="text-sm text-gray-600 whitespace-nowrap">Correctas</div>
                        </div>
                        <div class="text-center bg-red-50 rounded-lg p-4 min-w-24">
                            <div class="text-2xl font-bold text-red-600 mb-2">{{ $questions_count - $test->result }}</div>
                            <div class="text-sm text-gray-600 whitespace-nowrap">Incorrectas</div>
                        </div>
                        <div class="text-center bg-blue-50 rounded-lg p-4 min-w-24">
                            <div class="text-2xl font-bold text-blue-600 mb-2">
                                {{ $test->time_spent ? sprintf('%.1f', $test->time_spent / 60) : 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-600 whitespace-nowrap">Minutos</div>
                        </div>
                        <div class="text-center bg-purple-50 rounded-lg p-4 min-w-24">
                            <div class="text-2xl font-bold text-purple-600 mb-2">{{ $questions_count }}</div>
                            <div class="text-sm text-gray-600 whitespace-nowrap">Total</div>
                        </div>
                    </div>

                    <!-- Información adicional en horizontal -->
                    <div class="flex flex-col space-y-4 min-w-48">
                        @if (auth()->user()?->is_admin)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg px-6 py-4">
                                <div class="text-sm font-medium text-blue-900 mb-1">Usuario</div>
                                <div class="text-sm text-blue-700">{{ $test->user->name ?? 'Anónimo' }}</div>
                            </div>
                        @endif
                        
                        <div class="bg-green-50 border border-green-200 rounded-lg px-6 py-4">
                            <div class="text-sm font-medium text-green-900 mb-1">Fecha</div>
                            <div class="text-sm text-green-700">
                                {{ $test->created_at->setTimezone('America/Mexico_City')->format('d/m/Y g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revisión de Preguntas y Respuestas -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Revisión de Preguntas</h2>
                    <span class="text-sm text-gray-500">{{ $results->count() }} preguntas</span>
                </div>

                <div class="space-y-6">
                    @foreach ($results as $result)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <!-- Encabezado de la pregunta -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <span class="bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1 rounded-full mr-3">
                                            Pregunta {{ $loop->iteration }}
                                        </span>
                                        
                                        <!-- Indicador si no respondió -->
                                        @if(is_null($result->option_id))
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Sin responder
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 katex-content">
                                        {!! $result->question->text !!}
                                    </h3>
                                </div>
                            </div>

                            <!-- Código de la pregunta si existe -->
                            @if($result->question->code_snippet)
                                <div class="mb-4">
                                    <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                                        <pre class="text-green-400 text-sm"><code>{{ $result->question->code_snippet }}</code></pre>
                                    </div>
                                </div>
                            @endif

                            <!-- Todas las opciones de respuesta -->
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700 mb-3">Opciones de respuesta:</h4>
                                
                                <div class="space-y-2">
                                    @foreach($result->question->options as $option)
                                        @php
                                            $isSelected = $result->option_id == $option->id;
                                        @endphp
                                        <div class="p-3 rounded-lg border {{ $isSelected ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mt-1 mr-3">
                                                    @if($isSelected)
                                                        <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-5 h-5 bg-gray-300 rounded-full flex items-center justify-center">
                                                            <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <span class="katex-content {{ $isSelected ? 'text-green-800 font-medium' : 'text-gray-700' }}">
                                                        {!! $option->text !!}
                                                    </span>
                                                    @if($isSelected)
                                                        <span class="ml-2 text-xs text-green-600 font-medium">(Tu respuesta)</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Mensaje si no respondió -->
                                @if(is_null($result->option_id))
                                    <div class="mt-3 p-3 rounded-lg bg-yellow-50 border border-yellow-200">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-yellow-800 font-medium">No respondiste esta pregunta</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Explicación de la respuesta (opcional) -->
                            @if ($result->question->answer_explanation || $result->question->more_info_link)
                                <div class="border-t border-gray-200 pt-4">
                                    @if($result->question->answer_explanation)
                                        <div class="mb-3">
                                            <h4 class="font-medium text-gray-700 mb-2">💡 Explicación:</h4>
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                <p class="text-blue-800 katex-content">{!! $result->question->answer_explanation !!}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($result->question->more_info_link)
                                        <div>
                                            <h4 class="font-medium text-gray-700 mb-2">🔗 Más información:</h4>
                                            <a href="{{ $result->question->more_info_link }}" 
                                               target="_blank" 
                                               class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                {{ $result->question->more_info_link }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Volver al inicio
                </a>
                
                <a href="{{ route('myresults') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Ver todos mis resultados
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Configuración de KaTeX para ecuaciones matemáticas
        let katexOpts = {
            delimiters: [{
                    left: "$$",
                    right: "$$",
                    display: true
                }, 
                {
                    left: "\\[",
                    right: "\\]",
                    display: true
                }, 
                {
                    left: "$",
                    right: "$",
                    display: false
                }, 
                {
                    left: "\\(",
                    right: "\\)",
                    display: false
                }
            ],
            throwOnError: false,
            errorColor: '#cc0000',
            strict: false
        };

        // Renderizar KaTeX cuando la página se carga
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof renderMathInElement !== 'undefined') {
                document.querySelectorAll('.katex-content').forEach(function(element) {
                    renderMathInElement(element, katexOpts);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>

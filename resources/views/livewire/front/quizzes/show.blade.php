{{-- <?php --}}
<div x-data="{ 
    secondsLeft: {{ $this->getCurrentQuestionDuration() }},
    originalDuration: {{ $this->getCurrentQuestionDuration() }},
    questionIndex: {{ $currentQuestionIndex }},
    timerInterval: null,
    katexRendered: false
}" 
x-init="
    console.log('Iniciando timer con:', secondsLeft, 'segundos');
    
    // Función para renderizar KaTeX
    const renderKatex = () => {
        setTimeout(() => {
            console.log('Renderizando KaTeX...');
            if (typeof renderMathInElement !== 'undefined') {
                document.querySelectorAll('.katex-content:not([data-katex-done])').forEach(function(element) {
                    console.log('Procesando elemento:', element);
                    try {
                        renderMathInElement(element, {
                            delimiters: [
                                {left: '$$', right: '$$', display: true},
                                {left: '\\[', right: '\\]', display: true},
                                {left: '$', right: '$', display: false},
                                {left: '\\(', right: '\\)', display: false}
                            ],
                            throwOnError: false,
                            errorColor: '#cc0000'
                        });
                        element.setAttribute('data-katex-done', 'true');
                        element.setAttribute('wire:ignore', '');
                        console.log('KaTeX renderizado y protegido');
                    } catch (e) {
                        console.error('Error renderizando KaTeX:', e);
                    }
                });
                katexRendered = true;
            } else {
                console.warn('renderMathInElement no disponible');
            }
        }, 300);
    };
    
    // Función para iniciar el contador
    const startCountdown = () => {
        console.log('Iniciando countdown...');
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        
        timerInterval = setInterval(() => {
            if (secondsLeft > 1) { 
                secondsLeft = secondsLeft - 1; 
            } else {
                console.log('Tiempo terminado, siguiente pregunta');
                clearInterval(timerInterval);
                $wire.nextQuestion();
            }
        }, 1000);
    };

    // Renderizar KaTeX inicial con delay más largo
    setTimeout(() => {
        renderKatex();
    }, 500);
    
    // Iniciar contador después del KaTeX
    setTimeout(() => {
        startCountdown();
    }, 800);

    // Escuchar cuando cambia la pregunta
    $wire.on('question-changed', (event) => {
        console.log('Evento question-changed recibido:', event);
        clearInterval(timerInterval);
        secondsLeft = event.duration;
        originalDuration = event.duration;
        questionIndex++;
        
        // Limpiar marcas para nueva pregunta
        document.querySelectorAll('.katex-content').forEach(function(element) {
            element.removeAttribute('data-katex-done');
            element.removeAttribute('wire:ignore');
        });
        
        // Renderizar KaTeX para nueva pregunta y luego iniciar timer
        setTimeout(() => {
            renderKatex();
            setTimeout(() => {
                startCountdown();
            }, 200);
        }, 100);
    });
">

    <div class="p-8">
        <!-- Header del Quiz -->
        <div class="border-b border-gray-200 pb-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Pregunta {{ $currentQuestionIndex + 1 }} de {{ $this->questionsCount }}
                    </h1>
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ (($currentQuestionIndex + 1) / $this->questionsCount) * 100 }}%"></div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            Progreso: {{ $currentQuestionIndex + 1 }}/{{ $this->questionsCount }}
                        </p>
                    </div>
                </div>
                
                <!-- Timer mejorado -->
                <div class="text-right">
                    <div class="inline-flex items-center space-x-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-blue-800 font-medium">Tiempo restante:</span>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600" x-text="Math.floor(secondsLeft / 60) + ':' + String(secondsLeft % 60).padStart(2, '0')"></div>
                            <div class="text-xs text-blue-500">
                                <span x-text="originalDuration"></span> segundos total
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barra de progreso del timer -->
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                        <div class="bg-gradient-to-r from-green-400 via-yellow-400 to-red-400 h-2 rounded-full transition-all duration-1000" 
                             :style="`width: ${(secondsLeft / originalDuration) * 100}%`"
                             :class="secondsLeft <= 10 ? 'animate-pulse' : ''"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pregunta -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 katex-content" wire:key="question-{{ $currentQuestionIndex }}">
                    {!! $currentQuestion->text !!}
                </h2>
                
                @if ($currentQuestion->code_snippet)
                    <div class="mt-4">
                        <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                            <pre class="text-green-400 text-sm"><code>{{ $currentQuestion->code_snippet }}</code></pre>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Opciones de respuesta -->
        @php
            $correctAnswersCount = $currentQuestion->options->where('correct', true)->count();
            $isMultipleChoice = $correctAnswersCount > 1;
        @endphp

        <div class="space-y-4 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Opciones de respuesta</h3>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $isMultipleChoice ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ $isMultipleChoice ? 'Selección múltiple' : 'Selección única' }}
                </span>
            </div>
            
            @if ($isMultipleChoice)
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-purple-800 text-sm font-medium">Puedes seleccionar múltiples respuestas correctas</p>
                    </div>
                </div>
            @endif
            
            @foreach ($currentQuestion->options as $option)
                <div class="transform transition-all duration-200 hover:scale-[1.02]">
                    <label for="option.{{ $option->id }}" class="flex items-start p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                        
                        <div class="flex-shrink-0 mt-1 mr-3">
                            @if ($isMultipleChoice)
                                <input type="checkbox" 
                                       id="option.{{ $option->id }}"
                                       wire:model="answersOfQuestions.{{ $currentQuestionIndex }}"
                                       value="{{ $option->id }}"
                                       class="w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            @else
                                <input type="radio" 
                                       id="option.{{ $option->id }}"
                                       wire:model="answersOfQuestions.{{ $currentQuestionIndex }}"
                                       value="{{ $option->id }}"
                                       class="w-4 h-4 text-blue-600 border-2 border-gray-300 focus:ring-blue-500 focus:ring-2">
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="text-gray-900 font-medium katex-content" wire:key="option-{{ $option->id }}">
                                {!! $option->text !!}
                            </div>
                        </div>
                    </label>
                </div>
            @endforeach
        </div>

        <!-- Botones de navegación -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-gray-200 space-y-4 sm:space-y-0">
            <div class="flex items-center text-sm text-gray-600">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>
                    @if($isMultipleChoice)
                        Selecciona todas las respuestas que consideres correctas
                    @else
                        Selecciona la respuesta que consideres correcta
                    @endif
                </span>
            </div>
            
            <div class="flex justify-end space-x-3">
                @if ($currentQuestionIndex < $this->questionsCount - 1)
                    <button type="button"
                            x-on:click="clearInterval(timerInterval); $wire.nextQuestion();"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                        Siguiente Pregunta
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <button type="button"
                            x-on:click="clearInterval(timerInterval); $wire.submit();"
                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Finalizar Quiz
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Asegurar que KaTeX esté disponible globalmente
    window.katexReady = false;
    
    // Verificar si KaTeX está cargado
    function checkKatexReady() {
        if (typeof renderMathInElement !== 'undefined' && typeof katex !== 'undefined') {
            window.katexReady = true;
            console.log('KaTeX está listo');
        } else {
            console.log('Esperando KaTeX...');
            setTimeout(checkKatexReady, 100);
        }
    }
    
    checkKatexReady();

    // Función global para renderizar KaTeX mejorada
    window.renderAllKatex = function() {
        if (!window.katexReady) {
            console.log('KaTeX no está listo aún');
            setTimeout(window.renderAllKatex, 200);
            return;
        }
        
        console.log('Renderizando todo el KaTeX...');
        document.querySelectorAll('.katex-content:not([data-katex-done])').forEach(function(element) {
            try {
                renderMathInElement(element, {
                    delimiters: [
                        {left: "$$", right: "$$", display: true},
                        {left: "\\[", right: "\\]", display: true},
                        {left: "$", right: "$", display: false},
                        {left: "\\(", right: "\\)", display: false}
                    ],
                    throwOnError: false,
                    errorColor: '#cc0000',
                    strict: false
                });
                element.setAttribute('data-katex-done', 'true');
                element.setAttribute('wire:ignore', '');
                console.log('KaTeX renderizado para:', element);
            } catch (e) {
                console.error('Error renderizando KaTeX:', e);
            }
        });
    };

    // Renderizar cuando la página se carga con delay más largo
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(window.renderAllKatex, 1500);
    });

    // Renderizar después de actualizaciones de Livewire
    document.addEventListener('livewire:update', function() {
        console.log('Livewire actualizado, re-renderizando KaTeX');
        setTimeout(window.renderAllKatex, 200);
    });

    // Para Livewire 3
    document.addEventListener('livewire:morph', function() {
        console.log('Livewire morph, re-renderizando KaTeX');
        setTimeout(window.renderAllKatex, 200);
    });
</script>
@endpush

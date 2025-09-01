{{-- <?php --}}
<div x-data="{ 
    secondsLeft: {{ $this->getCurrentQuestionDuration() }},
    originalDuration: {{ $this->getCurrentQuestionDuration() }},
    questionIndex: {{ $currentQuestionIndex }},
    timerInterval: null
}" 
x-init="
    console.log('Iniciando timer con:', secondsLeft, 'segundos');
    
    // Función para renderizar KaTeX
    const renderKatex = () => {
        setTimeout(() => {
            console.log('Renderizando KaTeX...');
            if (typeof renderMathInElement !== 'undefined') {
                document.querySelectorAll('.katex-content').forEach(function(element) {
                    console.log('Procesando elemento:', element);
                    element.querySelectorAll('.katex').forEach(katexEl => katexEl.remove());
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
                    } catch (e) {
                        console.error('Error renderizando KaTeX:', e);
                    }
                });
            } else {
                console.warn('renderMathInElement no disponible');
            }
        }, 200);
    };
    
    // Función para iniciar el contador
    const startCountdown = () => {
        console.log('Iniciando countdown...');
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        
        timerInterval = setInterval(() => {
            console.log('Timer tick:', secondsLeft);
            if (secondsLeft > 1) { 
                secondsLeft = secondsLeft - 1; 
            } else {
                console.log('Tiempo terminado, siguiente pregunta');
                clearInterval(timerInterval);
                $wire.nextQuestion();
            }
        }, 1000);
    };

    // Renderizar KaTeX inicial
    renderKatex();
    
    // Iniciar contador
    startCountdown();

    // Escuchar cuando cambia la pregunta
    $wire.on('question-changed', (event) => {
        console.log('Evento question-changed recibido:', event);
        clearInterval(timerInterval);
        secondsLeft = event.duration;
        originalDuration = event.duration;
        questionIndex++;
        renderKatex();
        startCountdown();
    });
">

    <div class="mb-2">
        <div class="flex justify-between items-center">
            <span>Tiempo restante para esta pregunta: <span x-text="secondsLeft" class="font-bold"></span> seg.</span>
            <span class="text-sm text-gray-500">Duración: {{ $this->getCurrentQuestionDuration() }} segundos</span>
        </div>
        <!-- Barra de progreso del tiempo -->
        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000" 
                 :style="`width: ${(secondsLeft / originalDuration) * 100}%`"></div>
        </div>
    </div>

    <span class="text-bold">Pregunta {{ $currentQuestionIndex + 1 }} de {{ $this->questionsCount }}:</span>
    <h2 class="mb-4 text-2xl katex-content">{!! $currentQuestion->text !!}</h2>

    @if ($currentQuestion->code_snippet)
        <pre class="mb-4 border-2 border-solid bg-gray-50 p-2">{{ $currentQuestion->code_snippet }}</pre>
    @endif

    @php
        $correctAnswersCount = $currentQuestion->options->where('correct', true)->count();
        $isMultipleChoice = $correctAnswersCount > 1;
    @endphp

    <div class="mb-4">
        @if ($isMultipleChoice)
            <p class="text-sm text-gray-600 mb-2">Selecciona todas las respuestas correctas:</p>
        @else
            <p class="text-sm text-gray-600 mb-2">Selecciona la respuesta correcta:</p>
        @endif
        
        @foreach ($currentQuestion->options as $option)
            <div class="mb-2">
                <label for="option.{{ $option->id }}" class="flex items-center cursor-pointer">
                    @if ($isMultipleChoice)
                        <input type="checkbox" 
                               id="option.{{ $option->id }}"
                               wire:model="answersOfQuestions.{{ $currentQuestionIndex }}"
                               value="{{ $option->id }}"
                               class="mr-2">
                    @else
                        <input type="radio" 
                               id="option.{{ $option->id }}"
                               wire:model="answersOfQuestions.{{ $currentQuestionIndex }}"
                               value="{{ $option->id }}"
                               class="mr-2">
                    @endif
                    <span class="katex-content">{!! $option->text !!}</span>
                </label>
            </div>
        @endforeach
    </div>

    @if ($currentQuestionIndex < $this->questionsCount - 1)
        <div class="mt-4">
            <x-secondary-button
                x-on:click="
                    clearInterval(timerInterval);
                    $wire.nextQuestion();
                ">
                Siguiente pregunta
            </x-secondary-button>
        </div>
    @else
        <div class="mt-4">
            <x-primary-button x-on:click="
                clearInterval(timerInterval);
                $wire.submit();
            ">
                Enviar
            </x-primary-button>
        </div>
    @endif
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

    // Función global para renderizar KaTeX
    window.renderAllKatex = function() {
        if (!window.katexReady) {
            console.log('KaTeX no está listo aún');
            return;
        }
        
        console.log('Renderizando todo el KaTeX...');
        document.querySelectorAll('.katex-content').forEach(function(element) {
            // Limpiar renderizados previos
            element.querySelectorAll('.katex').forEach(katexEl => katexEl.remove());
            
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
                console.log('KaTeX renderizado para:', element);
            } catch (e) {
                console.error('Error renderizando KaTeX:', e);
            }
        });
    };

    // Renderizar cuando la página se carga
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(window.renderAllKatex, 1000);
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

<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Preguntas
        </h2>
    </x-slot>

    <x-slot name="title">
        Preguntas
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Header con botón crear y estadísticas --}}
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <a href="{{ route('question.create') }}"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Pregunta
                            </a>
                        </div>
                        <div class="text-sm text-gray-600">
                            Total: {{ $questions->total() }} preguntas
                        </div>
                    </div>

                    {{-- Filtros --}}
                    <div class="mb-6 bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Filtro de búsqueda --}}
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                    Buscar preguntas
                                </label>
                                <input type="text" 
                                       id="search"
                                       wire:model.live.debounce.300ms="search" 
                                       placeholder="Buscar por texto de pregunta..."
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            {{-- Filtro por materia --}}
                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Filtrar por materia
                                </label>
                                <select wire:model.live="subject_id" id="subject_id" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todas las materias</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Limpiar filtros --}}
                        {{-- @if($search || $subject_id)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <button wire:click="clearFilters"
                                        class="text-sm text-red-600 hover:text-red-700 font-medium">
                                    Limpiar filtros
                                </button>
                            </div>
                        @endif --}}
                    </div>

                    {{-- Tabla de preguntas --}}
                    <div class="mb-4 min-w-full overflow-hidden overflow-x-auto align-middle sm:rounded-md" wire:ignore.self>
                        <table class="min-w-full border divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="w-16 bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">ID</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Texto</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Materia</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Duración</span>
                                    </th>
                                    <th class="w-40 bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Acciones</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid" id="questions-table-body">
                                @forelse($questions as $question)
                                    <tr class="bg-white" wire:key="question-{{ $question->id }}">
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $question->id }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900">
                                            <div class="max-w-md question-text-content" data-question-id="{{ $question->id }}" data-original-text="{{ $question->text }}">
                                                {{ Str::limit($question->text, 100) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            @if($question->subject)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $question->subject->code }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">{{ $question->subject->name }}</div>
                                            @else
                                                <span class="text-gray-400">Sin materia</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $question->duration }}s
                                            </span>
                                        </td>
                                        <td>
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('question.edit', $question->id) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-gray-800 text-white hover:bg-gray-700 transition-colors"
                                                    title="Editar pregunta">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button wire:click="delete({{ $question }})"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-red-500 text-white hover:bg-red-600 transition-colors"
                                                    title="Eliminar pregunta"
                                                    onclick="return confirm('¿Estás seguro de que quieres eliminar esta pregunta?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center leading-5 text-gray-900 whitespace-no-wrap">
                                            @if($search || $subject_id)
                                                No se encontraron preguntas que coincidan con los filtros.
                                            @else
                                                No se encontraron preguntas.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Variable global para controlar el estado de KaTeX
    window.questionListKatexReady = false;
    window.questionListProcessing = false;

    // Configuración de KaTeX
    const katexConfig = {
        delimiters: [
            {left: "$$", right: "$$", display: true},
            {left: "\\[", right: "\\]", display: true},
            {left: "$", right: "$", display: false},
            {left: "\\(", right: "\\)", display: false}
        ],
        throwOnError: false,
        errorColor: '#cc0000',
        strict: false
    };

    // Función para verificar disponibilidad de KaTeX
    function isKatexAvailable() {
        return typeof renderMathInElement !== 'undefined' && typeof katex !== 'undefined';
    }

    // Función principal para renderizar KaTeX de manera segura
    function renderQuestionTexts() {
        if (window.questionListProcessing) {
            console.log('Ya hay un proceso de renderizado en curso, saltando...');
            return;
        }

        if (!isKatexAvailable()) {
            console.warn('KaTeX no está disponible aún');
            // Intentar de nuevo en 200ms
            setTimeout(renderQuestionTexts, 200);
            return;
        }

        window.questionListProcessing = true;
        console.log('Iniciando renderizado de KaTeX en lista de preguntas...');

        try {
            // Buscar todos los contenedores de texto de preguntas
            const questionElements = document.querySelectorAll('#questions-table-body .question-text-content');
            
            questionElements.forEach(function(element, index) {
                try {
                    // Verificar si ya está renderizado para evitar duplicados
                    if (element.hasAttribute('data-katex-processed')) {
                        return;
                    }

                    console.log(`Procesando pregunta ${index + 1}:`, element.textContent);
                    
                    // Renderizar KaTeX
                    renderMathInElement(element, katexConfig);
                    
                    // Marcar como procesado
                    element.setAttribute('data-katex-processed', 'true');
                    
                } catch (error) {
                    console.error(`Error renderizando KaTeX en elemento ${index}:`, error);
                }
            });

            console.log(`Renderizado completado para ${questionElements.length} elementos`);

        } catch (error) {
            console.error('Error general en renderizado de KaTeX:', error);
        } finally {
            window.questionListProcessing = false;
        }
    }

    // Función para limpiar y re-renderizar (para después de filtros)
    function refreshKatexRendering() {
        if (window.questionListProcessing) {
            return;
        }

        console.log('Limpiando y re-renderizando KaTeX...');
        
        // Limpiar marcas de procesado de elementos existentes
        document.querySelectorAll('#questions-table-body .question-text-content[data-katex-processed]').forEach(function(element) {
            element.removeAttribute('data-katex-processed');
        });

        // Re-renderizar después de un breve delay
        setTimeout(renderQuestionTexts, 100);
    }

    // Inicialización al cargar la página
    function initializeKatex() {
        if (isKatexAvailable()) {
            window.questionListKatexReady = true;
            renderQuestionTexts();
        } else {
            console.log('Esperando a que KaTeX esté disponible...');
            setTimeout(initializeKatex, 300);
        }
    }

    // Eventos del DOM
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM cargado, inicializando KaTeX...');
        setTimeout(initializeKatex, 500);
    });

    // Eventos de Livewire
    document.addEventListener('livewire:init', function() {
        console.log('Livewire inicializado para question-list');
        
        // Escuchar evento personalizado de filtros
        Livewire.on('questions-filtered', function() {
            console.log('Evento questions-filtered recibido');
            setTimeout(refreshKatexRendering, 250);
        });

        setTimeout(renderQuestionTexts, 400);
    });

    // Para Livewire 3 - morphing del DOM
    document.addEventListener('livewire:morph', function(event) {
        console.log('Livewire morph detectado en question-list');
        setTimeout(refreshKatexRendering, 150);
    });

    // Fallback para actualizaciones generales
    document.addEventListener('livewire:update', function(event) {
        console.log('Livewire update detectado en question-list');
        setTimeout(refreshKatexRendering, 150);
    });

    // Observer para detectar cambios en la tabla específicamente
    function setupQuestionTableObserver() {
        const tableBody = document.getElementById('questions-table-body');
        if (!tableBody) {
            console.warn('No se pudo encontrar el tbody de la tabla');
            return;
        }

        const observer = new MutationObserver(function(mutations) {
            let newContentDetected = false;
            
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    // Verificar nodos agregados
                    if (mutation.addedNodes.length > 0) {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1 && 
                                (node.querySelector('.question-text-content') || 
                                 node.classList?.contains('question-text-content'))) {
                                newContentDetected = true;
                            }
                        });
                    }
                }
            });
            
            if (newContentDetected) {
                console.log('Observer: detectado nuevo contenido en tabla de preguntas');
                setTimeout(refreshKatexRendering, 200);
            }
        });

        observer.observe(tableBody, {
            childList: true,
            subtree: true,
            attributes: false
        });

        console.log('Observer configurado para la tabla de preguntas');
    }

    // Configurar observer después de que el DOM esté listo
    setTimeout(setupQuestionTableObserver, 1000);

    // Múltiples intentos de renderizado inicial
    let initAttempts = 0;
    function retryInitialization() {
        if (initAttempts < 3 && !window.questionListKatexReady) {
            initAttempts++;
            console.log(`Intento de inicialización ${initAttempts}/3`);
            setTimeout(initializeKatex, 1000 * initAttempts);
        }
    }

    setTimeout(retryInitialization, 2000);

    // Eventos específicos para filtros que pueden disparar re-renderizado manual
    document.addEventListener('input', function(event) {
        if (event.target.id === 'search') {
            console.log('Input de búsqueda detectado');
            // Timeout más largo para permitir que el debounce complete
            setTimeout(refreshKatexRendering, 1000);
        }
    });

    document.addEventListener('change', function(event) {
        if (event.target.id === 'subject_id') {
            console.log('Cambio en select de materia detectado');
            setTimeout(refreshKatexRendering, 400);
        }
    });

    // Función global para renderizado manual (debugging)
    window.manualRenderKatex = function() {
        console.log('Renderizado manual iniciado');
        refreshKatexRendering();
    };

    console.log('Scripts de KaTeX para question-list cargados');
</script>
@endpush

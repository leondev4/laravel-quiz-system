<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $editing ? 'Editar Pregunta' : 'Crear Pregunta' }}
        </h2>
    </x-slot>

    <x-slot name="title">
        {{ $editing ? 'Editar Pregunta ' . $question->id : 'Crear Pregunta' }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save">
                        <div>
                            <x-input-label for="text" value="Texto de la pregunta *" />
                            <x-textarea wire:model="text" id="text" class="block mt-1 w-full" type="text"
                                name="text" required />
                            <x-input-error :messages="$errors->get('text')" class="mt-2" />
                        </div>

                        {{-- Campo de código --}}
                        <div class="mt-4">
                            <x-input-label for="code_snippet" value="Código (opcional)" />
                            <x-textarea wire:model="code_snippet" id="code_snippet" class="block mt-1 w-full font-mono text-sm"
                                name="code_snippet" rows="4" />
                            <x-input-error :messages="$errors->get('code_snippet')" class="mt-2" />
                        </div>

                        {{-- Campo de materia OBLIGATORIO --}}
                        <div class="mt-4">
                            <x-input-label for="subject_id" value="Materia *" class="font-semibold" />
                            <select wire:model.live="subject_id" id="subject_id" 
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required>
                                <option value="">-- Seleccionar materia --</option>
                                @foreach($this->subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-red-500">*</span> La materia es obligatoria para organizar las preguntas por tema
                            </p>
                        </div>

                        {{-- Campo de duración --}}
                        <div class="mt-4">
                            <label for="duration" class="block text-sm font-medium text-gray-700 font-semibold">Duración *</label>
                            <select name="duration" id="duration" wire:model="duration"
                                class="p-3 w-1/2 text-sm leading-5 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="0" disabled>Seleccione una duración</option>
                                <option value="30">30 segundos</option>
                                <option value="60">1 minuto</option>
                                <option value="90">1:30 minutos</option>
                                <option value="120">2 minutos</option>
                                <option value="150">2:30 minutos</option>
                                <option value="180">3 minutos</option>
                                <option value="240">4 minutos</option>
                                <option value="300">5 minutos</option>
                            </select>
                            @error('duration')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-red-500">*</span> Tiempo límite para responder la pregunta
                            </p>
                        </div>

                        {{-- Opciones de respuesta --}}
                        <div class="mt-4">
                            <x-input-label for="options" value="Opciones de respuesta *" class="font-semibold" />
                            <p class="text-xs text-gray-600 mb-3">
                                <span class="text-red-500">*</span> Debe marcar al menos una opción como correcta. Mínimo 2 opciones requeridas.
                            </p>
                            
                            @foreach ($options as $index => $option)
                                <div class="flex mt-2 items-center border rounded-lg p-2 {{ $option['correct'] ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                                    <span class="text-sm font-medium text-gray-500 mr-2">{{ $index + 1 }}.</span>
                                    <x-text-input type="text" wire:model="options.{{ $index }}.text"
                                        class="flex-1" name="options_{{ $index }}"
                                        id="options_{{ $index }}" autocomplete="off" 
                                        placeholder="Escriba la opción de respuesta..." />

                                    <div class="flex items-center ml-4">
                                        <input type="checkbox" class="mr-2 ml-2 text-green-600 focus:ring-green-500"
                                            wire:model="options.{{ $index }}.correct"> 
                                        <span class="text-sm font-medium {{ $option['correct'] ? 'text-green-700' : 'text-gray-600' }}">
                                            Correcta
                                        </span>
                                        
                                        {{-- Mostrar botón eliminar solo si hay más de 2 opciones --}}
                                        @if(count($options) > 2)
                                            <button wire:click="removeOption({{ $index }})" type="button"
                                                class="ml-3 text-red-600 hover:text-red-800 transition-colors"
                                                title="Eliminar opción">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        @else
                                            {{-- Mostrar mensaje informativo cuando hay exactamente 2 opciones --}}
                                            <span class="ml-3 text-xs text-gray-400">
                                                (Mínimo 2 opciones)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @error("options.{$index}.text")
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            @endforeach

                            @error('options')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror

                            <div class="mt-3 flex items-center justify-between">
                                <button wire:click="addOption" type="button"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar Opción
                                </button>
                                
                                <span class="text-xs text-gray-500">
                                    Total: {{ count($options) }} opciones
                                </span>
                            </div>
                        </div>

                        {{-- Explicación de la respuesta --}}
                        <div class="mt-4">
                            <x-input-label for="answer_explanation" value="Explicación de la respuesta (opcional)" />
                            <x-textarea wire:model="answer_explanation" id="answer_explanation" class="block mt-1 w-full"
                                name="answer_explanation" rows="3" />
                            <x-input-error :messages="$errors->get('answer_explanation')" class="mt-2" />
                        </div>

                        {{-- Enlace de más información --}}
                        <div class="mt-4">
                            <x-input-label for="more_info_link" value="Enlace de más información (opcional)" />
                            <x-text-input wire:model="more_info_link" id="more_info_link" class="block mt-1 w-full"
                                type="url" name="more_info_link" />
                            <x-input-error :messages="$errors->get('more_info_link')" class="mt-2" />
                        </div>

                        {{-- Resumen de campos requeridos --}}
                        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <h4 class="text-sm font-medium text-yellow-800 mb-2">Campos obligatorios:</h4>
                            <ul class="text-xs text-yellow-700 space-y-1">
                                <li>• Texto de la pregunta</li>
                                <li>• Materia</li>
                                <li>• Duración</li>
                                <li>• Al menos 2 opciones de respuesta</li>
                                <li>• Al menos una opción marcada como correcta</li>
                            </ul>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('questions') }}" 
                               class="rounded-md border border-gray-300 bg-white px-4 py-2 text-xs uppercase text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancelar
                            </a>
                            
                            <button type="button" onclick="mostrarPreview()"
                                class="rounded-md border border-transparent bg-blue-200 px-4 py-2 text-xs uppercase text-blue-700 hover:bg-blue-300 hover:text-blue-900 transition-colors">
                                Vista Previa
                            </button>

                            <x-primary-button>
                                {{ $editing ? 'Actualizar Pregunta' : 'Crear Pregunta' }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Vista Previa (mantener el mismo modal anterior) -->
    <div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Vista Previa de la Pregunta</h3>
                    <button onclick="cerrarPreview()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Contenido del Modal -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="mb-4">
                        <span class="text-bold">Pregunta:</span>
                        <h2 class="mb-4 text-xl katex-content" id="preguntaPreview"></h2>
                    </div>

                    <div id="materiaPreview" class="mb-4">
                        <span class="text-bold">Materia:</span>
                        <span id="materiaContent" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2"></span>
                    </div>

                    <div id="duracionPreview" class="mb-4">
                        <span class="text-bold">Duración:</span>
                        <span id="duracionContent" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2"></span>
                    </div>

                    <div id="codigoPreview" class="mb-4 hidden">
                        <span class="text-bold">Código:</span>
                        <pre class="mb-4 border-2 border-solid bg-white p-2" id="codigoContent"></pre>
                    </div>

                    <div class="mb-4">
                        <span class="text-bold">Opciones:</span>
                        <div id="opcionesPreview" class="mt-2 space-y-2"></div>
                    </div>

                    <div id="explicacionPreview" class="mb-4 hidden">
                        <span class="text-bold">Explicación:</span>
                        <div class="mt-1 text-sm text-gray-700 katex-content" id="explicacionContent"></div>
                    </div>

                    <div id="enlacePreview" class="mb-4 hidden">
                        <span class="text-bold">Más información:</span>
                        <a href="#" target="_blank" class="ml-2 text-blue-600 hover:underline" id="enlaceContent"></a>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end mt-6 pt-4 border-t">
                    <button onclick="cerrarPreview()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Configuración de KaTeX
    const katexOpts = {
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

    function mostrarPreview() {
        // Obtener datos de Livewire
        const preguntaTexto = @this.text || '';
        const opciones = @this.options || [];
        const codigoSnippet = @this.code_snippet || '';
        const explicacion = @this.answer_explanation || '';
        const enlaceInfo = @this.more_info_link || '';
        const subjectId = @this.subject_id || '';
        const duracion = @this.duration || 0;

        // Mostrar pregunta
        const preguntaPreview = document.getElementById('preguntaPreview');
        preguntaPreview.innerHTML = preguntaTexto;

        // Mostrar materia
        const materiaContent = document.getElementById('materiaContent');
        if (subjectId) {
            const subjectSelect = document.getElementById('subject_id');
            const selectedOption = subjectSelect.options[subjectSelect.selectedIndex];
            materiaContent.textContent = selectedOption.text;
            materiaContent.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2';
        } else {
            materiaContent.textContent = 'Sin materia seleccionada';
            materiaContent.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2';
        }

        // Mostrar duración
        const duracionContent = document.getElementById('duracionContent');
        if (duracion > 0) {
            duracionContent.textContent = duracion + ' segundos';
            duracionContent.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2';
        } else {
            duracionContent.textContent = 'Sin duración seleccionada';
            duracionContent.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2';
        }

        // Mostrar código si existe
        const codigoPreview = document.getElementById('codigoPreview');
        const codigoContent = document.getElementById('codigoContent');
        if (codigoSnippet.trim()) {
            codigoContent.textContent = codigoSnippet;
            codigoPreview.classList.remove('hidden');
        } else {
            codigoPreview.classList.add('hidden');
        }

        // Mostrar opciones
        const opcionesPreview = document.getElementById('opcionesPreview');
        opcionesPreview.innerHTML = '';
        
        opciones.forEach((opcion, index) => {
            if (opcion.text && opcion.text.trim()) {
                const div = document.createElement('div');
                div.className = 'flex items-center space-x-2 p-2 rounded border ' + 
                    (opcion.correct ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200');
                
                const input = document.createElement('input');
                input.type = 'radio';
                input.name = 'preview_options';
                input.className = 'mr-2';
                input.disabled = true;
                
                const span = document.createElement('span');
                span.className = 'katex-content';
                span.innerHTML = opcion.text;
                
                if (opcion.correct) {
                    span.className += ' font-bold text-green-700';
                    const correctLabel = document.createElement('span');
                    correctLabel.className = 'text-xs text-green-600 ml-2';
                    correctLabel.textContent = '✓ Correcta';
                    div.appendChild(input);
                    div.appendChild(span);
                    div.appendChild(correctLabel);
                } else {
                    div.appendChild(input);
                    div.appendChild(span);
                }
                
                opcionesPreview.appendChild(div);
            }
        });

        // Mostrar explicación si existe
        const explicacionPreview = document.getElementById('explicacionPreview');
        const explicacionContent = document.getElementById('explicacionContent');
        if (explicacion.trim()) {
            explicacionContent.innerHTML = explicacion;
            explicacionPreview.classList.remove('hidden');
        } else {
            explicacionPreview.classList.add('hidden');
        }

        // Mostrar enlace si existe
        const enlacePreview = document.getElementById('enlacePreview');
        const enlaceContent = document.getElementById('enlaceContent');
        if (enlaceInfo.trim()) {
            enlaceContent.href = enlaceInfo;
            enlaceContent.textContent = enlaceInfo;
            enlacePreview.classList.remove('hidden');
        } else {
            enlacePreview.classList.add('hidden');
        }

        // Mostrar modal
        document.getElementById('previewModal').classList.remove('hidden');

        // Renderizar KaTeX después de mostrar el modal
        setTimeout(() => {
            if (typeof renderMathInElement !== 'undefined') {
                document.querySelectorAll('#previewModal .katex-content').forEach(function(element) {
                    try {
                        renderMathInElement(element, katexOpts);
                    } catch (e) {
                        console.error('Error renderizando KaTeX:', e);
                    }
                });
            }
        }, 100);
    }

    function cerrarPreview() {
        document.getElementById('previewModal').classList.add('hidden');
    }

    // Cerrar modal al hacer clic fuera
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('previewModal');
        if (event.target === modal) {
            cerrarPreview();
        }
    });

    // Cerrar modal con tecla Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            cerrarPreview();
        }
    });
</script>
@endpush

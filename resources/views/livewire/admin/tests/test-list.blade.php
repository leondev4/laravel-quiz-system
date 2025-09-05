<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Resultados de Quizzes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Header con estadísticas --}}
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Gestión de Resultados</h3>
                            <p class="text-sm text-gray-600">Total: {{ $tests->total() }} resultados</p>
                        </div>
                        <div class="flex gap-2">
                            <x-danger-button 
                                wire:click="deleteOldTests(30)"
                                wire:confirm="¿Estás seguro de que deseas eliminar los resultados de más de 30 días?"
                                class="text-xs">
                                Borrar resultados viejos (30 días)
                            </x-danger-button>
                            
                            <x-danger-button 
                                wire:click="deleteOldTests(90)"
                                wire:confirm="¿Estás seguro de que deseas eliminar los resultados de más de 90 días?"
                                class="text-xs">
                                Borrar resultados viejos (90 días)
                            </x-danger-button>
                        </div>
                    </div>

                    {{-- Filtros --}}
                    <div class="mb-6 bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            
                            {{-- Filtro por materia --}}
                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Filtrar por materia
                                </label>
                                <select wire:model.live="subject_id" id="subject_id"
                                        class="w-full p-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 bg-white">
                                    <option value="">Todas las materias</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filtro por quiz --}}
                            <div>
                                <label for="quiz_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Filtrar por quiz
                                    @if($subject_id)
                                        <span class="text-xs text-gray-500">(de la materia seleccionada)</span>
                                    @endif
                                </label>
                                <select wire:model.live="quiz_id" id="quiz_id"
                                        class="w-full p-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    <option value="0">
                                        @if($subject_id)
                                            Todos los quizzes de la materia
                                        @else
                                            Todos los quizzes
                                        @endif
                                    </option>
                                    @forelse ($quizzes as $quiz)
                                        <option value="{{ $quiz->id }}">
                                            {{ $quiz->title }}
                                            @if($quiz->subject && !$subject_id)
                                                ({{ $quiz->subject->code }})
                                            @endif
                                        </option>
                                    @empty
                                        @if($subject_id)
                                            <option disabled>No hay quizzes en esta materia</option>
                                        @endif
                                    @endforelse
                                </select>
                                @if($subject_id && $quizzes->isEmpty())
                                    <p class="text-xs text-yellow-600 mt-1">
                                        No hay quizzes disponibles para la materia seleccionada.
                                    </p>
                                @endif
                            </div>

                            {{-- Información de filtros activos --}}
                            <div class="flex items-center">
                                @if($quiz_id > 0 || $subject_id)
                                    <div class="text-sm text-gray-600">
                                        <div class="font-medium mb-2">Filtros activos:</div>
                                        @if($subject_id)
                                            @php $selectedSubject = $subjects->where('id', $subject_id)->first(); @endphp
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-1 mr-1">
                                                📚 {{ $selectedSubject?->name }}
                                            </div>
                                        @endif
                                        @if($quiz_id > 0)
                                            @php $selectedQuiz = $quizzes->where('id', $quiz_id)->first(); @endphp
                                            <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1 mr-1">
                                                📝 {{ $selectedQuiz?->title }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Limpiar filtros --}}
                        @if($quiz_id > 0 || $subject_id)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <button wire:click="clearFilters"
                                        class="text-sm text-red-600 hover:text-red-700 font-medium">
                                    Limpiar filtros
                                </button>
                            </div>
                        @endif
                    </div>

                    {{-- Tabla de resultados --}}
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Usuario
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Quiz
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Materia
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Resultado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dirección IP
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tiempo Utilizado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($tests as $test)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $test->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-white text-xs font-semibold">
                                                        {{ strtoupper(substr($test->user->name ?? 'G', 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="font-medium">{{ $test->user->name ?? 'Invitado' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $test->user->email ?? 'Sin email' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="font-medium">{{ $test->quiz?->title }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $test->questions_count }} {{ $test->questions_count == 1 ? 'pregunta' : 'preguntas' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            @if($test->quiz?->subject)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $test->quiz->subject->code }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">{{ $test->quiz->subject->name }}</div>
                                            @else
                                                <span class="text-gray-400">Sin materia</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <span class="font-medium">{{ $test->result }}/{{ $test->questions_count }}</span>
                                                @php
                                                    $percentage = $test->questions_count > 0 ? ($test->result / $test->questions_count) * 100 : 0;
                                                @endphp
                                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                    {{ $percentage >= 70 ? 'bg-green-100 text-green-800' : ($percentage >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ number_format($percentage, 1) }}%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $test->ip_address }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($test->time_spent)
                                                {{ sprintf('%.1f', $test->time_spent / 60) }} min
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div>{{ $test->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs">{{ $test->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('results.show', $test) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 border border-transparent rounded-md text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    title="Ver resultados">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <button wire:click="deleteTest({{ $test->id }})"
                                                    wire:confirm="¿Estás seguro de que deseas eliminar este resultado?"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-600 border border-transparent rounded-md text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                    title="Eliminar resultado">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                            @if($quiz_id > 0 || $subject_id)
                                                No se encontraron resultados que coincidan con los filtros.
                                            @else
                                                No se encontraron resultados.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    @if($tests->hasPages())
                        <div class="mt-6">
                            {{ $tests->links() }}
                        </div>
                    @endif

                    {{-- Mensajes flash --}}
                    @if (session()->has('message'))
                        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

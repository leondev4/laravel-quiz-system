<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Quizzes
        </h2>
    </x-slot>

    <x-slot name="title">
        Quizzes
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Header con botón crear y estadísticas --}}
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <a href="{{ route('quiz.create') }}"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Quiz
                            </a>
                        </div>
                        <div class="text-sm text-gray-600">
                            Total: {{ $quizzes->total() }} quizzes
                        </div>
                    </div>

                    {{-- Filtros --}}
                    <div class="mb-6 bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Filtro de búsqueda --}}
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                    Buscar quizzes
                                </label>
                                <input type="text" 
                                       id="search"
                                       wire:model.live.debounce.300ms="search" 
                                       placeholder="Buscar por título del quiz..."
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
                        @if($search || $subject_id)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <button wire:click="clearFilters"
                                        class="text-sm text-red-600 hover:text-red-700 font-medium">
                                    Limpiar filtros
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4 min-w-full overflow-hidden overflow-x-auto align-middle sm:rounded-md">
                        <table class="min-w-full border divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">ID</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Título</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Materia</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Estado</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Apertura/Cierre</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Preguntas</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Publicado</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Público</span>
                                    </th>
                                    <th class="w-40 bg-gray-50 px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Acciones</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @forelse($quizzes as $quiz)
                                    <tr class="bg-white">
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $quiz->id }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <div class="font-medium">{{ $quiz->title }}</div>
                                            @if($quiz->description)
                                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($quiz->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            @if($quiz->subject)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $quiz->subject->code }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">{{ $quiz->subject->name }}</div>
                                            @else
                                                <span class="text-gray-400">Sin materia</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            @php
                                                $status = $quiz->status;
                                                $statusColors = [
                                                    'open' => 'bg-green-100 text-green-800',
                                                    'upcoming' => 'bg-yellow-100 text-yellow-800',
                                                    'closed' => 'bg-red-100 text-red-800'
                                                ];
                                                $statusLabels = [
                                                    'open' => 'Abierto',
                                                    'upcoming' => 'Próximamente',
                                                    'closed' => 'Cerrado'
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$status] }}">
                                                {{ $statusLabels[$status] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <div class="text-xs">
                                                @if($quiz->opens_at)
                                                    <div>Abre: {{ $quiz->opens_at->format('d/m/Y H:i') }}</div>
                                                @endif
                                                @if($quiz->closes_at)
                                                    <div>Cierra: {{ $quiz->closes_at->format('d/m/Y H:i') }}</div>
                                                @endif
                                                @if(!$quiz->opens_at && !$quiz->closes_at)
                                                    <span class="text-gray-500">Siempre disponible</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $quiz->questions_count }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <input 
                                                type="checkbox" 
                                                wire:change="togglePublished({{ $quiz->id }})"
                                                @checked($quiz->published)
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <input 
                                                type="checkbox" 
                                                wire:change="togglePublic({{ $quiz->id }})"
                                                @checked($quiz->public)
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('quiz.edit', $quiz) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-gray-800 text-white hover:bg-gray-700 transition-colors"
                                                    title="Editar Examen">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <button wire:click="delete({{ $quiz->id }})"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-red-500 text-white hover:bg-red-600 transition-colors"
                                                    title="Eliminar Examen"
                                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este examen?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center leading-5 text-gray-900 whitespace-no-wrap">
                                            @if($search || $subject_id)
                                                No se encontraron quizzes que coincidan con los filtros.
                                            @else
                                                No se encontraron quizzes.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $quizzes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

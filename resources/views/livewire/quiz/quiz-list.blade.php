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
                    <div class="mb-4">
                        <a href="{{ route('quiz.create') }}"
                            class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                            Create Quiz
                        </a>
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
                                            {{ $quiz->title }}
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
                                            {{ $quiz->questions_count }}
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
                                        <td colspan="8"
                                            class="px-6 py-4 text-center leading-5 text-gray-900 whitespace-no-wrap">
                                            No se encontraron exámenes.
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

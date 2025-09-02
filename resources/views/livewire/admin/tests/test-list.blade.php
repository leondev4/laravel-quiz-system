<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de Quizzes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center">
                        <select class="p-3 w-1/2 text-sm leading-5 rounded border-0 shadow text-slate-600"
                            wire:model.live="quiz_id">
                            <option value="0">Todos los quizzes</option>
                            @foreach ($quizzes as $quiz)
                                <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                            @endforeach
                        </select>
                        
                        <div class="flex gap-2">
                            <x-danger-button 
                                wire:click="deleteOldTests(30)"
                                wire:confirm="Are you sure you want to delete tests older than 30 days?"
                                class="text-xs">
                                Borrar quizzes viejos (30 días)
                            </x-danger-button>
                            
                            <x-danger-button 
                                wire:click="deleteOldTests(90)"
                                wire:confirm="Are you sure you want to delete tests older than 90 days?"
                                class="text-xs">
                                Borrar quizzes viejos (90 días)
                            </x-danger-button>
                        </div>
                    </div>

                    <table class="table mt-4 w-full table-view">
                        <thead>
                            <tr>
                                <th class="w-16 bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">ID</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">usuario</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Quiz</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Resultado</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">dirección IP</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Tiempo utilizado</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">FEcha</span>
                                </th>
                                <th class="w-40 bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">
                                    </span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                            @forelse($tests as $test)
                                <tr class="bg-white">
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->id }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->user->name ?? 'Guest' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->quiz?->title }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->result . '/' . $test->questions_count }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->ip_address }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ intval($test->time_spent / 60) }} min. y {{ gmdate('s', $test->time_spent) }}
                                        seg.
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        <div class="flex gap-2">
                                            <a href="{{ route('results.show', $test) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                title="Ver resultados">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <button 
                                                wire:click="deleteTest({{ $test->id }})"
                                                wire:confirm="¿Seguro que desea eliminar este quiz?"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-500 border border-transparent rounded-md text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                                title="Eliminar quiz">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"
                                        class="px-6 py-4 text-center leading-5 text-gray-900 whitespace-no-wrap">
                                        No hay quizzes disponibles.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $tests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

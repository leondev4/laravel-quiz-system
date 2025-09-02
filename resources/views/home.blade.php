<x-app-layout>

    {{-- Fecha y hora actual del sistema --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-blue-800 font-medium">
                        Fecha y hora actual del sistema: {{ now()->format('d/m/Y g:i A') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Quizzes privados --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h6 class="text-2xl font-bold text-gray-800">Quizzes Activos</h6>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            {{ $registered_only_quizzes->count() }} disponibles
                        </div>
                    </div>

                    {{-- Filtro de docentes mejorado --}}
                    <div class="mb-8">
                        <form method="GET" action="{{ route('home') }}" class="flex items-center space-x-3">
                            <label for="author_id" class="text-sm font-medium text-gray-700">Filtrar por docente:</label>
                            <select name="author_id" id="author_id" onchange="this.form.submit()"
                                class="p-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">Todos los docentes</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}" {{ $author_id == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        {{ $authors->links() }}
                    </div>

                    {{-- Grid de quizzes mejorado --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($registered_only_quizzes as $quiz)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                {{-- Header de la tarjeta --}}
                                <div class="p-5 border-b border-gray-100">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <a href="{{ route('quiz.show', $quiz->slug) }}" 
                                               class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition-colors line-clamp-2">
                                                {{ $quiz->title }}
                                            </a>
                                            @if($quiz->description)
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $quiz->description }}</p>
                                            @endif
                                        </div>
                                        {{-- Estado del quiz --}}
                                        <div class="ml-3">
                                            @php
                                                $status = $quiz->status ?? 'open';
                                                $statusConfig = [
                                                    'open' => ['bg-green-100', 'text-green-800', '🟢'],
                                                    'upcoming' => ['bg-yellow-100', 'text-yellow-800', '🟡'],
                                                    'closed' => ['bg-red-100', 'text-red-800', '🔴']
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusConfig[$status][0] ?? 'bg-gray-100' }} {{ $statusConfig[$status][1] ?? 'text-gray-800' }}">
                                                {{ $statusConfig[$status][2] ?? '⚪' }} {{ ucfirst($status === 'open' ? 'Abierto' : ($status === 'upcoming' ? 'Próximo' : 'Cerrado')) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Contenido de la tarjeta --}}
                                <div class="p-5">
                                    {{-- Información del quiz --}}
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium">{{ $quiz->questions_count }}</span>
                                            <span class="ml-1">{{ $quiz->questions_count == 1 ? 'pregunta' : 'preguntas' }}</span>
                                        </div>
                                        
                                        @if($quiz->user)
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $quiz->user->name }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Fechas del quiz --}}
                                    <div class="space-y-2">
                                        @if($quiz->opens_at)
                                            <div class="flex items-center text-xs text-gray-600">
                                                <svg class="w-3 h-3 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-medium">Abre:</span>
                                                <span class="ml-1">{{ $quiz->opens_at->format('d/m/Y g:i A') }}</span>
                                            </div>
                                        @endif
                                        @if($quiz->closes_at)
                                            <div class="flex items-center text-xs text-gray-600">
                                                <svg class="w-3 h-3 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-medium">Cierra:</span>
                                                <span class="ml-1">{{ $quiz->closes_at->format('d/m/Y g:i A') }}</span>
                                            </div>
                                        @endif
                                        @if(!$quiz->opens_at && !$quiz->closes_at)
                                            <div class="flex items-center text-xs text-blue-600">
                                                <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-medium">Siempre disponible</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Footer con botón de acción --}}
                                <div class="px-5 pb-5">
                                    <a href="{{ route('quiz.show', $quiz->slug) }}" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M12 5v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Iniciar Quiz
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay quizzes disponibles</h3>
                                    <p class="mt-1 text-sm text-gray-500">No se encontraron quizzes para usuarios registrados en este momento.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

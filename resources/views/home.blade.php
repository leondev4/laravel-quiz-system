<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Quizzes Disponibles
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Estadísticas principales --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <div>
                                    <p class="text-blue-100">Quizzes Disponibles</p>
                                    <p class="text-2xl font-bold">{{ $registered_only_quizzes->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <div>
                                    <p class="text-green-100">Materias</p>
                                    <p class="text-2xl font-bold">{{ $subjects->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <div>
                                    <p class="text-purple-100">Total Preguntas</p>
                                    <p class="text-2xl font-bold">{{ $registered_only_quizzes->sum('questions_count') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mostrar filtros solo si hay quizzes --}}
                    @if($registered_only_quizzes->count() > 0)
                        {{-- Filtros mejorados --}}
                        <div class="mb-8 bg-gray-50 rounded-lg p-4">
                            <form method="GET" action="{{ route('home') }}" class="space-y-4">
                                
                                {{-- Encabezado de filtros --}}
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
                                    @if($author_id || $subject_id)
                                        <a href="{{ route('home') }}" 
                                           class="text-sm text-red-600 hover:text-red-700 font-medium">
                                            Limpiar filtros
                                        </a>
                                    @endif
                                </div>

                                {{-- Filtros en grid responsivo --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    
                                    {{-- Filtro por docente --}}
                                    <div>
                                        <label for="author_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Filtrar por docente
                                        </label>
                                        <select name="author_id" id="author_id" onchange="this.form.submit()"
                                                class="w-full p-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white">
                                            <option value="">Todos los docentes</option>
                                            @foreach ($authors as $author)
                                                <option value="{{ $author->id }}" {{ $author_id == $author->id ? 'selected' : '' }}>
                                                    {{ $author->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- Mantener el filtro de materia cuando se cambia el docente --}}
                                        @if($subject_id)
                                            <input type="hidden" name="subject_id" value="{{ $subject_id }}">
                                        @endif
                                    </div>

                                    {{-- Filtro por materia --}}
                                    <div>
                                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            Filtrar por materia
                                        </label>
                                        <select name="subject_id" id="subject_id" onchange="this.form.submit()"
                                                class="w-full p-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring-green-500 focus:border-green-500 bg-white">
                                            <option value="">Todas las materias</option>
                                            @foreach ($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $subject_id == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->code }} - {{ $subject->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- Mantener el filtro de docente cuando se cambia la materia --}}
                                        @if($author_id)
                                            <input type="hidden" name="author_id" value="{{ $author_id }}">
                                        @endif
                                    </div>

                                    {{-- Información de filtros activos --}}
                                    <div class="flex items-center">
                                        @if($author_id || $subject_id)
                                            <div class="text-sm text-gray-600">
                                                <div class="font-medium">Filtros activos:</div>
                                                @if($author_id)
                                                    <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1 mr-1">
                                                        👤 {{ $authors->where('id', $author_id)->first()?->name }}
                                                    </div>
                                                @endif
                                                @if($subject_id)
                                                    <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1 mr-1">
                                                        📚 {{ $subjects->where('id', $subject_id)->first()?->name }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </form>
                            
                            {{-- Paginación de autores --}}
                            @if($authors->hasPages())
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    {{ $authors->links() }}
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Grid de quizzes mejorado --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($registered_only_quizzes as $quiz)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 group overflow-hidden">
                                {{-- Header con estado del quiz --}}
                                <div class="p-4 pb-2">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-lg text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-2">
                                                {{ $quiz->title }}
                                            </h3>
                                            @if($quiz->description)
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                                    {{ $quiz->description }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        {{-- Badge de estado --}}
                                        <div class="ml-2">
                                            @php
                                                $status = $quiz->status;
                                                $statusConfig = [
                                                    'open' => ['bg-green-100', 'text-green-800', '🟢'],
                                                    'upcoming' => ['bg-yellow-100', 'text-yellow-800', '🟡'],
                                                    'closed' => ['bg-red-100', 'text-red-800', '🔴']
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusConfig[$status][0] }} {{ $statusConfig[$status][1] }}">
                                                {{ $statusConfig[$status][2] }}
                                                @if($status === 'open')
                                                    Disponible
                                                @elseif($status === 'upcoming')
                                                    Próximamente
                                                @else
                                                    Cerrado
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Información del quiz --}}
                                    <div class="space-y-2">
                                        <div class="flex items-center text-xs text-gray-600">
                                            <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="font-medium">{{ $quiz->questions_count }}</span>
                                            <span class="ml-1">{{ $quiz->questions_count == 1 ? 'pregunta' : 'preguntas' }}</span>
                                        </div>
                                        
                                        {{-- Materia --}}
                                        @if($quiz->subject)
                                            <div class="flex items-center text-xs text-gray-600">
                                                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.832 18.477 19.246 18 17.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $quiz->subject->code }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        {{-- Docente --}}
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
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Inicia: {{ $quiz->opens_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                        @if($quiz->closes_at)
                                            <div class="flex items-center text-xs text-gray-600">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Termina: {{ $quiz->closes_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Footer con botón de acción --}}
                                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                                    @if($quiz->status === 'open')
                                        <a href="{{ route('quiz.show', $quiz->slug) }}" 
                                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a2.5 2.5 0 015 0H17l-3 3-3-3z"></path>
                                            </svg>
                                            Realizar Quiz
                                        </a>
                                    @elseif($quiz->status === 'upcoming')
                                        <div class="w-full text-center py-2 text-sm text-yellow-600 font-medium">
                                            Disponible próximamente
                                        </div>
                                    @else
                                        <div class="w-full text-center py-2 text-sm text-red-600 font-medium">
                                            Quiz cerrado
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay quizzes disponibles</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @if($author_id || $subject_id)
                                            No se encontraron quizzes con los filtros seleccionados.
                                        @else
                                            No se encontraron quizzes disponibles en este momento.
                                        @endif
                                    </p>
                                    @if($author_id || $subject_id)
                                        <div class="mt-4">
                                            <a href="{{ route('home') }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                Limpiar filtros
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

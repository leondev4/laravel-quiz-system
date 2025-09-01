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
                    <h6 class="text-xl font-bold">Quizzes Activos</h6>
                    {{-- mostrar los docentes a seleccionar  --}}
                    <form method="GET" action="{{ route('home') }}">
                        <select name="author_id" id="author_id" onchange="this.form.submit()"
                        class="p-3 w-1/2 text-lg leading-5 rounded border-0 shadow text-slate-600">
                            <option value="">Todos los docentes</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" {{ $author_id == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                    {{ $authors->links() }}
                    <div class="mb-6"></div>

                    @forelse($registered_only_quizzes as $quiz)
                        <div class="px-4 py-2 w-full lg:w-6/12 xl:w-3/12">
                            <div
                                class="flex relative flex-col mb-6 min-w-0 break-words bg-white rounded shadow-lg xl:mb-0">
                                <div class="flex-auto p-4">
                                    <a href="{{ route('quiz.show', $quiz->slug) }}" class="text-lg font-medium hover:text-blue-600">
                                        {{ $quiz->title }}
                                    </a>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Preguntas: <span class="font-medium">{{ $quiz->questions_count }}</span>
                                    </p>
                                    
                                    {{-- Fechas compactas --}}
                                    <div class="mt-3 text-xs text-gray-500 space-y-1">
                                        @if($quiz->opens_at)
                                            <div>🟢 Abre: {{ $quiz->opens_at->format('d/m/Y g:i A') }}</div>
                                        @endif
                                        @if($quiz->closes_at)
                                            <div>🔴 Cierra: {{ $quiz->closes_at->format('d/m/Y g:i A') }}</div>
                                        @endif
                                        @if(!$quiz->opens_at && !$quiz->closes_at)
                                            <div class="text-blue-600">⏰ Siempre disponible</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="mt-2">No hay quizzes para usuarios registrados.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

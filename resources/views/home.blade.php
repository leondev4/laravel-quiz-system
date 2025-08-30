<x-app-layout>

    {{-- Quizzes publicos --}}

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h6 class="text-xl font-bold">Quizzes Públicos</h6>

                    @forelse($public_quizzes as $quiz)
                        <div class="px-4 py-2 w-full lg:w-6/12 xl:w-3/12">
                            <div
                                class="flex relative flex-col mb-6 min-w-0 break-words bg-white rounded shadow-lg xl:mb-0">
                                <div class="flex-auto p-4">
                                    <a href="{{ route('quiz.show', $quiz->slug) }}">{{ $quiz->title }}</a>
                                    <p class="text-sm">Preguntas: <span>{{ $quiz->questions_count }}</span></p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="mt-2">No hay quizzes públicos</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div> --}}

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
                                    <a href="{{ route('quiz.show', $quiz->slug) }}">{{ $quiz->title }}</a>
                                    <p class="text-sm">Questions: <span>{{ $quiz->questions_count }}</span></p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="mt-2">No quizzes para usuarios registrados.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

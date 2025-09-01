<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h6 class="text-xl font-bold">Resultados del Quiz</h6>

                    <table class="mt-4 table w-full table-view">
                        <tbody class="bg-white">
                            @if (auth()->user()?->is_admin)
                                <tr class="w-28">
                                    <th
                                        class="border border-solid bg-gray-100 px-6 py-3 text-left text-sm font-semibold uppercase text-slate-600">
                                        USUARIO</th>
                                    <td class="border border-solid px-6 py-3">{{ $test->user->name ?? '' }}
                                        ({{ $test->user->email ?? '' }})</td>
                                </tr>
                            @endif
                            <tr class="w-28">
                                <th
                                    class="border border-solid bg-gray-100 px-6 py-3 text-left text-sm font-semibold uppercase text-slate-600">
                                    FECHA</th>
                                <td class="border border-solid px-6 py-3">
                                    {{ $test->created_at->format('d/m/Y g:i A') ?? '' }}</td>
                            </tr>
                            <tr class="w-28">
                                <th
                                    class="border border-solid bg-gray-100 px-6 py-3 text-left text-sm font-semibold uppercase text-slate-600">
                                    RESULTADO</th>
                                <td class="border border-solid px-6 py-3">
                                    {{ $test->result }} / {{ $questions_count }}
                                    @if ($test->time_spent)
                                        (tiempo: {{ sprintf('%.2f', $test->time_spent / 60) }}
                                        minutos)
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        @isset($leaderboard)
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-12">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    {{-- <div class="p-6 text-gray-900">
                        <h6 class="text-xl font-bold">Tabla de Clasificación</h6>

                        <table class="table mt-4 w-full table-view">
                            <thead>
                                <th class="text-left">Posición</th>
                                <th class="text-left">Usuario</th>
                                <th class="text-left">Resultado</th>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($leaderboard as $test)
                                    <tr @class([
                                        'bg-gray-100' => auth()->user()->name == $test->user->name,
                                    ])>
                                        <td class="w-9">{{ $loop->iteration }}</td>
                                        <td class="w-1/2">{{ $test->user->name }}</td>
                                        <td>{{ $test->result }} / {{ $questions_count }} (tiempo:
                                            {{ sprintf('%.2f', $test->time_spent / 60) }} minutos)
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>
        @endisset
        <br>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @foreach ($results as $result)
                        <table class="table table-view w-full my-4 bg-white">
                            <tbody class="bg-white">
                                <tr class="bg-gray-100">
                                    <td class="w-1/2">Pregunta #{{ $loop->iteration }}</td>
                                    <td>{!! nl2br($result->question->text) !!}</td>
                                </tr>
                                <tr>
                                    <td>Respuestas</td>
                                    <td>
                                        @foreach ($result->question->options as $option)
                                            <li @class([
                                                'underline' => $result->option_id == $option->id,
                                                'font-bold' => $option->correct == 1,
                                            ])>
                                                {{ $option->text }}
                                                {{-- @if ($option->correct == 1)
                                                    <span class="italic">(respuesta correcta)</span>
                                                @endif --}}
                                                @if ($result->option_id == $option->id)
                                                    <span class="italic">(tu respuesta)</span>
                                                @endif
                                            </li>
                                        @endforeach
                                        @if (is_null($result->option_id))
                                            <span class="font-bold italic">Pregunta sin responder.</span>
                                        @endif
                                    </td>
                                </tr>
                                @if ($result->question->answer_explanation || $result->question->more_info_link)
                                    <tr>
                                        <td>Explicación de la Respuesta</td>
                                        <td>
                                            {{ $result->question->answer_explanation }}
                                        </td>
                                    </tr>
                                    @if ($result->question->more_info_link)
                                        <tr>
                                            <td>
                                                Leer más:
                                            </td>
                                            <td>
                                                <div class="mt-4">
                                                    <a href="{{ $result->question->more_info_link }}"
                                                        class="hover:underline" target="_blank">
                                                        {{ $result->question->more_info_link }}
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                        </table>

                        @if (!$loop->last)
                            <hr class="my-4 md:min-w-full">
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

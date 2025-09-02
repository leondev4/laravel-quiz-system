<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Quiz: {{ $quiz->title }}
        </h2>
    </x-slot>

    <x-slot name="title">
        {{ $quiz->title }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (!$quiz->public && !auth()->check())
                        <div class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-red-700">
                            <span class="inline-block align-middle mr-8">
                                Este quiz está disponible solo para usuarios registrados. Por favor <a href="{{ route('login') }}"
                                    class="hover:underline">Inicia sesión</a> o <a href="{{ route('register') }}"
                                    class="hover:underline">Regístrate</a>
                            </span>
                        </div>
                    @elseif (!$quiz->isOpen())
                        <div class="text-white px-6 py-4 border-0 rounded relative mb-4 
                            {{ $quiz->status === 'upcoming' ? 'bg-yellow-600' : 'bg-red-700' }}">
                            <span class="inline-block align-middle mr-8">
                                @if($quiz->status === 'upcoming')
                                    Este quiz se abrirá el {{ $quiz->opens_at->format('d \d\e M, Y \a \l\a\s H:i') }}
                                @else
                                    Este quiz se cerró el {{ $quiz->closes_at->format('d \d\e M, Y \a \l\a\s H:i') }}
                                @endif
                            </span>
                        </div>
                    @else
                        @livewire('front.quizzes.show', ['quiz' => $quiz])
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

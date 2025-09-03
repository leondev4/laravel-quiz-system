<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $quiz->title }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $quiz->questions->count() }} preguntas • 
                    @if($quiz->user)
                        Creado por {{ $quiz->user->name }}
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-3">
                @php
                    $status = $quiz->status ?? 'open';
                    $statusConfig = [
                        'open' => ['bg-green-100', 'text-green-800', 'Disponible'],
                        'upcoming' => ['bg-yellow-100', 'text-yellow-800', 'Próximamente'],
                        'closed' => ['bg-red-100', 'text-red-800', 'Cerrado']
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusConfig[$status][0] }} {{ $statusConfig[$status][1] }}">
                    {{ $statusConfig[$status][2] }}
                </span>
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ $quiz->title }}
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Información del Quiz -->
            @if($quiz->description)
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Acerca de este Quiz</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $quiz->description }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Alertas de Estado -->
            @if (!$quiz->public && !auth()->check())
                <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-white">Acceso Restringido</h3>
                            <p class="text-red-100 mt-1">
                                Este quiz está disponible solo para usuarios registrados.
                            </p>
                            <div class="mt-4 flex space-x-3">
                                <a href="{{ route('login') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-white text-red-600 rounded-lg hover:bg-red-50 transition-colors font-medium">
                                    Iniciar Sesión
                                </a>
                                <a href="{{ route('register') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800 transition-colors font-medium">
                                    Registrarse
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif (!$quiz->isOpen())
                <div class="bg-gradient-to-r {{ $quiz->status === 'upcoming' ? 'from-yellow-500 to-orange-500' : 'from-red-500 to-red-600' }} rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($quiz->status === 'upcoming')
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-white">
                                {{ $quiz->status === 'upcoming' ? 'Quiz Próximamente' : 'Quiz Cerrado' }}
                            </h3>
                            <p class="text-white opacity-90 mt-1">
                                @if($quiz->status === 'upcoming')
                                    Este quiz se abrirá el {{ $quiz->opens_at->format('d \d\e F, Y \a \l\a\s g:i A') }}
                                @else
                                    Este quiz se cerró el {{ $quiz->closes_at->format('d \d\e F, Y \a \l\a\s g:i A') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Información del Quiz Disponible -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $quiz->questions->count() }}</h3>
                            <p class="text-gray-600">{{ $quiz->questions->count() == 1 ? 'Pregunta' : 'Preguntas' }}</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Tiempo Variable</h3>
                            <p class="text-gray-600">Por pregunta</p>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Evaluación</h3>
                            <p class="text-gray-600">Instantánea</p>
                        </div>
                    </div>
                </div>

                <!-- Componente del Quiz -->
                <div class="bg-white rounded-xl shadow-lg">
                    @livewire('front.quizzes.show', ['quiz' => $quiz])
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

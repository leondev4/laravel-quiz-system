<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $editing ? 'Editar Quiz' : 'Crear Quiz' }}
        </h2>
    </x-slot>

    <x-slot name="title">
        {{ $editing ? 'Editar Quiz ' . $quiz->id : 'Crear Quiz' }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Quiz Details Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form wire:submit="save">
                        <div>
                            <x-input-label for="title" value="Título" />
                            <x-text-input wire:model.live="title" id="title" class="block mt-1 w-full" type="text"
                                name="title" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="slug" value="Slug" />
                            <x-text-input wire:model.live="slug" id="slug" class="block mt-1 w-full" type="text"
                                name="slug" disabled />
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" value="Descripción" />
                            <x-textarea wire:model.live="description" id="description" class="block mt-1 w-full"
                                type="text" name="description" />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Campo de materia OBLIGATORIO -->
                        <div class="mt-4">
                            <x-input-label for="subject_id" value="Materia *" class="font-semibold" />
                            <select wire:model.live="subject_id" id="subject_id" 
                                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required>
                                <option value="">-- Seleccionar materia --</option>
                                @foreach($listsForFields['subjects'] ?? [] as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-red-500">*</span> La materia es obligatoria para organizar los quizzes
                            </p>
                        </div>

                        <!-- Fechas de apertura y cierre -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <x-input-label for="opens_at" value="Fecha de Apertura" />
                                <input type="datetime-local" 
                                       wire:model.live="opens_at" 
                                       id="opens_at" 
                                       class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                <x-input-error :messages="$errors->get('opens_at')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">Dejar vacío para disponibilidad inmediata</p>
                            </div>
                            
                            <div>
                                <x-input-label for="closes_at" value="Fecha de Cierre" />
                                <input type="datetime-local" 
                                       wire:model.live="closes_at" 
                                       id="closes_at" 
                                       class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                                <x-input-error :messages="$errors->get('closes_at')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">Dejar vacío para sin expiración</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center">
                                <x-input-label for="published" value="Publicado" />
                                <input type="checkbox" id="published" class="mr-1 ml-2" wire:model.live="published">
                            </div>
                            <x-input-error :messages="$errors->get('published')" class="mt-2" />
                        </div>

                        {{-- <div class="mt-4">
                            <div class="flex items-center">
                                <x-input-label for="public" value="Público" />
                                <input type="checkbox" id="public" class="mr-1 ml-2" wire:model.live="public">
                            </div>
                            <x-input-error :messages="$errors->get('public')" class="mt-2" />
                        </div> --}}

                        <div class="mt-4">
                            <x-primary-button>
                                Guardar Detalles del Quiz
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            @if($editing && $quiz->exists)
                <livewire:quiz.question-manager :quiz="$quiz" wire:key="question-manager-{{ $quiz->id }}" />
            @endif
        </div>
    </div>
</div>
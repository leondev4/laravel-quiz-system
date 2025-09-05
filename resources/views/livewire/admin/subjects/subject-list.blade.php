<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Gestión de Materias
        </h2>
    </x-slot>

    <x-slot name="title">
        Gestión de Materias
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Header con búsqueda y botón crear -->
                    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1 max-w-md">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                Buscar materias
                            </label>
                            <input type="text" 
                                   id="search"
                                   wire:model.live.debounce.300ms="search" 
                                   placeholder="Buscar por nombre, código o descripción..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">
                                Total: {{ $subjects->total() }} materias
                            </span>
                            <button wire:click="openCreateModal" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Materia
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de materias -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <button wire:click="sortBy('id')" class="flex items-center space-x-1 text-xs font-medium uppercase tracking-wider text-gray-500 hover:text-gray-700">
                                            <span>ID</span>
                                            @if($sortField === 'id')
                                                @if($sortDirection === 'asc')
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <button wire:click="sortBy('code')" class="flex items-center space-x-1 text-xs font-medium uppercase tracking-wider text-gray-500 hover:text-gray-700">
                                            <span>Código</span>
                                            @if($sortField === 'code')
                                                @if($sortDirection === 'asc')
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <button wire:click="sortBy('name')" class="flex items-center space-x-1 text-xs font-medium uppercase tracking-wider text-gray-500 hover:text-gray-700">
                                            <span>Nombre</span>
                                            @if($sortField === 'name')
                                                @if($sortDirection === 'asc')
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Descripción</span>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Quizzes</span>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Estado</span>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 text-xs font-medium uppercase tracking-wider text-gray-500 hover:text-gray-700">
                                            <span>Creado</span>
                                            @if($sortField === 'created_at')
                                                @if($sortDirection === 'asc')
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                                                    </svg>
                                                @endif
                                            @endif
                                        </button>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($subjects as $subject)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $subject->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $subject->code }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="max-w-xs truncate">
                                                {{ $subject->description ?: 'Sin descripción' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $subject->quizzes_count }} quizzes
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($subject->active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Activa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Inactiva
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $subject->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <!-- Editar materia -->
                                                <button wire:click="openEditModal({{ $subject->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-green-500 text-white hover:bg-green-600 transition-colors"
                                                        title="Editar materia">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>

                                                <!-- Toggle estado -->
                                                <button wire:click="toggleActive({{ $subject->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent {{ $subject->active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-blue-500 hover:bg-blue-600' }} text-white transition-colors"
                                                        title="{{ $subject->active ? 'Desactivar' : 'Activar' }} materia">
                                                    @if($subject->active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    @endif
                                                </button>

                                                <!-- Eliminar materia -->
                                                <button wire:click="deleteSubject({{ $subject->id }})"
                                                        wire:confirm="¿Estás seguro de que quieres eliminar la materia '{{ $subject->name }}'? Esta acción no se puede deshacer."
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-red-500 text-white hover:bg-red-600 transition-colors"
                                                        title="Eliminar materia">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            @if($search)
                                                No se encontraron materias que coincidan con "{{ $search }}".
                                            @else
                                                No hay materias registradas aún.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($subjects->hasPages())
                        <div class="mt-6">
                            {{ $subjects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para CREAR materia -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Crear Nueva Materia</h3>
                        <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="createCode" class="block text-sm font-medium text-gray-700 mb-1">
                            Código de la Materia
                        </label>
                        <input type="text" 
                               id="createCode"
                               wire:model="code" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase"
                               placeholder="Ej: MAT101">
                        @error('code') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="createName" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre de la Materia
                        </label>
                        <input type="text" 
                               id="createName"
                               wire:model="name" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="createDescription" class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción (opcional)
                        </label>
                        <textarea id="createDescription"
                                  wire:model="description" 
                                  rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('description') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   wire:model="active" 
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Materia activa</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeCreateModal" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="createSubject" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                            Crear Materia
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para EDITAR materia -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Editar Materia</h3>
                        <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="editCode" class="block text-sm font-medium text-gray-700 mb-1">
                            Código de la Materia
                        </label>
                        <input type="text" 
                               id="editCode"
                               wire:model="code" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                        @error('code') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="editName" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre de la Materia
                        </label>
                        <input type="text" 
                               id="editName"
                               wire:model="name" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="editDescription" class="block text-sm font-medium text-gray-700 mb-1">
                            Descripción (opcional)
                        </label>
                        <textarea id="editDescription"
                                  wire:model="description" 
                                  rows="3"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('description') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   wire:model="active" 
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Materia activa</span>
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeEditModal" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="updateSubject" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                            Actualizar Materia
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Mensajes Flash -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
            {{ session('error') }}
        </div>
    @endif
</div>

<script>
    // Auto-hide flash messages
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const flashMessages = document.querySelectorAll('.fixed.top-4.right-4');
            flashMessages.forEach(function(message) {
                message.style.display = 'none';
            });
        }, 5000);
    });
</script>

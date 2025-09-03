{{-- filepath: resources/views/livewire/admin/users/user-list.blade.php --}}
<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Gestión de Usuarios (CRUD)
        </h2>
    </x-slot>

    <x-slot name="title">
        Gestión de Usuarios
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Header con búsqueda más grande y botón crear -->
                    <div class="mb-6 space-y-4">
                        <!-- Búsqueda más grande -->
                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                            <div class="flex-1 max-w-2xl">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                    Buscar usuarios
                                </label>
                                <input type="text" 
                                       id="search"
                                       wire:model.live.debounce.300ms="search" 
                                       placeholder="Buscar por nombre o email..."
                                       class="w-full h-12 text-lg rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4">
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600 whitespace-nowrap">
                                    Total: {{ $users->total() }} usuarios
                                </span>
                                <button wire:click="openCreateModal" 
                                        class="inline-flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Crear Usuario
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
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
                                        <button wire:click="sortBy('email')" class="flex items-center space-x-1 text-xs font-medium uppercase tracking-wider text-gray-500 hover:text-gray-700">
                                            <span>Email</span>
                                            @if($sortField === 'email')
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
                                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Verificado</span>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 text-xs font-medium uppercase tracking-wider text-gray-500 hover:text-gray-700">
                                            <span>Registrado</span>
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
                                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Quizzes</span>
                                    </th>
                                    <th class="px-6 py-3 text-left">
                                        <span class="text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $user->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($user->email_verified_at)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Verificado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Sin verificar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->tests->count() }} realizados
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <!-- Ver/Editar usuario -->
                                                <button wire:click="openEditModal({{ $user->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-green-500 text-white hover:bg-green-600 transition-colors"
                                                        title="Editar usuario">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>

                                                <!-- Resetear contraseña manual -->
                                                <button wire:click="openResetModal({{ $user->id }})"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-blue-500 text-white hover:bg-blue-600 transition-colors"
                                                        title="Resetear contraseña manual">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 01-2 2m2-2h-3m-9 1V9a2 2 0 012-2m0 0a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V9a2 2 0 012-2m0 0V7a2 2 0 012-2"/>
                                                    </svg>
                                                </button>

                                                <!-- NUEVO: Restablecer contraseña al email -->
                                                <button wire:click="resetPasswordToEmail({{ $user->id }})"
                                                        wire:confirm="¿Restablecer la contraseña del usuario '{{ $user->name }}' a su email: {{ $user->email }}?"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-purple-500 text-white hover:bg-purple-600 transition-colors"
                                                        title="Restablecer contraseña al email">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                                                    </svg>
                                                </button>

                                                <!-- Enviar enlace de reseteo -->
                                                <button wire:click="sendPasswordResetLink({{ $user->id }})"
                                                        wire:confirm="¿Enviar enlace de reseteo de contraseña a {{ $user->email }}?"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-yellow-500 text-white hover:bg-yellow-600 transition-colors"
                                                        title="Enviar enlace de reseteo">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                </button>

                                                <!-- Eliminar usuario -->
                                                <button wire:click="deleteUser({{ $user->id }})"
                                                        wire:confirm="¿Estás seguro de que quieres eliminar al usuario '{{ $user->name }}'? Esta acción no se puede deshacer."
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-red-500 text-white hover:bg-red-600 transition-colors"
                                                        title="Eliminar usuario">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            @if($search)
                                                No se encontraron usuarios que coincidan con "{{ $search }}".
                                            @else
                                                No hay usuarios registrados aún.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($users->hasPages())
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para CREAR usuario -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Crear Nuevo Usuario</h3>
                        <button wire:click="closeCreateModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="createName" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre
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
                        <label for="createEmail" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" 
                               id="createEmail"
                               wire:model="email" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="createPassword" class="block text-sm font-medium text-gray-700 mb-1">
                            Contraseña
                        </label>
                        <input type="password" 
                               id="createPassword"
                               wire:model="newPassword" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Mínimo 8 caracteres">
                        @error('newPassword') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="createConfirmPassword" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar Contraseña
                        </label>
                        <input type="password" 
                               id="createConfirmPassword"
                               wire:model="confirmPassword" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('confirmPassword') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeCreateModal" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="createUser" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                            Crear Usuario
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para EDITAR usuario -->
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Editar Usuario</h3>
                        <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="editName" class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre
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
                        <label for="editEmail" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" 
                               id="editEmail"
                               wire:model="email" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="editPassword" class="block text-sm font-medium text-gray-700 mb-1">
                            Nueva Contraseña (opcional)
                        </label>
                        <input type="password" 
                               id="editPassword"
                               wire:model="newPassword" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Dejar vacío para mantener actual">
                        @error('newPassword') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    @if($newPassword)
                        <div class="mb-6">
                            <label for="editConfirmPassword" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirmar Nueva Contraseña
                            </label>
                            <input type="password" 
                                   id="editConfirmPassword"
                                   wire:model="confirmPassword" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('confirmPassword') 
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    @endif

                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeEditModal" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="updateUser" 
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                            Actualizar Usuario
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para resetear contraseña -->
    @if($showResetModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Resetear Contraseña</h3>
                        <button wire:click="closeResetModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">
                            Nueva Contraseña
                        </label>
                        <input type="password" 
                               id="newPassword"
                               wire:model="newPassword" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               placeholder="Mínimo 8 caracteres">
                        @error('newPassword') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirmar Contraseña
                        </label>
                        <input type="password" 
                               id="confirmPassword"
                               wire:model="confirmPassword" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('confirmPassword') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button wire:click="closeResetModal" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button wire:click="resetPassword" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                            Resetear Contraseña
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
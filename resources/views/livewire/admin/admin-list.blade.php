<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Administradores
        </h2>
    </x-slot>

    <x-slot name="title">
        Administradores
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('admin.create') }}"
                            class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">
                            Crear Administrador
                        </a>
                    </div>

                    <div class="mb-4 min-w-full overflow-hidden overflow-x-auto align-middle sm:rounded-md">
                        <table class="min-w-full border divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="w-16 bg-gray-50 px-6 py-3 text-left">
                                        <span
                                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">ID</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span
                                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Nombre</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span
                                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Email</span>
                                    </th>
                                    <th class="bg-gray-50 px-6 py-3 text-left">
                                        <span
                                            class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500"></span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @forelse($admins as $admin)
                                    <tr class="bg-white">
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $admin->id }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $admin->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $admin->email }}
                                        </td>
                                        <td>
                                            <button wire:click="delete({{ $admin->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md border border-transparent bg-red-500 text-white hover:bg-red-600 transition-colors"
                                                title="Eliminar administrador"
                                                onclick="return confirm('¿Estás seguro de que quieres eliminar este administrador?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-6 py-4 text-center leading-5 text-gray-900 whitespace-no-wrap">
                                            No hay administradores registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

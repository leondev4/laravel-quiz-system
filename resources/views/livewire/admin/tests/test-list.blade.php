<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Test List
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center">
                        <select class="p-3 w-1/2 text-sm leading-5 rounded border-0 shadow text-slate-600"
                            wire:model.live="quiz_id">
                            <option value="0">All quizzes</option>
                            @foreach ($quizzes as $quiz)
                                <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                            @endforeach
                        </select>
                        
                        <div class="flex gap-2">
                            <x-danger-button 
                                wire:click="deleteOldTests(30)"
                                wire:confirm="Are you sure you want to delete tests older than 30 days?"
                                class="text-xs">
                                Delete tests older than 30 days
                            </x-danger-button>
                            
                            <x-danger-button 
                                wire:click="deleteOldTests(90)"
                                wire:confirm="Are you sure you want to delete tests older than 90 days?"
                                class="text-xs">
                                Delete tests older than 90 days
                            </x-danger-button>
                        </div>
                    </div>

                    <table class="table mt-4 w-full table-view">
                        <thead>
                            <tr>
                                <th class="w-16 bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">ID</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">User</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Quiz</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Result</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">IP
                                        Address</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Time
                                        Spent</span>
                                </th>
                                <th class="bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Created</span>
                                </th>
                                <th class="w-40 bg-gray-50 px-6 py-3 text-left">
                                    <span
                                        class="text-xs font-medium uppercase leading-4 tracking-wider text-gray-500">Actions</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                            @forelse($tests as $test)
                                <tr class="bg-white">
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->id }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->user->name ?? 'Guest' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->quiz?->title }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->result . '/' . $test->questions_count }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->ip_address }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ intval($test->time_spent / 60) }}:{{ gmdate('s', $test->time_spent) }}
                                        minutes
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        {{ $test->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                        <div class="flex gap-2">
                                            <a href="{{ route('results.show', $test) }}"
                                                class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                                View
                                            </a>
                                            <button 
                                                wire:click="deleteTest({{ $test->id }})"
                                                wire:confirm="Are you sure you want to delete this test?"
                                                class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"
                                        class="px-6 py-4 text-center leading-5 text-gray-900 whitespace-no-wrap">
                                        No tests were found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $tests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

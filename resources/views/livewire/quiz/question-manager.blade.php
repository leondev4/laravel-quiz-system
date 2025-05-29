<div>
    <!-- Current Quiz Questions -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Quiz Questions ({{ $this->currentQuestions->count() }})</h3>
                <x-primary-button wire:click="openAddModal">
                    Add Questions
                </x-primary-button>
            </div>
            
            @if($this->currentQuestions->isEmpty())
                <div class="text-gray-500 text-center py-8">
                    No questions added to this quiz yet.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($this->currentQuestions as $question)
                        <div class="border rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $question->text }}</p>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $question->options->where('correct', true)->count() }} correct answer(s)
                                        </span>
                                        <span class="ml-2 text-gray-500">
                                            @if($question->options->isNotEmpty())
                                                Latest answer: {{ $question->options->first()->created_at->diffForHumans() }}
                                            @else
                                                No answers yet
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <button wire:click="removeQuestion({{ $question->id }})" 
                                        wire:confirm="Are you sure you want to remove this question from the quiz?"
                                        class="ml-4 text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Add Questions Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Add Questions to Quiz</h3>
                        <button wire:click="closeAddModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search Questions</label>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="search" 
                                   placeholder="Search by question text..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Answer From Date</label>
                            <input type="date" 
                                   wire:model.live="dateFrom"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Answer To Date</label>
                            <input type="date" 
                                   wire:model.live="dateTo"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Selection Controls -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex space-x-2">
                            <button wire:click="selectAllVisible" 
                                    class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200">
                                Select All Visible
                            </button>
                            <button wire:click="clearSelection" 
                                    class="text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200">
                                Clear Selection ({{ count($selectedQuestions) }})
                            </button>
                            <button wire:click="clearFilters" 
                                    class="text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded hover:bg-gray-200">
                                Clear Filters
                            </button>
                        </div>
                        <span class="text-sm text-gray-600">
                            {{ count($selectedQuestions) }} question(s) selected
                        </span>
                    </div>

                    <!-- Questions List -->
                    <div class="max-h-96 overflow-y-auto border rounded-lg">
                        @if($this->availableQuestions->isEmpty())
                            <div class="text-gray-500 text-center py-8">
                                No questions found matching your criteria.
                            </div>
                        @else
                            @foreach($this->availableQuestions as $question)
                                <div class="border-b last:border-b-0 p-4 hover:bg-gray-50">
                                    <div class="flex items-start space-x-3">
                                        <input type="checkbox" 
                                               wire:click="toggleQuestionSelection({{ $question->id }})"
                                               @checked(in_array($question->id, $selectedQuestions))
                                               class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $question->text }}</p>
                                            <div class="mt-2 text-sm text-gray-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $question->options->where('correct', true)->count() }} correct answer(s)
                                                </span>
                                                <span class="ml-2 text-gray-500">
                                                    @if($question->options->isNotEmpty())
                                                        Latest answer: {{ $question->options->first()->created_at->diffForHumans() }}
                                                    @else
                                                        No answers yet
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if($this->availableQuestions->hasPages())
                        <div class="mt-4">
                            {{ $this->availableQuestions->links() }}
                        </div>
                    @endif

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-2 mt-6 pt-4 border-t">
                        <x-secondary-button wire:click="closeAddModal">
                            Cancel
                        </x-secondary-button>
                        <x-primary-button wire:click="addSelectedQuestions" 
                                         :disabled="empty($selectedQuestions)">
                            Add Selected Questions ({{ count($selectedQuestions) }})
                        </x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
</div>
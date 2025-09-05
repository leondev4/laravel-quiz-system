<?php

namespace App\Livewire\Admin\Subjects;

use App\Models\Subject;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    
    // Form properties
    public $selectedSubjectId = null;
    public $name = '';
    public $description = '';
    public $code = '';
    public $active = true;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'code' => 'required|string|max:10|unique:subjects,code',
        'active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // CREATE SUBJECT
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
        $this->resetErrorBag();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function createSubject()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:10|unique:subjects,code',
            'active' => 'boolean',
        ]);

        Subject::create([
            'name' => $this->name,
            'description' => $this->description,
            'code' => strtoupper($this->code),
            'active' => $this->active,
            'user_id' => auth()->id(),
        ]);

        session()->flash('success', "Materia '{$this->name}' creada exitosamente.");
        $this->closeCreateModal();
        $this->resetPage();
    }

    // UPDATE SUBJECT
    public function openEditModal($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        
        $this->selectedSubjectId = $subjectId;
        $this->name = $subject->name;
        $this->description = $subject->description;
        $this->code = $subject->code;
        $this->active = $subject->active;
        $this->showEditModal = true;
        $this->resetErrorBag();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function updateSubject()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:10|unique:subjects,code,' . $this->selectedSubjectId,
            'active' => 'boolean',
        ]);

        $subject = Subject::findOrFail($this->selectedSubjectId);
        
        $subject->update([
            'name' => $this->name,
            'description' => $this->description,
            'code' => strtoupper($this->code),
            'active' => $this->active,
        ]);

        session()->flash('success', "Materia '{$subject->name}' actualizada exitosamente.");
        $this->closeEditModal();
    }

    // DELETE SUBJECT
    public function deleteSubject($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        
        // Verificar si tiene quizzes asociados
        if ($subject->quizzes()->count() > 0) {
            session()->flash('error', 'No se puede eliminar la materia porque tiene quizzes asociados.');
            return;
        }

        $subjectName = $subject->name;
        $subject->delete();

        session()->flash('success', "Materia '{$subjectName}' eliminada exitosamente.");
        $this->resetPage();
    }

    // TOGGLE ACTIVE
    public function toggleActive($subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $subject->update(['active' => !$subject->active]);
        
        $status = $subject->active ? 'activada' : 'desactivada';
        session()->flash('success', "Materia '{$subject->name}' {$status} exitosamente.");
    }

    private function resetForm()
    {
        $this->selectedSubjectId = null;
        $this->name = '';
        $this->description = '';
        $this->code = '';
        $this->active = true;
    }

    public function render(): View
    {
        $subjects = Subject::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount(['quizzes', 'questions']) // Agregar conteo de preguntas
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.subjects.subject-list', [
            'subjects' => $subjects
        ]);
    }
}

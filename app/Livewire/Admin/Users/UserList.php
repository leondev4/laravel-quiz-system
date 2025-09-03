<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showResetModal = false;
    
    // Form properties
    public $selectedUserId = null;
    public $name = '';
    public $email = '';
    public $newPassword = '';
    public $confirmPassword = '';
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'newPassword' => 'nullable|min:8',
        'confirmPassword' => 'nullable|same:newPassword',
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

    // CREATE USER
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

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->newPassword),
            'is_admin' => false,
        ]);

        session()->flash('success', "Usuario '{$this->name}' creado exitosamente.");
        $this->closeCreateModal();
        $this->resetPage();
    }

    // READ/VIEW USER
    public function viewUser($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->is_admin) {
            session()->flash('error', 'No se puede ver información de un administrador.');
            return;
        }

        $this->selectedUserId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->showEditModal = true;
        $this->resetErrorBag();
    }

    // UPDATE USER
    public function openEditModal($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->is_admin) {
            session()->flash('error', 'No se puede editar un administrador.');
            return;
        }

        $this->selectedUserId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->newPassword = '';
        $this->confirmPassword = '';
        $this->showEditModal = true;
        $this->resetErrorBag();
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function updateUser()
    {
        $user = User::findOrFail($this->selectedUserId);
        
        if ($user->is_admin) {
            session()->flash('error', 'No se puede editar un administrador.');
            $this->closeEditModal();
            return;
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->selectedUserId,
        ];

        if ($this->newPassword) {
            $rules['newPassword'] = 'min:8';
            $rules['confirmPassword'] = 'same:newPassword';
        }

        $this->validate($rules);

        $updateData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->newPassword) {
            $updateData['password'] = Hash::make($this->newPassword);
        }

        $user->update($updateData);

        session()->flash('success', "Usuario '{$user->name}' actualizado exitosamente.");
        $this->closeEditModal();
    }

    // DELETE USER
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Verificar que no sea administrador
        if ($user->is_admin) {
            session()->flash('error', 'No se puede eliminar un usuario administrador.');
            return;
        }

        // Verificar que no sea el usuario actual
        if ($user->id === auth()->id()) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta.');
            return;
        }

        $userName = $user->name;
        $user->delete();

        session()->flash('success', "Usuario '{$userName}' eliminado exitosamente.");
        $this->resetPage();
    }

    // RESET PASSWORD
    public function openResetModal($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->is_admin) {
            session()->flash('error', 'No se puede resetear la contraseña de un administrador.');
            return;
        }

        $this->selectedUserId = $userId;
        $this->newPassword = '';
        $this->confirmPassword = '';
        $this->showResetModal = true;
        $this->resetErrorBag();
    }

    public function closeResetModal()
    {
        $this->showResetModal = false;
        $this->selectedUserId = null;
        $this->newPassword = '';
        $this->confirmPassword = '';
        $this->resetErrorBag();
    }

    public function resetPassword()
    {
        $this->validate([
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        
        if ($user->is_admin) {
            session()->flash('error', 'No se puede resetear la contraseña de un administrador.');
            $this->closeResetModal();
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        session()->flash('success', "Contraseña del usuario '{$user->name}' reseteeada exitosamente.");
        $this->closeResetModal();
    }

    public function sendPasswordResetLink($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->is_admin) {
            session()->flash('error', 'No se puede enviar enlace de reseteo a un administrador.');
            return;
        }

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('success', "Enlace de reseteo enviado a {$user->email}.");
        } else {
            session()->flash('error', 'Error al enviar el enlace de reseteo.');
        }
    }

    // NUEVA FUNCIÓN: Restablecer contraseña automáticamente al email
    public function resetPasswordToEmail($userId)
    {
        $user = User::findOrFail($userId);
        
        if ($user->is_admin) {
            session()->flash('error', 'No se puede resetear la contraseña de un administrador.');
            return;
        }

        // Generar nueva contraseña usando el email del usuario
        $newPassword = $user->email;
        
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        session()->flash('success', "Contraseña del usuario '{$user->name}' restablecida a su email: {$user->email}");
    }

    private function resetForm()
    {
        $this->selectedUserId = null;
        $this->name = '';
        $this->email = '';
        $this->newPassword = '';
        $this->confirmPassword = '';
    }

    public function render(): View
    {
        $users = User::query()
            ->where('is_admin', false)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.users.user-list', [
            'users' => $users
        ]);
    }
}

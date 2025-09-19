<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UserCell extends Component
{
    public User $user;
    public bool $isAdmin;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->isAdmin = $user->is_admin;
    }

    public function updateIsAdmin(): void
    {
        $this->authorize('update', $this->user);
        $this->user->is_admin = $this->isAdmin;
        $this->user->save();
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->user);
        $this->user->delete();
    }
}

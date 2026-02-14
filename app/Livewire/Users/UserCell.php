<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use const App\Models\ADMIN_LEVEL;
use const App\Models\MAVEN_EDITOR_LEVEL;

class UserCell extends Component
{
    public array $permissionLevelNames = [
        'User', 'Maven Editor', 'Admin'
    ];

    public User $user;
    public int $permissionLevel;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->permissionLevel = $user->permission_level;
    }

    public function updatePermissionLevel(): void
    {
        $this->authorize('update', $this->user);
        $this->user->permission_level = $this->permissionLevel;
        $this->user->save();
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->user);
        $this->user->delete();
    }
}

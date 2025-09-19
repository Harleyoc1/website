<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UserIndex extends Component
{
    public $users;

    public string $newUserEmail;
    public bool $newUserIsAdmin;

    public function mount(): void
    {
        $this->users = User::all();
    }

    public function add(): void
    {
        // TODO: implement
    }

}

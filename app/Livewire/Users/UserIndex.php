<?php

namespace App\Livewire\Users;

use App\Auth\Registration\Registrant;
use App\Auth\Registration\RegistrationTokenRepository;
use App\Models\User;
use Livewire\Component;

class UserIndex extends Component
{
    public $users;

    public string $newUserEmail = '';
    public bool $newUserIsAdmin = false;

    public function mount(): void
    {
        $this->users = User::all();
    }

    public function add(): void
    {
        $this->validate([
            'newUserEmail' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email']
        ]);

        $token = RegistrationTokenRepository::get()->create(new Registrant($this->newUserEmail, $this->newUserIsAdmin));

        // TODO: send the email

        session()->flash('token', $token);

        // Close modal and reset fields
        $this->modal('addUser')->close();
        $this->newUserEmail = '';
        $this->newUserIsAdmin = false;
    }

}

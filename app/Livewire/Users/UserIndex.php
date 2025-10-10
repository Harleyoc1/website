<?php

namespace App\Livewire\Users;

use App\Auth\Registration\Registrant;
use App\Auth\Registration\RegistrationTokenRepository;
use App\Mail\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Users')]
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

        Mail::to($this->newUserEmail)->send(new Registration($this->newUserEmail, $token));

        session()->flash('success');

        // Close modal and reset fields
        $this->modal('addUser')->close();
        $this->newUserEmail = '';
        $this->newUserIsAdmin = false;
    }

}

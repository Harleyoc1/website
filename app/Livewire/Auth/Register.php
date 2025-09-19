<?php

namespace App\Livewire\Auth;

use App\Auth\Registration\RegistrationTokenRepository;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.auth', ['title' => 'Register'])]
class Register extends Component
{
    #[Locked]
    public string $token = '';

    public string $name = '';

    #[Locked]
    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $registrant = RegistrationTokenRepository::get()->validate($this->email, $this->token);
        if (!$registrant) {
            $this->addError('email', 'Token is invalid or expired.');
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        // Since the user needs a token to register, we can consider this an email verification
        $user->markEmailAsVerified();

        if ($registrant->isAdmin()) {
            $user->is_admin = true;
            $user->save();
        }
        RegistrationTokenRepository::get()->deleteExisting($this->email);

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}

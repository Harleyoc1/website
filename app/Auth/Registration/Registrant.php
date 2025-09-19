<?php

namespace App\Auth\Registration;

class Registrant
{

    public function __construct(
        private string $email,
        private bool $isAdmin
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

}

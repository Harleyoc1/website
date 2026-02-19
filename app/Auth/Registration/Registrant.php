<?php

namespace App\Auth\Registration;

class Registrant
{

    public function __construct(
        private string $email,
        private int $permissionLevel
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPermissionLevel(): int
    {
        return $this->permissionLevel;
    }

}

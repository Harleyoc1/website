<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

function authenticate_http_user(): bool {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    $user = User::where('email', $username)->first();
    return $user && Hash::check($password, $user->password);
}

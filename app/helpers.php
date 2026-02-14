<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

function authenticated_http_user(): User|false {
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    $user = User::where('email', $username)->first();
    if (isset($user) and Hash::check($password, $user->password)) {
        return $user;
    }
    return false;
}

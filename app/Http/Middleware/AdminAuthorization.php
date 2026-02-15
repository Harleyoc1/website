<?php

namespace App\Http\Middleware;

use const App\Models\ADMIN_LEVEL;

class AdminAuthorization extends PermissionLevelAuthorization
{
    protected function getRequiredLevel(): int
    {
        return ADMIN_LEVEL;
    }

}

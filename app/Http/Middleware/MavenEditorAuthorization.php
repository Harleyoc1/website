<?php

namespace App\Http\Middleware;

use const App\Models\MAVEN_EDITOR_LEVEL;

class MavenEditorAuthorization extends PermissionLevelAuthorization
{
    protected function getRequiredLevel(): int
    {
        return MAVEN_EDITOR_LEVEL;
    }

}

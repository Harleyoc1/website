<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

/**
 * Project policy is quite simple at the moment: any users can view any projects, and admins can use any CRUD
 * operation on all projects.
 */
class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return isset($user) && $user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return isset($user) && $user->is_admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return isset($user) && $user->is_admin;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Notebook;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotebookPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return in_array($user->role, ['user', 'admin']);
    }

    public function update(User $user, Notebook $notebook)
    {
        return $user->role === 'admin' || $notebook->user_id === $user->id;
    }

    public function delete(User $user, Notebook $notebook)
    {
        return $user->role === 'admin';
    }
}
<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function delete(User $currentUser, User $user)
    {
        return $currentUser->isAdmin() && $currentUser->id !== $user->id;
    }
}
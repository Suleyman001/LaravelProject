<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    use HandlesAuthorization;

    public function delete(User $currentUser, User $user)
    {
        $allowed = $currentUser->isAdmin() && $currentUser->id !== $user->id;
        
        Log::info('User Delete Authorization', [
            'current_user_id' => $currentUser->id,
            'current_user_role' => $currentUser->role,
            'target_user_id' => $user->id,
            'allowed' => $allowed
        ]);

        return $allowed;
    }

    // Optional: Method to manage user profiles
    public function update(User $currentUser, User $user)
    {
        return $currentUser->isAdmin() || $currentUser->id === $user->id;
    }

    // Optional: Method to view user list
    public function viewAny(User $currentUser)
    {
        return $currentUser->isAdmin();
    }
}
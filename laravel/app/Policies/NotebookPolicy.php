<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Notebook;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class NotebookPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        $allowed = in_array($user->role, ['user', 'admin']);
        
        Log::info('Notebook Create Authorization', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'allowed' => $allowed
        ]);

        return $allowed;
    }


    public function update(User $user, Notebook $notebook)
    {
        $allowed = $user->isAdmin() || $notebook->user_id === $user->id;
        
        Log::info('Notebook Update Authorization', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'notebook_id' => $notebook->id,
            'notebook_owner_id' => $notebook->user_id,
            'allowed' => $allowed
        ]);

        return $user->isAdmin() || $notebook->user_id === $user->id;
    }

    public function delete(User $user, Notebook $notebook)
    {
        $allowed = $user->isAdmin();
        
        Log::info('Notebook Delete Authorization', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'notebook_id' => $notebook->id,
            'allowed' => $allowed
        ]);

        return $user->isAdmin() || $notebook->user_id === $user->id;
    }

    // Optional: View method to control visibility
    public function view(User $user, Notebook $notebook)
    {
        // Everyone can view, but admin can see all
        return $user->isAdmin() || $notebook->user_id === $user->id;
    }
}
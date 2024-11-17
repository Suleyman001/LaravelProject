<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Notebook;
use App\Policies\NotebookPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Notebook::class => NotebookPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
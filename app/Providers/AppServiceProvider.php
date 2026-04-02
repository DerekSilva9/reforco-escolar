<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Policies\Gate;
use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use App\Policies\StudentPolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate as GateFacade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        GateFacade::policy(Student::class, StudentPolicy::class);
        GateFacade::policy(Team::class, TeamPolicy::class);
        GateFacade::policy(User::class, UserPolicy::class);
    }
}

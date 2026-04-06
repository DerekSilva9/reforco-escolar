<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Policies\Gate;
use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use App\Models\Notice;
use App\Policies\StudentPolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPolicy;
use App\Policies\FinancePolicy;
use App\Policies\AttendancePolicy;
use App\Policies\NoticePolicy;
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
        GateFacade::policy(Notice::class, NoticePolicy::class);
        GateFacade::define('finance-view', function (User $user) {
            return $user->isAdmin();
        });
        GateFacade::define('finance-create', function (User $user) {
            return $user->isAdmin();
        });
        GateFacade::define('attendance-view', function (User $user) {
            return $user->isAdmin() || $user->isProfessor();
        });
    }
}


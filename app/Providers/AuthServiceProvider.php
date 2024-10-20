<?php

namespace App\Providers;

use App\Models\Schoolsadmin;
use Illuminate\Support\Facades\Gate;
use App\Models\Schoolsinstitutions;
use App\Policies\SchoolsadminPolicy;
use App\Policies\SchoolsinstitutionsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Schoolsinstitutions::class => SchoolsinstitutionsPolicy::class,
        Schoolsadmin::class => SchoolsadminPolicy::class,
        \App\Models\Parents::class => \App\Policies\ParentsPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

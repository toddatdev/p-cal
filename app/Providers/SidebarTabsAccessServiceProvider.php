<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Symfony\Component\Console\Input\Input;

class SidebarTabsAccessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $allTabs = ['roles', 'employees', 'commissions', 'projects', 'admin settings'];

        foreach ($allTabs as $tab) {

            Gate::define($tab, function (User $user) use ($tab) {
                if (empty($user->role_id)) {

                    return 1;
                }
                else {

                    $permission = Permission::where('name', 'LIKE', '%'.$tab.'%')->first();
                    $role = Role::find($user->role_id);

                    return $role && $role->hasPermission($permission);
                }

            });
        }

    }
}

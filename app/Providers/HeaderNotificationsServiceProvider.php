<?php

namespace App\Providers;

use App\Models\CompletedProject;
use App\Models\EarningApproval;
use App\Models\Notification;
use App\Models\ProjectApprovalCommissions;
use App\Models\ProjectsApproval;
use App\Models\Role;
use App\Models\StopEarning;
use App\Models\StopEarningApproval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class HeaderNotificationsServiceProvider extends ServiceProvider
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
        View::composer('admin.layouts.partials.header', function ($view) {

            $authInfo = Auth::user();
            $userRole = $authInfo['role_id'];
            $notificationsExist = false;
            $user['name'] = $authInfo->first_name . ' ' . $authInfo->last_name;
            $user['role'] = Role::where('id', $userRole)->pluck('name')->first();
            if (empty($user['role']) && $user['name'] !== 'Super Admin') {
                $user['role'] = 'Super Admin';
            }

            if (empty($userRole)) {

                $projectNotifications = ProjectsApproval::where('is_read', 0)->exists();
                $earningNotifications = EarningApproval::where('is_read', 0)->exists();
                $completeProjectNotification = CompletedProject::where('is_read', 0)->exists();
                $commissionNotifications = ProjectApprovalCommissions::where(['is_read' => 0])->whereNotNull('project_id')->exists();
                $stopEarningNotifications = StopEarningApproval::where('is_read', 0)->exists();
                $notificationsExist = $projectNotifications || $earningNotifications || $commissionNotifications || $stopEarningNotifications || $completeProjectNotification;
            }
            else {

                $notificationsExist = Notification::where('is_read', 0)->where('notification_for', $authInfo['id'])->exists();
            }

            $view->with(['notificationsExist' => $notificationsExist, 'userName' => $user['name'], 'userRole' => $user['role']]);

        });
    }
}

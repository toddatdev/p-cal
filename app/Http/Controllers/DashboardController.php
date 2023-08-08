<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $activeUser = Auth::user();
        $roleCount = Role::where('status', 0)->where('is_del', 0)->count();
        $employeeCount = User::where('role_id', '!=', null)->where('is_del', 0)->count();
        $projectCount = Project::where('status', 0)->where('is_del', 0)->count();
        $projects = ProjectUser::where('status', 0)->where('is_del', 0)->where('user_id', $activeUser['id'])->with(['project', 'role'])->get();

        $myEarning = 0;
        $remainingEarning = 0;
        $totalEarning = 0;

        if (!empty($activeUser->role_id)) {
            $loggedInUserRole = strtolower($activeUser->role->name);

            $earnings = $projects->flatMap(function ($project) {
                return $project->project->earnings->where('is_del', 0);
            });

            $totalEarning = $earnings->sum(function ($earning) {
                return $earning->earning;
            });

            if ($loggedInUserRole === 'hod') {
                $myEarning = $earnings->sum('hod_commission');
                $remainingEarning = $earnings->sum('manager_commission') + $earnings->sum('employee_commission');
            } elseif ($loggedInUserRole === 'manager') {
                $myEarning = $earnings->sum('manager_commission');
                $remainingEarning = $earnings->sum('employee_commission');
            } else {
                $myEarning = $earnings->sum('employee_commission');
            }
        }


        return view('admin.index', compact('roleCount', 'employeeCount', 'projectCount', 'activeUser', 'myEarning', 'remainingEarning', 'totalEarning'));
    }

}

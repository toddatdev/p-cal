<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\CompletedProject;
use App\Models\Earning;
use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectApprovalCommissions;
use App\Models\ProjectApprovalUsers;
use App\Models\ProjectCommissions;
use App\Models\ProjectsApproval;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{

    /**
     * @param Request $request
     * @return Factory|View|RedirectResponse|Application
     */
    public function index(Request $request): Factory|View|RedirectResponse|Application
    {
        try {
            if($request['status'] == 'completed'){
                $projects = Project::where('status', 1)->where('is_del', 0)->with(['sale', 'type', 'platform', 'commission']);
            }
            elseif ($request['status'] == 'in-progress'){
                $projects = Project::where('status', 0)->where('is_del', 0)->with(['sale', 'type', 'platform', 'commission']);
            }
            else{
                $projects = Project::where('is_del', 0)->with(['sale', 'type', 'platform', 'commission']);
            }
            /* Start: Search functionality for search in header */
            if (!empty($request['header_search'])) {

                $headerSearch = $request['header_search'];
                $projects = $projects->where(function ($query) use ($headerSearch)  {
                    $query->where('client_name', 'like', '%'.$headerSearch.'%')
                        ->orWhere('job_title', 'like', '%'.$headerSearch.'%');
                });
            }

            $activeUser = Auth::user();

            if (!empty($activeUser['role_id'])) {

                $projects = $projects->whereHas('projectUser', function ($query) use ($activeUser) {
                    $query->where('user_id', $activeUser['id']);
                });
            }

            /* End: Search functionality for search in header */

            $projects = $projects->paginate(10);
            $projects = $this->addSerialNumber($projects);
            $users = User::where('role_id', '!=', '')->where('is_del', 0)->get();
            $salesPersons = AdminSetting::where('setting_type', 'sales_person')->where('status', 0)->where('is_del', 0)->get();
            $platforms = AdminSetting::where('setting_type', 'platform')->where('status', 0)->where('is_del', 0)->get();
            $types = AdminSetting::where('setting_type', 'type')->where('status', 0)->where('is_del', 0)->get();

            $requestStatus = $request['status'];

            $currentUser = Auth::user();
            $activeUser = User::select('role_id')->where('id', Auth::user()->id)->first();
            if (empty($activeUser['role_id']))
                $activeUser = '1'; // admin
            else
                $activeUser = '2'; // every other role

            $loggedInUser = User::where('id', $currentUser['id'])->with(['role'])->first();

            return view('admin.projects.index', compact('projects', 'users', 'salesPersons', 'platforms', 'types', 'activeUser','currentUser','requestStatus', 'loggedInUser'));
        }
        catch (\Exception $e) {

            return redirect()->route('dashboard')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function parentUsersList(Request $request): JsonResponse
    {

        try {

            $userID = $request['user_id'];
            $checkProjectUsers = ProjectUser::where('user_id', $userID)->where('project_id', $request['project_id'])->first();
            if (!empty($checkProjectUsers)) {
                $userInfo = ProjectUser::select('role_id', 'parent_id')->where('user_id', $userID)->where('project_id', $request['project_id'])->with(['role', 'parentUser'])->first();
                $userInfo['parent_user_id'] = $userInfo['parent_id'];
                $parentRole = false;
                if (!empty($userInfo['role']->parent_id)) {
                    $parentRole = Role::select('id', 'name')->where('id', $userInfo['role']['parent_id'])->with(['users'])->first();
                }
            }
            else {

                $userInfo = User::select('role_id', 'parent_user_id')->where('id', $userID)->with(['role', 'parentUser'])->first();
                $parentRole = false;
                if (!empty($userInfo['role']->parent_id)) {
                    $parentRole = Role::select('id', 'name')->where('id', $userInfo['role']['parent_id'])->with(['users'])->first();
                }
            }


            return response()->json(['success' => True, 'parentRoleUsersList' => $parentRole ? $parentRole['users'] : false, 'currentParentUser' => $userInfo['parent_user_id'], 'parentRoleName' => $parentRole ? $parentRole['name'] : false, 'parentUserParent' => $userInfo['parentUser']]);
        }
        catch (\Exception $e) {

            return response()->json(['success' => False, 'message' => 'Something went wrong. Please try again later.']);

        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request): JsonResponse
    {

        try {

            $projectID = $request['project_id'];
            $project = Project::where('id', $projectID)->where('status', 0)->where('is_del', 0)->with(['sale', 'type', 'platform', 'commission'])->first();
            $project['user_id'] = $project->projectEmployee()->id;
            if (empty($project))
                return response()->json(['success' => false, 'Invalid data for project.']);

            return response()->json(['success' => true, 'project' => $project]);
        } catch (\Exception $e) {

//            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
            return response()->json(['success' => false, 'message' => $e->getMessage()]);

        }
    }


    /**
     * @param $id
     * @return Factory|View|RedirectResponse|Application
     */
    public function projectDetails($id): Factory|View|RedirectResponse|Application
    {

        try {
            $projectID = base64_decode($id);
            $project = Project::where('id', $projectID)
                ->where('is_del', 0);

            $activeUser = Auth::user();

            if (!empty($activeUser['role_id'])) {
                $project = $project->
                whereHas('projectUser', function ($query) use ($activeUser) {
                    $query->where('user_id', $activeUser['id']);
                });
            }

            $project = $project->with(['commission'])->first();
            $totalEarning = $project->total_earning;
            $earnings = Earning::where('status', 0)->where('is_del', 0)->where('project_id', $project->id)->get();
            $employeeCommissionTotal = (new Earning)->calculateCommissionTotal('employee_commission', 'employee_commission_by_exg_rate', $project['id']);
            $managerCommissionTotal = (new Earning)->calculateCommissionTotal('manager_commission', 'manager_commission_by_exg_rate', $project['id']);
            $hodCommissionTotal = (new Earning)->calculateCommissionTotal('hod_commission', 'hod_commission_by_exg_rate', $project['id']);

            $currentUser = Auth::user();
            /* Stop Earning */

            $months = [];

            $currentDate = Carbon::now();
            $projectStartDate = Carbon::parse($project->start_date);
            $projectEndDate = Carbon::parse($project->end_date);

            if ($projectStartDate > $currentDate) {
                $diffInMonths = $projectStartDate->diffInMonths($projectEndDate, false);

                for ($i = 0; $i <= $diffInMonths; $i++) {
                    $currentMonth = $projectStartDate->copy()->addMonths($i);
                    $monthName = $currentMonth->format('F');
                    $months[] = $monthName;
                }
            } else {
                $diffInMonths = $currentDate->diffInMonths($projectEndDate, false);

                for ($i = 0; $i <= $diffInMonths; $i++) {
                    $currentMonth = $currentDate->copy()->addMonths($i);
                    $monthName = $currentMonth->format('F');
                    $months[] = $monthName;
                }
            }

//            /* Authenticating user for access */
            $loggedInUser = User::where('id', $currentUser['id'])->with(['role'])->first();

            ///* Earning Months */
            $earningMonths = Earning::where('project_id', $projectID)->select('year', 'month')->get();
            $earningMonths = $earningMonths->toArray();

            return view('admin.projects.project-detail', compact('project','earnings','totalEarning', 'employeeCommissionTotal', 'managerCommissionTotal', 'hodCommissionTotal', 'months', 'currentUser','loggedInUser','earningMonths'));
        } catch (\Exception $e) {

            return redirect()->route('dashboard')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [

                'client_name' => 'required|max:255',
                'job_title' => 'required|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'hourly_rate' => 'required|numeric',
                'sales_person' => 'required|integer',
                'type' => 'required|integer',
                'platform' => 'required|integer',
                'commission_percentage_employee' => [
                    'nullable',
                    'numeric',
                ],
                'commission_percentage_manager' => [
                    'nullable',
                    'numeric',
                ],
                'commission_percentage_hod' => [
                    'nullable',
                    'numeric',
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }


            $verifySalesPerson = AdminSetting::where('id', $request['sales_person'])->where('status', 0)->where('is_del', 0)->first();
            if (empty($verifySalesPerson))
                return response()->json(['success' => false, 'message' => 'Invalid data for sales person.']);

            $verifyType = AdminSetting::where('id', $request['type'])->where('status', 0)->where('is_del', 0)->first();
            if (empty($verifyType))
                return response()->json(['success' => false, 'message' => 'Invalid data for type.']);

            $verifyPlatform = AdminSetting::where('id', $request['platform'])->where('status', 0)->where('is_del', 0)->first();
            if (empty($verifyPlatform))
                return response()->json(['success' => false, 'message' => 'Invalid data for platform.']);

            if ($request['commission_percentage_employee'] == '')
                $request['commission_percentage_employee'] = 0;

            if ($request['commission_percentage_manager'] == '')
                $request['commission_percentage_manager'] = 0;

            if ($request['commission_percentage_hod'] == '')
                $request['commission_percentage_hod'] = 0;

            $totalPercentage = $request['commission_percentage_employee'] + $request['commission_percentage_manager'] + $request['commission_percentage_hod'];

            if ($totalPercentage > 100) {
                return response()->json(['success' => false, 'message' => 'The combined commission percentages cannot exceed 100%.']);
            }


            if ($request['active_user'] == 1)
                $projectStatus = $this->createProject($request);
            else
                $projectStatus = $this->sendProjectForApproval($request);

            DB::commit();

            return response()->json(['success' => $projectStatus['success'], 'message' => $projectStatus['message']]);

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    /**
     * @param $data
     * @return array
     */
    private function createProject($data): array
    {

        if (!empty($data['project_id']))
            $project = Project::where('id', $data['project_id'])->first();


        else
            $project = new Project();

        $project->client_name = $data['client_name'];
        $project->job_title = $data['job_title'];
        $project->start_date = $data['start_date'];
        $project->end_date = $data['end_date'];
        $project->hourly_rate = $data['hourly_rate'];
        $project->sales_person_id = $data['sales_person'];
        $project->type_id = $data['type'];
        $project->platform_id = $data['platform'];

        $project->save();
        /* Commissions */

        $addNewCommissions = False;

        if (!empty($data['project_id'])) {
            $currentCommission = ProjectCommissions::where('project_id', $data['project_id'])->orderBy('id','DESC')->first();
            if (
                $currentCommission['commission_percentage_employee'] != $data['commission_percentage_employee'] ||
                $currentCommission['commission_percentage_manager'] != $data['commission_percentage_manager'] ||
                $currentCommission['commission_percentage_hod'] != $data['commission_percentage_hod']
            ) {
                $currentCommission->status = '1';
                $currentCommission->save();
                $addNewCommissions = True;
            }

        }
        else {

            $addNewCommissions = True;
        }

        if ($addNewCommissions) {

            $commissions = new ProjectCommissions();
            $commissions->project_id = $project['id'];
            $commissions->commission_percentage_employee = $data['commission_percentage_employee'];
            $commissions->commission_percentage_manager = $data['commission_percentage_manager'];
            $commissions->commission_percentage_hod = $data['commission_percentage_hod'];
            $commissions->save();
        }

        /* Project Users */

        $checkProjectUsers = ProjectUser::where('project_id', $project['id'])->get();
        if (count($checkProjectUsers) > 0) {

            $userIds = $checkProjectUsers->pluck('user_id')->toArray();
            if ($data['user_ids'] != $userIds) {

                /* Project Assignment Notification */
                if (!empty($data['project_id'])){
                    $difference = array_diff($userIds,$data['user_ids']);
                    $removing_employees = array_values($difference);
                    if (count($removing_employees) > 0){
                        foreach ($removing_employees as $employee) {
                            $notification = new Notification();
                            $notification->project_id = $data['project_id'];
                            $notification->notification_by = Auth::user()->id;
                            $notification->notification_for = $employee;
                            $notification['message'] = 'You have been removed from  <span class="fw-600">' . $project->job_title . '</span> Project.';
                            $notification->save();
                        }
                    }
                    $added_employees = array_diff($removing_employees ,$userIds);
                    if (count($added_employees) > 0){
                        foreach ($added_employees as $employee) {
                            $notification = new Notification();
                            $notification->project_id = $data['project_id'];
                            $notification->notification_by = Auth::user()->id;
                            $notification->notification_for = $employee;
                            $notification['message'] = 'You have been added in  <span class="fw-600">' . $project->job_title . '</span> Project.';
                            $notification->save();
                        }
                    }
                }
                ProjectUser::where('project_id', $project['id'])->delete();
            }
        }

        foreach ($data['user_ids'] as $key => $userId) {

            $projectUsers = new ProjectUser();

            $roleID = User::where('id', $userId)->pluck('role_id')->first();

            $projectUsers->project_id = $project['id'];
            $projectUsers->role_id = $roleID;
            $projectUsers->parent_id = $data['user_ids'][$key + 1] ?? $data['user_ids'][$key + 1] ?? null;
            $projectUsers->user_id = $userId;
            $projectUsers->status = 0;
            $projectUsers->is_del = 0;

            $projectUsers->save();
        }

        /* Project Assignment Notification */
        if (empty($data['project_id'])){
            foreach ($data['user_ids'] as $key => $userId) {
                $notification = new Notification();
                $notification->project_id = $data['project_id'];
                $notification->notification_by = Auth::user()->id;
                $notification->notification_for = $userId;
                $notification['message'] = 'You have been added in  <span class="fw-600">' . $project->job_title . '</span> Project.';
                $notification->save();
            }
        }

        return ['success' => true, 'message' => 'Project has been successfully saved.'];

    }

    /**
     * @param $data
     * @return array
     */
    private function sendProjectForApproval($data): array
    {

        if (!empty($data['project_id'])) {
            $project = ProjectsApproval::where('project_id', $data['project_id'])->first();

            if (empty($project)){
                $project = new ProjectsApproval();
            }
            elseif ($project->is_approved == 2){
                $project->delete();
                $project = new ProjectsApproval();
            }
        }
        else {

            $project = new ProjectsApproval();
        }

        if (!empty($data['project_id']) && $project->requested_by != Auth::user()->id){
            $project->is_read = 0;
        }

        $project->project_id = $data['project_id'] ?? null;
        $project->client_name = $data['client_name'];
        $project->job_title = $data['job_title'];
        $project->start_date = $data['start_date'];
        $project->end_date = $data['end_date'];
        $project->hourly_rate = $data['hourly_rate'];
        $project->sales_person_id = $data['sales_person'];
        $project->type_id = $data['type'];
        $project->platform_id = $data['platform'];
        $project->requested_by = Auth::user()->id;

        $project->save();

        /* Commissions */

        $addNewCommissions = False;

        if (!empty($data['project_id'])) {
            $currentCommission = ProjectCommissions::where('project_id', $data['project_id'])->orderBy('id','DESC')->first();
            if (
                $currentCommission['commission_percentage_employee'] != $data['commission_percentage_employee'] ||
                $currentCommission['commission_percentage_manager'] != $data['commission_percentage_manager'] ||
                $currentCommission['commission_percentage_hod'] != $data['commission_percentage_hod']
            ) {
                $addNewCommissions = True;
            }

        }
        else {

            $addNewCommissions = True;
        }
        if ($addNewCommissions) {


            $projectApprovalCommission = ProjectApprovalCommissions::where([
                'project_approval_id' => $project['id'],
                'is_approved' => 0
            ])->first();
            if ($projectApprovalCommission){
                $projectApprovalCommission->commission_percentage_employee = $data['commission_percentage_employee'];
                $projectApprovalCommission->commission_percentage_manager = $data['commission_percentage_manager'];
                $projectApprovalCommission->commission_percentage_hod = $data['commission_percentage_hod'];
                $projectApprovalCommission->save();
            }
            else{
                $commissions = new ProjectApprovalCommissions();
                $commissions->commission_id = $data['project_id'] ? $currentCommission['id'] : null;
                $commissions->project_approval_id = $project['id'];
                $commissions->commission_percentage_employee = $data['commission_percentage_employee'];
                $commissions->commission_percentage_manager = $data['commission_percentage_manager'];
                $commissions->commission_percentage_hod = $data['commission_percentage_hod'];
                $commissions->requested_by = Auth::user()->id;
                $commissions->save();
            }
        }

        /* Project Users */

        $checkProjectUsers = ProjectApprovalUsers::where('project_approval_id', $project['id'])->get();
        if (count($checkProjectUsers) > 0) {

            $userIds = $checkProjectUsers->pluck('user_id')->toArray();

            /* Project Assignment Notification */
            if ($data['user_ids'] != $userIds) {

                ProjectApprovalUsers::where('project_approval_id', $project['id'])->delete();

            }
        }

        foreach ($data['user_ids'] as $key => $userId) {

            $projectUsers = new ProjectApprovalUsers();

            $roleID = User::where('id', $userId)->pluck('role_id')->first();

            $projectUsers->project_approval_id = $project['id'];
            $projectUsers->role_id = $roleID;
            $projectUsers->parent_id = $data['user_ids'][$key + 1] ?? $data['user_ids'][$key + 1] ?? null;
            $projectUsers->user_id = $userId;

            $projectUsers->save();
        }


        return ['success' => true, 'message' => 'Project has been successfully sent for approval.'];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {

        try {

            $projectID = $request['project_id'];
            $activeUser = $request['active_user'];
            $project = Project::where('id', $projectID)->where('status', 0)->where('is_del', 0)->first();

            if ($activeUser == 1) {


                if (empty($project))
                    return response()->json(['success' => false, 'Invalid data for project.']);

                $project->is_del = 1;
                $project->save();

                return response()->json(['success' => true, 'message' => 'Project has been successfully deleted.']);
            }
            else {

                $projectApproval = ProjectsApproval::where('project_id', $projectID)->first();
                if (empty($projectApproval))
                    $projectApproval = new ProjectsApproval();

                $projectApproval->project_id = $project['id'];
                $projectApproval->job_title = $project['job_title'];
                $projectApproval->requested_by = Auth::user()->id;
                $projectApproval->is_requested_del = 1;
                $projectApproval->is_approved = 0;

                $projectApproval->save();

                return response()->json(['success' => true, 'message' => 'Project has been successfully sent for approval.']);
            }

        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function completeProject(Request $request): JsonResponse
    {
        try {
            $projectID = $request['project_id'];
            $user_id = Auth::user()->id;
            $project = Project::where('id', $projectID)->where('status', 0)->first();
            if (!empty($project)){
                $completed_project = CompletedProject::where('project_id', $projectID)->where('is_completed', 0)->first();
                if (!empty($completed_project) && $completed_project->user_id != $user_id){
                    $completed_project->update(['user_id'=> $user_id]);                }
                else if(empty($completed_project)){
                    $completedProject = new CompletedProject();
                    $completedProject->project_id = $projectID;
                    $completedProject->user_id = $user_id;
                    $completedProject->save();
                }
            }
                return response()->json(['success' => true, 'message' => 'Project has been successfully completed']);
        }
        catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
    }
}

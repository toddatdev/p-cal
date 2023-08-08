<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\CompletedProject;
use App\Models\Earning;
use App\Models\EarningApproval;
use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectApprovalCommissions;
use App\Models\ProjectApprovalUsers;
use App\Models\ProjectCommissions;
use App\Models\ProjectsApproval;
use App\Models\ProjectUser;
use App\Models\StopEarning;
use App\Models\StopEarningApproval;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{

    /**
     * @return Factory|View|Application
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $userInfo = Auth::user();
        if (empty($userInfo['role_id'])) {

            $projectNotifications = ProjectsApproval::select('id', 'requested_by', 'job_title', 'is_requested_del', 'project_id','updated_at')->where('is_approved', 0)
                ->with(['user'])->orderBy('updated_at', 'DESC')->get();
            $earningNotifications = EarningApproval::select('id', 'earning_id', 'requested_by', 'earning', 'project_id', 'month', 'is_requested_del','updated_at')->where('is_approved', 0)
                ->with(['project', 'user'])->orderBy('updated_at', 'DESC')->get();
            $commissionNotifications = ProjectApprovalCommissions::where(
                [
                    'is_approved' => 0,
                ]
            )->whereNotNull('project_id')
                ->with(['project', 'user'])
                ->orderBy('updated_at', 'DESC')
                ->get();
            $completedNotifications = CompletedProject::where('is_completed', 0)
                ->with(['project', 'user'])->orderBy('updated_at', 'DESC')->get();

            $stopEarningNotifications = StopEarningApproval::where('is_approved', 0)->with(['user', 'earner', 'project'])->orderBy('id', 'DESC')->get()
                ->map(function ($notification) {
                    $notification['month'] = Carbon::create(null, $notification['month'], 1)->format('F');
                    return $notification;
                });

            $projectNotifications = $projectNotifications->map(function ($notification) {
                $notification->type = 'project';
                return $notification;
            });

            $earningNotifications = $earningNotifications->map(function ($notification) {
                $notification->type = 'earning';
                return $notification;
            });

            $commissionNotifications = $commissionNotifications->map(function ($notification) {
                $notification->type = 'commission';
                return $notification;
            });

            $stopEarningNotifications = $stopEarningNotifications->map(function ($notification) {
                $notification->type = 'stopEarning';
                return $notification;
            });

            $completedNotifications = $completedNotifications->map(function ($notification) {
                $notification->type = 'completed';
                return $notification;
            });

            $notifications = $projectNotifications->concat($earningNotifications)->concat($commissionNotifications)->concat($stopEarningNotifications)->concat($completedNotifications)->sortByDesc('updated_at');

            ProjectsApproval::where('is_read', 0)->update(['is_read' => 1]);
            EarningApproval::where('is_read', 0)->update(['is_read' => 1]);
            ProjectApprovalCommissions::where('is_read', 0)->update(['is_read' => 1]);
            StopEarningApproval::where('is_read', 0)->update(['is_read' => 1]);
            CompletedProject::where('is_read', 0)->update(['is_read' => 1]);
            $adminNotifications = True;

        }
        else {

            $notifications = Notification::where('notification_for', $userInfo['id'])
                ->whereBetween('updated_at',
                    [Carbon::now()->subMonth(1), Carbon::now()])
                ->with(['user'])->get();
            Notification::where('is_read', 0)->where('notification_for', $userInfo['id'])->update(['is_read' => 1]);
            $adminNotifications = False;
        }
        return view('admin.notifications.index', compact('notifications', 'adminNotifications'));
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function approve(Request $request): JsonResponse
    {

        try {

            DB::beginTransaction();

            $notificationId = base64_decode($request['notification_id']);
            $approvalStatus = $request['approval_status']; // 1 = cancel, 2 = approved
            $notificationType = $request['notification_type']; // 1 = project, 2 = earning

            if ($notificationType == 1)
                $approvalResponse = $this->projectApproval($notificationId, $approvalStatus, $request);
            else if ($notificationType == 2)
                $approvalResponse = $this->earningApproval($notificationId, $approvalStatus, $request);
            else if ($notificationType == 3)
                $approvalResponse = $this->commissionApproval($notificationId, $approvalStatus, $request);
            else if ($notificationType == 4)
                $approvalResponse = $this->stopEarningApproval($notificationId, $approvalStatus, $request);
            else if ($notificationType == 5)
                $approvalResponse = $this->completeProjectApproval($notificationId, $approvalStatus, $request);

        $projectNotifications = ProjectsApproval::select('id')->where('is_approved', 0)->get();

        $earningNotifications = EarningApproval::select('id')->where('is_approved', 0)->get();
        $commissionNotifications = ProjectApprovalCommissions::where(['is_approved' => 0])->whereNotNull('project_id')->get();

        $notifications = $projectNotifications->concat($earningNotifications)->concat($commissionNotifications);
        $notificationCount = count($notifications);

            DB::commit();

            return response()->json(['success' => $approvalResponse['success'], 'message' => $approvalResponse['message'], 'remainingNotifications' => $notificationCount]);
        }
        catch (\Exception $e) {

            DB::rollback();
            return response()->json(['success' => False, 'message' => $e->getMessage()]);

        }

    }

    private function stopEarningApproval($notificationId, $approvalStatus, $request) {

        $stopEarningApproval = StopEarningApproval::where('id', $notificationId)->first();
        $stopEarningApprovalDetails = StopEarningApproval::where('id', $notificationId)->with(['user', 'earner', 'project'])->first();
        $stoppingMonth = Carbon::create(null, $stopEarningApprovalDetails['month'], 1)->format('F');

        $notification = new Notification();
        $notification->project_id = $stopEarningApproval['project_id'];
        $notification->notification_by = Auth::user()->id;
        $notification->notification_for = $stopEarningApproval['requested_by'];

        if ($approvalStatus == 1) {

            $stopEarningApproval->is_approved = 1;
            $stopEarningApproval->save();

            $notification['message'] = 'Did not approve your request to stop earning for ' . $stopEarningApprovalDetails['earner']->first_name . ' ' . $stopEarningApprovalDetails['earner']->last_name . ' from ' . $stoppingMonth . ' against <span class="fw-600">' . $stopEarningApprovalDetails['project']->job_title . '</span> Project.';
            $notification->save();

            return ['success' => True, 'message' => 'Request has been successfully cancelled.'];
        }

        $stopEarningApproval->is_approved = 1;
        $stopEarningApproval->save();

        $notification['message'] = 'Approve your request to stop earning for ' . $stopEarningApprovalDetails['earner']->first_name . ' ' . $stopEarningApprovalDetails['earner']->last_name . ' from ' . $stoppingMonth . ' against <span class="fw-600">' . $stopEarningApprovalDetails['project']->job_title . '</span> Project.';

        $notification->save();

        $stopEarningApprovalValuesToExclude = ['id', 'requested_by', 'is_approved', 'is_read'];
        $stopEarning = new StopEarning();

        foreach ($stopEarningApproval->getAttributes() as $attribute => $approvalValue) {



            if (!in_array($attribute, $stopEarningApprovalValuesToExclude)) {

                $stopEarning->$attribute = $approvalValue;
            }
        }

        $stopEarning->save();

        return ['success' => True, 'message' => 'Request has been successfully approved.'];
    }

    /**
     * @param $notificationId
     * @param $approvalStatus
     * @param $request
     * @return array
     */
    private function projectApproval($notificationId, $approvalStatus, $request): array
    {

        $projectApproval = ProjectsApproval::where(
            [
                'id' => $notificationId
            ]
        )->first();
        $commissionApproval = ProjectApprovalCommissions::where(['project_approval_id' => $projectApproval['id'], 'is_approved' => 0, 'requested_by' => $projectApproval['requested_by']])->orderBy('id', 'DESC')->first();

        $requestFor = '';

        $notification = new Notification();
        $notification->project_approval_id = $notificationId;
        $notification->notification_by = Auth::user()->id;
        $notification->notification_for = $projectApproval['requested_by'];

        if ($approvalStatus == 1) {

            $projectApproval->is_approved = 1;
            $projectApproval->save();

            if (!empty($projectApproval['project_id']) && $projectApproval['is_requested_del']) {

                $requestFor = 'delete';
            }
            else if (!empty($projectApproval['project_id']) && $projectApproval['is_requested_del'] != 0) {

                $requestFor = 'edit';
            }
            else if (empty($projectApproval['project_id'])) {

                $requestFor = 'add';
            }

            $notification['message'] = 'Did not approve your request to ' . $requestFor . ' the <span class="fw-600">' . $projectApproval['job_title'] . '</span> Project.';
            $notification->save();

            return ['success' => True, 'message' => 'Request has been successfully cancelled.'];
        }

        if ($projectApproval['is_requested_del'] == 1) {

            Project::where('id', $projectApproval['project_id'])->update(['is_del' => 1]);
            $projectApproval->is_approved = 2;
            $projectApproval->save();

            if (!empty($commissionApproval)) {

                $commissionApproval->is_approved = 2;
                $commissionApproval->save();
            }

            $notification['message'] = 'Approve your request to delete the <span class="fw-600">' . $projectApproval['job_title'] . '</span> Project.';
            $notification->save();

            return ['success' => True, 'message' => 'Project has been successfully deleted.'];
        }

        $message = '';
        if (empty($projectApproval['project_id']) && $projectApproval['is_requested_del'] == 0) {

            $project = new Project();
            $message = 'Project has been successfully added.';

            $notification['message'] = 'Approve your request to add the <span class="fw-600">' . $projectApproval['job_title'] . '</span> Project.';
            $notification->save();
        }
        else if (!empty($projectApproval['project_id']) && $projectApproval['is_requested_del'] == 0) {

            $project = Project::where('id', $projectApproval['project_id'])->first();
            ProjectCommissions::where('id', $projectApproval['project_id'])->where('status', 0)->update(['status' => 1]);
            $message = 'Project has been successfully updated.';

            $notification['message'] = 'Approve your request to update the <span class="fw-600">' . $projectApproval['job_title'] . '</span> Project.';
            $notification->save();
        }

        $valuesToExclude = ['id', 'project_id', 'requested_by', 'is_requested_del', 'is_approved', 'is_read'];

        foreach ($projectApproval->getAttributes() as $attribute => $value) {
            if (!in_array($attribute, $valuesToExclude)) {
                $project->$attribute = $value;
            }
        }

        $project->save();

        $projectApproval->is_approved = 2;
        $projectApproval->save();

        if (!empty($commissionApproval)) {

            /* Commissions */

            $commissionValuesToExclude = ['id', 'commission_id', 'project_id', 'project_approval_id', 'requested_by', 'is_approved', 'requested_with_project', 'is_read'];
            $commission = new ProjectCommissions();

            $commission->project_id = $project['id'];

            foreach ($commissionApproval->getAttributes() as $attribute => $commissionValue) {

                if (!in_array($attribute, $commissionValuesToExclude)) {

                    $commission->$attribute = $commissionValue;
                }
            }

            $commission->save();

            $commissionApproval->is_approved = 2;
            $commissionApproval->save();
        }

        $projectUsers = ProjectUser::where('project_id', $project['id'])->get();
        $approvalProjectUsers = ProjectApprovalUsers::where('project_approval_id', $projectApproval['id'])->get();

        if (count($approvalProjectUsers) > 0) {

            $approvalProjectUserIds = $approvalProjectUsers->pluck('user_id')->toArray();

            if ($projectUsers->isNotEmpty()) {

                $userIds = $projectUsers->pluck('user_id')->toArray();
                if ($approvalProjectUserIds != $userIds) {
                    /* Project Assignment Notification */
                    if (!empty($projectApproval['project_id']) && $projectApproval['is_requested_del'] == 0){
                        $difference = array_diff($userIds, $approvalProjectUserIds);
                        $removing_employees = array_values($difference);
                        if (count($removing_employees) > 0){
                            foreach ($removing_employees as $employee) {
                                $notification = new Notification();
                                $notification->project_id = $project['id'];
                                $notification->notification_by = $projectApproval['requested_by'];
                                $notification->notification_for = $employee;
                                $notification['message'] = 'has removed you from  <span class="fw-600">' . $project->job_title . '</span> Project.';
                                $notification->save();
                            }
                        }
//                        $added_employees = array_diff($removing_employees ,$userIds);
                        $diff = array_diff($approvalProjectUserIds, $userIds);
                        $added_employees = array_values($diff);
                        if (count($added_employees) > 0){
                            foreach ($added_employees as $employee) {
                                $notification = new Notification();
                                $notification->project_id = $project['id'];
                                $notification->notification_by = $projectApproval['requested_by'];
                                $notification->notification_for = $employee;
                                $notification['message'] = 'has added you in  <span class="fw-600">' . $project->job_title . '</span> Project.';
                                $notification->save();
                            }
                        }
                    }
                    ProjectUser::where('project_id', $project['id'])->delete();
                }
            }

            foreach ($approvalProjectUserIds as $key => $userId) {

                $projectUsers = new ProjectUser();

                $roleID = User::where('id', $userId)->pluck('role_id')->first();

                $projectUsers->project_id = $project['id'];
                $projectUsers->role_id = $roleID;
                $projectUsers->parent_id = $approvalProjectUserIds[$key + 1] ?? $approvalProjectUserIds[$key + 1] ?? null;
                $projectUsers->user_id = $userId;
                $projectUsers->status = 0;
                $projectUsers->is_del = 0;

                $projectUsers->save();
            }

            ProjectApprovalUsers::where('project_approval_id', $projectApproval['id'])->update(['is_approved' => 1]);

            /* Project Assignment Notification */
            if (empty($projectApproval['project_id']) && $projectApproval['is_requested_del'] == 0){
                foreach ($approvalProjectUserIds as $key => $userId) {
                    $notification = new Notification();
                    $notification->project_id = $project['id'];
                    $notification->notification_by = $projectApproval['requested_by'];
                    $notification->notification_for = $userId;
                    $notification['message'] = 'has added you in  <span class="fw-600">' . $project['job_title'] . '</span> Project.';
                    $notification->save();
                }
            }
        }


        return ['success' => True, 'message' => $message];
    }

    /**
     * @param $notificationId
     * @param $approvalStatus
     * @param $request
     * @return array
     */
    private function earningApproval($notificationId, $approvalStatus, $request): array
    {

        $earningApproval = EarningApproval::where('id', $notificationId)->with(['project'])->first();

        $requestFor = '';

        $notification = new Notification();
        $notification->project_id = $earningApproval['project_id'];
        $notification->notification_by = Auth::user()->id;
        $notification->notification_for = $earningApproval['requested_by'];

        if ($approvalStatus == 1) {

            $earningApproval->is_approved = 1;
            $earningApproval->save();

            if (!empty($earningApproval['earning_id']) && $earningApproval['is_requested_del']) {

                $requestFor = 'delete';
            }
            else if (!empty($earningApproval['earning_id']) && $earningApproval['is_requested_del'] != 0) {

                $requestFor = 'edit';
            }
            else if (empty($earningApproval['earning_id'])) {

                $requestFor = 'add';
            }

            $notification['message'] = 'Did not approve your request to ' . $requestFor . ' the $' . number_format($earningApproval['earning'] , 2) . ' earning for ' . $earningApproval['month'] . ' against <span class="fw-600">' . $earningApproval['project']->job_title . '</span> Project.';
            $notification->save();

            return ['success' => True, 'message' => 'Request has been successfully cancelled.'];
        }

        if ($earningApproval['is_requested_del'] == 1) {

            Earning::where('id', $earningApproval['earning_id'])->update(['is_del' => 1]);
            $earningApproval->is_approved = 2;
            $earningApproval->save();

            $notification['message'] = 'Approve your request to delete the $' . number_format($earningApproval['earning'] , 2) . ' earning for ' . $earningApproval['month'] . ' against <span class="fw-600">' . $earningApproval['project']->job_title . '</span> Project.';
            $notification->save();

            return ['success' => True, 'message' => 'Earning has been successfully deleted.'];
        }

        $message = '';

        if (empty($earningApproval['earning_id']) && $earningApproval['is_requested_del'] == 0) {

            $earning = new Earning();
            $message = 'Earning has been successfully added.';

            $notification['message'] = 'Approve your request to add the $' . number_format($earningApproval['earning'] , 2) . ' earning for ' . $earningApproval['month'] . ' against <span class="fw-600">' . $earningApproval['project']->job_title . '</span> Project.';
            $notification->save();
        }
        else if (!empty($earningApproval['earning_id']) && $earningApproval['is_requested_del'] == 0) {

            $earning = Earning::where('id', $earningApproval['earning_id'])->first();
            $message = 'Earning has been successfully updated.';

            $notification['message'] = 'Approve your request to update the $' . number_format($earningApproval['earning'] , 2) . ' earning for ' . $earningApproval['month'] . ' against <span class="fw-600">' . $earningApproval['project']->job_title . '</span> Project.';
            $notification->save();
        }

        $valuesToExclude = ['id', 'earning_id', 'requested_by', 'is_requested_del', 'is_approved', 'is_read'];

        foreach ($earningApproval->getAttributes() as $attribute => $value) {
            if (!in_array($attribute, $valuesToExclude)) {
                $earning->$attribute = $value;
            }
        }

        $earning->save();

        $earningApproval->is_approved = 2;
        $earningApproval->save();

        return ['success' => True, 'message' => $message];
    }

    /**
     * @param $notificationId
     * @param $approvalStatus
     * @param $request
     * @return JsonResponse|array
     */
    private function commissionApproval($notificationId, $approvalStatus, $request): JsonResponse|array
    {

//        try {

            $approvalCommission = ProjectApprovalCommissions::where('id', $notificationId)->with(['project'])->first();
            if (empty($approvalCommission))
                return ['success' => False, 'message' => 'Invalid data for commission.'];

            $approvalCommission->is_approved = $approvalStatus;
            $approvalCommission->save();

            $message = 'Commission has been successfully cancelled';
            $notificationMessage = 'Did not approve your request to add the commission for <span class="fw-600">' . $approvalCommission['project']->job_title . '</span> Project.';

            if ($approvalStatus == 2) {

                $currentCommission = ProjectCommissions::where(
                    [
                        'project_id' => $approvalCommission['project_id'],
                        'status' => 0,
                        'is_del' => 0
                    ]
                )->orderBy('id', 'DESC')->first();

                $currentCommission->status = 1;
                $currentCommission->save();

                $commissionValuesToExclude = ['id', 'commission_id', 'project_approval_id', 'requested_by', 'is_approved', 'is_read'];

                $commission = new ProjectCommissions();

                foreach ($approvalCommission->getAttributes() as $attribute => $commissionValue) {

                    if (!in_array($attribute, $commissionValuesToExclude)) {

                        $commission->$attribute = $commissionValue;
                    }
                }

                $commission->save();

                $message = 'Commission has been successfully approved';

                $notificationMessage = 'Approve your request to add the commission for <span class="fw-600">' . $approvalCommission['project']->job_title . '</span> Project.';
            }

            $notification = new Notification();
            $notification->project_id = $approvalCommission['project_approval_id'];
            $notification->notification_by = Auth::user()->id;
            $notification->notification_for = $approvalCommission['requested_by'];
            $notification->message = $notificationMessage;
            $notification->save();

            return ['success' => True, 'message' => $message];
//        }
//        catch (\Exception $e) {
//
//            return ['success' => False, 'message' => 'Something went wrong. Please try again later.'];
//        }


    }
    /**
     * @param $project_id
     * @param $request
     * @return JsonResponse|array
     */
    public function projectDetail(Request $request): JsonResponse|array
    {
        try {
            $data = ProjectsApproval::where('id', $request->project_id)->first();
            $sales_person = AdminSetting::where('id', $data->sales_person_id)->pluck('name')->first();
            $type = AdminSetting::where('id', $data->type_id)->pluck('name')->first();
            $platform = AdminSetting::where('id', $data->platform_id)->pluck('name')->first();
            $user = User::where('id', $data->requested_by)->first();
            $employee = $user->first_name. ' ' . $user->last_name;
            $projectCommission = ProjectApprovalCommissions::where('project_approval_id', $request->project_id)->first();
            $data['sales_person'] = $sales_person;
            $data['type'] = $type;
            $data['platform'] = $platform;
            $data['employee'] = $employee;
            $data['commission_hod'] = $projectCommission->commission_percentage_hod;
            $data['commission_employee'] = $projectCommission->commission_percentage_employee;
            $data['commission_manager'] = $projectCommission->commission_percentage_manager;

            return ['success' => True, 'data' => $data];
        }
        catch (\Exception $e) {
            return ['success' => False, 'message' => 'Something went wrong. Please try again later.'];
        }
    }

    /**
     * @param $notificationId
     * @param $approvalStatus
     * @param $request
     * @return JsonResponse|array
     */
    public function completeProjectApproval($notificationId, $approvalStatus, $request): JsonResponse|array
    {
        try {
            $approvalCompletedProject = CompletedProject::where('id', $notificationId)->where('is_completed', 0)->with(['project'])->first();
            if (empty($approvalCompletedProject)){
                return ['success' => False, 'message' => 'Invalid data for project approval.'];
            }

            if ($approvalStatus == 1){
                $approvalCompletedProject->update(['is_completed'=> $approvalStatus]);
                $message = 'Request to complete project has been successfully cancelled';
                $notificationMessage = 'Did not approve your request to complete project for <span class="fw-600">' . $approvalCompletedProject['project']->job_title . '</span> Project.';
            }
            else if ($approvalStatus == 2){
                $approvalCompletedProject->update(['is_completed'=> $approvalStatus]);
                $project = Project::where('id', $approvalCompletedProject->project_id)->where('status', 0)->first();
                $project->update(['status'=> 1]);
                $message = 'Request to complete project has been successfully approved';
                $notificationMessage = 'Approved your request to complete project for <span class="fw-600">' . $approvalCompletedProject['project']->job_title . '</span> Project.';
            }
            $notification = new Notification();
            $notification->project_id = $approvalCompletedProject['project_id'];
            $notification->notification_by = Auth::user()->id;
            $notification->notification_for = $approvalCompletedProject['user_id'];
            $notification->message = $notificationMessage;
            $notification->save();

            return ['success' => True, 'message' => $message];
        }
        catch (\Exception $e) {
            return ['success' => False, 'message' => 'Something went wrong. Please try again later.'];
        }
    }

    /**
     * @param $project_id
     * @param $request
     * @return JsonResponse|array
     */
    public function commissionDetail(Request $request): JsonResponse|array
    {
        try {
            $data = ProjectApprovalCommissions::where('id', $request->commission_id)->first();
            $project = Project::where('id', $data->project_id)->pluck('job_title')->first();
            $user = User::where('id', $data->requested_by)->first();
            $employee = $user->first_name. ' ' . $user->last_name;
            $data['project'] = $project;
            $data['requested_by'] = $employee;

            return ['success' => True, 'data' => $data];
        }
        catch (\Exception $e) {
            return ['success' => False, 'message' => 'Something went wrong. Please try again later.'];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

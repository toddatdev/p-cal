<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\EarningApproval;
use App\Models\Project;
use App\Models\ProjectCommissions;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\StopEarning;
use App\Models\StopEarningApproval;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EarningController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function stopEarning(Request $request): JsonResponse
    {

        try {

            if ($request['active_user'] == 1)
                $earningResponse = $this->stopEarningAdmin($request);
            else
                $earningResponse = $this->stopEarningApproval($request);

            return response()->json(['success' => $earningResponse['success'], 'message' => $earningResponse['message']]);
        }
        catch (\Exception $e) {

            return response()->json(['success' => False, 'message' => 'Something went wrong. Please try again later.']);
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function stopEarningAdmin($request): array
    {

        $project = Project::where(
            [
                'id' => $request['project_id'],
                'is_del' => 0
            ]
        )
            ->exists();

        if (!$project)
            return ['success' => False, 'message' => 'Invalid data for project.'];

        $month = $request['month'];
        $monthToDigit = Carbon::createFromFormat('F', $month)->format('n');

        $currentDate = Carbon::now();
        if ($currentDate->format('n') <= $monthToDigit)
            $currentYear = $currentDate->year;
        else
            $currentYear = $currentDate->year + 1;

        $projectUsers = $request['project_users'];

        foreach ($projectUsers as $user) {

            $stopEarning = new StopEarning();
            $stopEarning->project_id = $request['project_id'];
            $stopEarning->user_id = $user;
            $stopEarning->month = $monthToDigit;
            $stopEarning->year = $currentYear;
            $stopEarning->save();
        }

        return ['success' => True, 'message' => 'Earning has been successfully stopped.'];
    }

    /**
     * @param $request
     * @return array
     */
    public function stopEarningApproval($request): array
    {

        try {

            $project = Project::where(
                [
                    'id' => $request['project_id'],
                    'is_del' => 0
                ]
            )
                ->exists();

            if (!$project)
                return ['success' => False, 'message' => 'Invalid data for project.'];

            $month = $request['month'];
            $monthToDigit = Carbon::createFromFormat('F', $month)->format('n');

            $currentDate = Carbon::now();
            if ($currentDate->format('n') <= $monthToDigit)
                $currentYear = $currentDate->year;
            else
                $currentYear = $currentDate->year + 1;

            $projectUsers = $request['project_users'];

            foreach ($projectUsers as $user) {


                $stopEarning = new StopEarningApproval();
                $stopEarning->project_id = $request['project_id'];
                $stopEarning->user_id = $user;
                $stopEarning->month = $monthToDigit;
                $stopEarning->year = $currentYear;
                $stopEarning->requested_by = Auth::user()->id;
                $stopEarning->is_approved = 0;
                $stopEarning->is_read = 0;
                $stopEarning->save();
            }

            return ['success' => True, 'message' => 'Earning has been successfully sent for approval.'];
        }
        catch (\Exception $e) {

            return ['success' => False, 'message' => 'Something has went wrong. Please try again later.'];
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.earnings.index');
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


    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'earning' => 'required|numeric',
                'year' => 'required',
                'month' => 'required',
                'exg_rate' => 'required|numeric',
                'currency' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }

            $commissions = array();

            $commissions['amount'] = $request['earning'];

            $currentCommissions = ProjectCommissions::where(
                [
                    'project_id' => $request['project_id'],
                    'status' => 0,
                    'is_del' => 0
                ]
            )->orderBy('id', 'DESC')
                ->first();

            if (empty($currentCommissions)){
                return response()->json(['success' => false, 'message' => 'Unable to find commissions for this project.']);
            }

            $hodStopEarning = False;
            $managerStopEarning = False;
            $employeeStopEarning = False;

            $projectUsers = ProjectUser::select('user_id')->where('project_id', $request['project_id'])->get();

            foreach ($projectUsers as $projectUser) {

                $stopEarning = StopEarning::where(
                    [
                        'project_id' => $request['project_id'],
                        'user_id' => $projectUser['user_id'],
                    ]
                )->with(['user'])
                    ->orderBy('year', 'ASC')
                    ->orderBy('month', 'ASC')
                    ->first();

                if (!empty($stopEarning)) {

                    if (!empty($stopEarning->user->role)) {

                        $role = $stopEarning->user->role;

                        if ($role['id'] == 1) {

                            $hodStopEarning = True;
                        }
                        else if ($role['id'] == 2) {

                            $managerStopEarning = True;
                        }
                        else if ($role['id'] == 3) {

                            $employeeStopEarning = True;
                        }
                    }
                }
            }

            $employeePercentage = $employeeStopEarning ? 0 : $currentCommissions->commission_percentage_employee ?? 0;
            $managerPercentage = $managerStopEarning ? 0 : $currentCommissions->commission_percentage_manager ?? 0;
            $hodPercentage = $hodStopEarning ? 0 : $currentCommissions->commission_percentage_hod ?? 0;

            /* Calculating commission in dollars */

            $commissions['employeeCommission'] = ($commissions['amount'] * $employeePercentage) / 100;
            $commissions['managerCommission'] = ($commissions['amount'] * $managerPercentage) / 100;
            $commissions['hodCommission'] = ($commissions['amount'] * $hodPercentage) / 100;

            /* Calculate commission in exg rate */

            $commissions['employeeCommissionByExgRate'] = $commissions['employeeCommission'] * $request['exg_rate'];
            $commissions['managerCommissionByExgRate'] = $commissions['managerCommission'] * $request['exg_rate'];
            $commissions['hodCommissionByExgRate'] = $commissions['hodCommission'] * $request['exg_rate'];

            if ($request['active_user'] == 1)
                $earningStatus = $this->createEarning($request, $commissions);
            else
                $earningStatus = $this->createEarningForApproval($request, $commissions);

            return response()->json(['success' => $earningStatus['success'], 'message' => $earningStatus['message']]);

        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
    }

    /**
     * @param $data
     * @param $commissions
     * @return array
     */
    private function createEarning($data, $commissions): array
    {

        if (!empty($data['earning_id']))
            $earning = Earning::where('id', $data['earning_id'])->first();
        else

            $earning = new Earning();

        $earning->project_id = $data['project_id'];
        $earning->earning = $commissions['amount'];
        $earning->year = $data['year'];
        $earning->month = $data['month'];
        $earning->exg_rate = $data['exg_rate'];
        $earning->employee_commission = $commissions['employeeCommission'];
        $earning->manager_commission = $commissions['managerCommission'];
        $earning->hod_commission = $commissions['hodCommission'];
        $earning->employee_commission_by_exg_rate = $commissions['employeeCommissionByExgRate'];
        $earning->manager_commission_by_exg_rate = $commissions['managerCommissionByExgRate'];
        $earning->hod_commission_by_exg_rate = $commissions['hodCommissionByExgRate'];
        $earning->currency = $data['currency'];

        $earning->save();

        return ['success' => True, 'message' => 'Earning has been successfully saved.'];
    }

    /**
     * @param $data
     * @param $commissions
     * @return array
     */
    private function createEarningForApproval($data, $commissions): array
    {

        if (!empty($data['earning_id'])) {

            $earning = EarningApproval::where(
                [
                    'earning_id' => $data['earning_id'],
                    'is_approved' => 0,
                ]
            )->first();

            if (empty($earning))
                $earning = new EarningApproval();
        }
        else {

            $earning = EarningApproval::where(
                [
                    'year' => $data['year'],
                    'month' => $data['month'],
                    'is_approved' => 0
                ]
            )->first();

            if (empty($earning))
                $earning = new EarningApproval();
        }

        $earning->earning_id = $data['earning_id'];
        $earning->project_id = $data['project_id'];
        $earning->earning = $commissions['amount'];
        $earning->year = $data['year'];
        $earning->month = $data['month'];
        $earning->exg_rate = $data['exg_rate'];
        $earning->employee_commission = $commissions['employeeCommission'];
        $earning->manager_commission = $commissions['managerCommission'];
        $earning->hod_commission = $commissions['hodCommission'];
        $earning->employee_commission_by_exg_rate = $commissions['employeeCommissionByExgRate'];
        $earning->manager_commission_by_exg_rate = $commissions['managerCommissionByExgRate'];
        $earning->hod_commission_by_exg_rate = $commissions['hodCommissionByExgRate'];
        $earning->currency = $data['currency'];
        $earning->requested_by = Auth::user()->id;

        $earning->save();

        return ['success' => True, 'message' => 'Earning has been successfully sent for approval.'];
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request): JsonResponse
    {
        try {
            $earningID = $request['earning_id'];
            $earning = Earning::where('id', $earningID)->where('status', 0)->where('is_del', 0)->first();
            if (empty($earning))
                return response()->json(['success' => false, 'Invalid data for project.']);

            return response()->json(['success' => true, 'earning' => $earning]);
        }
        catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy(Request $request): JsonResponse
    {
        try {

            $earningID = $request['earning_id'];
            $earning = Earning::where('id', $earningID)->where('status', 0)->where('is_del', 0)->first();

            if (empty($earning))
                return response()->json(['success' => false, 'Invalid data for project.']);

            if ($request['active_user'] == 1) {

                $earning->is_del = 1;
                $earning->save();

                return response()->json(['success' => true, 'message' => 'Project Earning has been successfully deleted.']);
            }
            else {

                $earningApproval = new EarningApproval();
                $earningApproval->earning_id = $earning['id'];
                $earningApproval->project_id = $earning['project_id'];
                $earningApproval->earning = $earning['earning'];
                $earningApproval->month = $earning['month'];
                $earningApproval->requested_by = Auth::user()->id;
                $earningApproval->is_requested_del = 1;
                $earningApproval->save();

                return response()->json(['success' => True, 'message' => 'Request has been successfully sent for approval.']);

            }


        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function usersListForStopEarning(Request $request): JsonResponse
    {

        try {

            $projectId = $request['project_id'];
            $month = Carbon::createFromFormat('F', $request['month'])->format('n');
            $currentDate = Carbon::now();
            $year = ($currentDate->format('n') <= $month) ? $currentDate->year : $currentDate->year + 1;

            $projectUsers = ProjectUser::where('project_id', $projectId)->with(['user'])->get();

            $filterProjectUsers = $projectUsers->filter(function ($projectUser) use ($projectId, $year, $month) {
                $stopEarning = StopEarning::where(
                    [
                        'project_id' => $projectId,
                        'user_id' => $projectUser['user_id']
                    ]
                )->orderBy('year', 'ASC')
                    ->orderBy('month', 'ASC')
                    ->first();

                if (!empty($stopEarning)) {
                    return ($year == $stopEarning['year'] && $month < $stopEarning['month']) || $year < $stopEarning['year'];
                }

                return true;
            });

            return response()->json(['success' => True, 'projectUsers' => $filterProjectUsers]);
        }
        catch (\Exception $e) {

            return response()->json(['success' => False, 'message' => 'Something went wrong. Please try again later.']);
        }

    }
}

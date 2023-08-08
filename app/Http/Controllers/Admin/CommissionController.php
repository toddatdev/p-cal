<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectApprovalCommissions;
use App\Models\ProjectCommissions;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\StopEarning;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommissionController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCommission(Request $request): \Illuminate\Http\JsonResponse
    {

        try {

            $commission = ProjectCommissions::where(
                [
                    'project_id' => $request['project_id'],
                    'status' => 0
                ]
            )->orderBy('id', 'DESC')
                ->first();

            if (empty($commission))
                return response()->json(['success' => False, 'message' => 'Invalid commission data.']);

            return response()->json(['success' => True, 'commission' => $commission]);
        }
        catch (\Exception $e) {

            return response()->json(['success' => False, 'message' => 'Something went wrong. Please try again later.']);

        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function editCommission(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [

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

        if ($validator->fails())
            return response()->json(['success' => false, 'errors' => $validator->errors()]);

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

        if ($request['active_user'] == '1')
            $commission = $this->addCommission($request);
        else
            $commission = $this->addCommissionForApproval($request);

        return response()->json(['success' => $commission['success'], 'message' => $commission['message']]);
    }

    /**
     * @param $request
     * @return array
     */
    private function addCommission($request): array
    {

        try {

            $project_id = $request['project_id'];
            $currentCommission = ProjectCommissions::where(
                [
                    'project_id' => $project_id,
                    'status' => 0,
                ]
            )->orderBy('id', 'DESC')
                ->first();

            $addNewCommission = False;

            if (
                $currentCommission['commission_percentage_employee'] != $request['commission_percentage_employee'] ||
                $currentCommission['commission_percentage_manager'] != $request['commission_percentage_manager'] ||
                $currentCommission['commission_percentage_hod'] != $request['commission_percentage_hod']
            )
                $addNewCommission = True;

            if ($addNewCommission) {

                $currentCommission->status = 1;
                $currentCommission->save();

                $newCommission = new ProjectCommissions();
                $newCommission->project_id = $project_id;
                $newCommission->commission_percentage_employee = $request['commission_percentage_employee'];
                $newCommission->commission_percentage_manager = $request['commission_percentage_manager'];
                $newCommission->commission_percentage_hod = $request['commission_percentage_hod'];
                $newCommission->save();
            }

            return ['success' => True, 'message' => 'Commission has been successfully added.'];
        }
        catch (\Exception $e) {

            return ['success' => False, 'message' => 'Something went wrong. Please try again later.'];
        }
    }

    private function addCommissionForApproval($request): array
    {

//        try {

            $project_id = $request['project_id'];
            $currentCommission = ProjectCommissions::where(
                [
                    'project_id' => $project_id,
                    'status' => 0,
                ]
            )->orderBy('id', 'DESC')
                ->first();

            $addNewCommission = False;

            if (
                $currentCommission['commission_percentage_employee'] != $request['commission_percentage_employee'] ||
                $currentCommission['commission_percentage_manager'] != $request['commission_percentage_manager'] ||
                $currentCommission['commission_percentage_hod'] != $request['commission_percentage_hod']
            )
                $addNewCommission = True;

            if ($addNewCommission) {
                $projectApprovalCommission = ProjectApprovalCommissions::where([
                    'project_id' => $project_id,
                    'is_approved' => 0
                ])->first();
                if ($projectApprovalCommission){
                    $projectApprovalCommission->commission_percentage_employee = $request['commission_percentage_employee'];
                    $projectApprovalCommission->commission_percentage_manager = $request['commission_percentage_manager'];
                    $projectApprovalCommission->commission_percentage_hod = $request['commission_percentage_hod'];
                    $projectApprovalCommission->save();
                }
                else{
                    $newCommission = new ProjectApprovalCommissions();
                    $newCommission->project_id = $project_id;
                    $newCommission->commission_percentage_employee = $request['commission_percentage_employee'];
                    $newCommission->commission_percentage_manager = $request['commission_percentage_manager'];
                    $newCommission->commission_percentage_hod = $request['commission_percentage_hod'];
                    $newCommission->requested_by = Auth::user()->id;
                    $newCommission->save();
                }


                $message = 'Commission has been successfully sent for approval.';
            }
            else {

                $message = 'Current commission already has same percentage.';
            }

            return ['success' => True, 'message' => $message];
//        }
//        catch (\Exception $e) {
//
//            return ['success' => False, 'message' => 'Something went wrong. Please try again later.'];
//        }
    }


}

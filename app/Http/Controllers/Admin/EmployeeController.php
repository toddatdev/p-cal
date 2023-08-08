<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use PHPUnit\Util\Exception;

class EmployeeController extends Controller
{


    /**
     * @return Factory|\Illuminate\Contracts\View\View|RedirectResponse|Application
     */
    public function index(): Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|Application
    {
        try {

            $roles = Role::where('is_del', 0)->get();
            $employees = User::where('is_del', 0)->where('role_id', '!=', null)->with(['role'])->paginate(10);

            return view('admin.employees.index', compact('roles', 'employees'));
        }
        catch (\Exception $e) {

            return redirect()->route('dashboard')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getParentRoleUsers(Request $request): JsonResponse
    {

        try {
            $employeeParentRoleUsers = Role::where('id', $request['employee_role_id'])->with(['parentRoleUsers'])->first();
            $parentRoleName = Role::where('id', $employeeParentRoleUsers['parent_id'])->pluck('name')->first();
            return response()->json(['success' => True, 'users' => $employeeParentRoleUsers, 'parentRoleName' => $parentRoleName]);
        }
        catch (\Exception $e) {

            return response()->json(['success' => False, 'message' => 'Something went wrong. Please try again later.']);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getInfo(Request $request): JsonResponse
    {

        try {

            $employee = User::where('id', $request['id'])->with(['role'])->first();
            $parentUsers = User::where('role_id', $employee['role']->parent_id)->where('role_id', '!=', null)->where('id', '!=', $request['id'])->get();
            $parentRoleName = Role::where('id', $employee['role']->parent_id)->pluck('name')->first();
            return response()->json(['success' => true, 'employee' => $employee, 'parentUsers' => $parentUsers, 'parentRoleName' => $parentRoleName]);
        }
        catch (\Exception $e) {

            return response()->json(['success' => True, 'message' => 'Something went wrong. Please try again later.']);
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
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        try {

            $validator = Validator::make($request->all(), [

                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => [
                    'required',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($request->input('employee_id')),
                ],
                'phone' => ['required', 'regex:/^[0-9\+\-\(\)]+$/'],
                'date_of_birth' => 'required|date|before_or_equal:today',
                'job_title' => 'required',
                'role_id' => 'required',
                'password' => empty($request['employee_id']) ? 'required' : 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }

            if (!empty($request['employee_id']))
                $employee = User::where('id', $request['employee_id'])->first();
            else
                $employee = new User();

            $employee->username = $request['first_name'].''.$request['last_name'];
            $employee->first_name = $request['first_name'];
            $employee->last_name = $request['last_name'];
            $employee->email = $request['email'];
            $employee->phone = $request['phone'];
            $employee->dob = $request['date_of_birth'];
            $employee->job_title = $request['job_title'];
            $employee->role_id = $request['role_id'];
            $employee->parent_user_id = $request['parent_user'];
            if ($request['password'] != '')
                $employee->password = Hash::make($request['password']);
            $employee->address = '-';

            $employee->save();

            return response()->json(['success' => true, 'message' => 'Data has been successfully saved.']);
        }
        catch (Exception $e) {

            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
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
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        try {

            $user = User::where('id', $request['id'])->first();
            $user->is_del = 1;
            $user->save();
            return response()->json(['success' => True, 'message' => 'Employee has been successfully deleted.']);
        }
        catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'Something went wrong. Please try again later.']);
        }
    }
}

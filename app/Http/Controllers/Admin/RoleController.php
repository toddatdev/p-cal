<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPUnit\Exception;
use function MyNamespace\empty1;

class RoleController extends Controller
{


    /**
     * @return Factory|View|RedirectResponse|Application
     */
    public function index(): Factory|View|RedirectResponse|Application
    {
        try {

            $roles = Role::where('status', 0)->where('is_del', 0)->withCount('users')->paginate(10);

            return view('admin.roles.index',compact('roles'));
        }
        catch (\Exception $e) {

            return redirect()->route('dashboard')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * @return Factory|View|RedirectResponse|Application
     */
    public function create(): Factory|View|RedirectResponse|Application
    {
        try {

            $roles = Role::where('status', 0)->where('is_del', 0)->get();
            $permissions = Permission::where('status', 0)->where('is_del', 0)->get();
            return view('admin.roles.create', compact('roles', 'permissions'));
        }
        catch (\Exception $e) {

            return redirect()->route('dashboard')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('roles', 'name')->where(function ($query) {
                        $query->where('is_del', '<>', 1);
                    })->ignore($request->input('role_id')),
                ],
                'permission_ids' => 'required|array'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }

            $roleID = $request['role_id'];
            if (empty($roleID)) {

                $role = new Role();
            } else {

                $role = Role::where('id', $roleID)->first();
            }

            $role->name = $request['name'];
            $role->parent_id = $request['parent_id'] ? $request['parent_id'] : null;
            $role->permission_ids = json_encode($request['permission_ids']);

            $role->save();

            return response()->json(['success' => true, 'message' => 'Successfully saved.']);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
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
     * @param $id
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function edit($id): View|Factory|Redirector|RedirectResponse|Application
    {
        try {

            $role = Role::where('id', $id)->where('status', 0)->where('is_del', 0)->first();

            if (empty($role)) {
                return redirect(route('roles'))->with('error', 'Invalid Data');
            }

            $roles = Role::where(function ($query) use ($role) {
                $query->where('id', $role['parent_id'])
                    ->orWhere(function ($query) use ($role) {
                        $query->where('status', 0)
                            ->where('is_del', 0)
                            ->where('id', '!=', $role['id']);
                    });
            })->get();
            $permissions = Permission::where('status', 0)->where('is_del', 0)->get();

            return view('admin.roles.create', compact('role', 'roles', 'permissions'));
        }
        catch (\Exception $e) {

            return redirect()->route('dashboard')->with('error', 'Something went wrong. Please try again later.');
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


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        try {

            $data = Role::where('id', $request['id'])->first();
            if (empty($data))
                return response()->json(['success' => false, 'message' => 'Invalid role data has been passed.']);

            $data->is_del = 1;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Delete Successfully.']);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

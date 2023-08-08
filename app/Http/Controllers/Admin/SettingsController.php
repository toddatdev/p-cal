<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPUnit\Exception;

class SettingsController extends Controller
{

    /**
     * @return Factory|View|Application
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {

        $salesPersons = AdminSetting::where('setting_type', 'sales_person')->where('status', 0)->where('is_del', 0)->get();
        $platforms = AdminSetting::where('setting_type', 'platform')->where('status', 0)->where('is_del', 0)->get();
        $types = AdminSetting::where('setting_type', 'type')->where('status', 0)->where('is_del', 0)->get();

        return view('admin.settings.index', compact('salesPersons','platforms','types'));
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('admin_settings', 'name')->ignore($request->input('id')),
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }

            $id = $request['id'];

            if (empty($id)) {

                $adminSettings = new AdminSetting();
            } else {

                $adminSettings = AdminSetting::where('id', $id)->first();
            }



            $adminSettings->name = $request['name'];
            $adminSettings->setting_type = $request['setting_type'];

            $adminSettings->save();

            return response()->json(['success' => true, 'message' => 'Successfully saved.']);
        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }



    public function destroy(Request $request)
    {
        try {

            $data = AdminSetting::findOrFail($request->id);

            $data->delete();

            return response()->json(['success' => true, 'message' => 'Delete Successfully.']);

        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

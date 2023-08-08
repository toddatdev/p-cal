<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    use PasswordValidationRules;

    /**
     * @return Factory|View|Application
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {

        $userInfo = Auth::user();
        $userInfo['role'] = Role::where('id', $userInfo['role_id'])->pluck('name')->first();

        return view('admin.profile.index', ['profile' => $userInfo]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {

        $userInfo = Auth::user();
        $activeUser = $userInfo['role_id'];

        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($request->input('user_id')),
                ],
                'phone' => ['required', 'regex:/^[0-9\+\-\(\)]+$/'],
                'job_title' => 'required_if:activeUser,!=,null|max:255',
                'profile_image' => [
                    'sometimes', // Only apply the rule if the field is present
                    'nullable',
                    'file',
                    'mimes:jpeg,jpg,png,svg',
                    'max:5000',
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()]);
            }

            $user = User::where(['id' => $request['user_id'],    'is_del' => 0])->first();
            if (empty($user))
                return response()->json(['success' => false, 'message' => 'Invalid user data.']);


            if ($user['id'] != $userInfo['id'])
                return response()->json(['success' => false, 'message' => 'You are not allowed to perform this action.']);

            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->phone = trim($request['phone']);
            if (!empty($activeUser))
                $user->job_title = $request['job_title'];

            if ($request['remove_profile'] == 1)
                $user->profile_photo_path = null;

            if ($request->hasFile('profile_image')) {

                $image = $request->file('profile_image');
                $imageName = $image->getClientOriginalName();
                $imagePath = $image->storeAs('images', $imageName, 'public');
                $user->profile_photo_path = $imagePath;
            }
            if ($request['change_password']){
                $user->password = Hash::make($request['change_password']);
            }

            $user->save();

            return response()->json(['success' => true, 'message' => 'Profile has been successfully saved.']);
        }
        catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

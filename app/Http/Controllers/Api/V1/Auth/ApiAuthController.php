<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Operator;
use Illuminate\Http\Request;
use App\Traits\ActivityTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class ApiAuthController extends Controller
{
    use ActivityTrait;

    public function  __construct()
    {
        $this->middleware('auth:api', ['except' => ['registration', 'login_for_user', 'LoginWithThirdPartyApi', 'UserProfileUpdate', 'userInfo']]);
    }

    protected function guard()
    {
        return Auth::guard('api');
    }

    public function registration(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'name' => 'required|string',
            'mobile' => 'required|regex:/(01)[0-9]{9}/|unique:users,mobile',
            'operator' => 'required|string',
            'password' => 'required|min:7'
        ]);

        if ($validator->fails()) {

            $this->activity_log('Registration', 'Default Registration', implode(",", $validator->messages()->all()), 'Faild', $request->mobile);
            return $this->sendError(true, 'Validation Error.', $validator->messages()->all(), 406);
        } else {


            try {

                $user = User::create([
                    'name' => $request->name,
                    'username' => $request->mobile,
                    'mobile' => $request->mobile,
                    'email' => $request->email ?? '',
                    'password' => Hash::make($request->password),
                    'usertype' => "User",
                    'operator' => $request->operator,
                    'register_by' => "App"
                ]);


                if ($user->save()) {

                    $set_request = new Request([
                        'username' => $request->mobile,
                        'password' => $request->password
                    ]);

                    $this->activity_log('Registration', 'Default Registration', 'Registration Successfully', 'Done', $user->id);

                    return $this->login_for_user($set_request);
                } else {
                    return $this->success_error(true, 'Registration Faild!', '', 200);
                }


                return $this->ResponseJson(false, 'Registration Successful!', $user, 200);
            } catch (\Throwable $th) {
                $this->activity_log('Registration', 'Registration Insert Error', "Create User Error In Database", 'Faild', $request->mobile);
                return $this->sendError(true, 'Registration Insert Error', $th->getMessage(), 406);
            }
        }
    }

    public function LoginWithThirdPartyApi(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            //'operator' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError(true, 'Validation Error.', $validator->messages()->all(), 406);
        } else {


            $set_request = new Request([
                'username' => $request->email,
                'password' => $request->email . 'thirdPartyApi',
            ]);

            if (User::where("username", $request->email)->exists()) {
                $this->activity_log('Login', 'Social Login', 'Re-login With Email', 'Done', $request->email);
                return $this->login_for_user($set_request);
            } else {

                try {

                    $user = User::create([
                        'name' => $request->name,
                        'username' => $request->email,
                        'email' => $request->email,
                        'password' => Hash::make($request->email . 'thirdPartyApi'),
                        'user_type' => "User",
                        'registered_by' => "Google"
                    ]);

                    return $this->login_for_user($set_request);
                } catch (\Throwable $th) {
                    $this->activity_log('Registration', 'Social Login', 'Database Insert Error', 'Faild', $request->email);
                    return $this->sendError(true, 'Registration Insert Error', 'Database Insert Error', 406);
                }
            }
        }
    }


    public function UserProfileUpdate(Request $request)
    {

        $validator =  \Validator::make($request->all(), [
            'user_id' => 'required|int',
            //'profile_photo_path'=>'required|mimes:jpeg,jpg,png,gif|required|max:10000',
            'name' => 'required|string',
            'operator' => 'required|string',
            'mobile' => 'numeric|string',

        ]);


        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response
            $this->activity_log('Profile', 'Profile Update', 'Profile data not validated', 'Faild', $request->user_id);
            return $this->ResponseJson(true, "Input data error", $validator->errors(), 200);
        } else {

            $operator = Operator::where('name', 'like', $request->operator)->first();
            $dt = User::findOrFail($request->user_id);

            $dt->name = isset($request->name) ? $request->name : $dt->name;
            $dt->mobile = isset($request->mobile) ? $request->mobile : $dt->mobile;
            if (isset($request->operator) && !empty($request->operator)) {
                $operator = Operator::where('name', 'like', '%' . $request->operator . '%')->first();
                $dt->operator = $operator ? $operator->id : null;
            }

            // if ($request->file('profile_photo_path')) {
            //     $file = $request->file('profile_photo_path');
            //     $Ext = $file->getClientOriginalExtension();
            //     $file_path = public_path('profile_image/');
            //     $iName = date('YmdHis') . "." . $Ext;
            //     $dt->profile_photo_path = 'public/profile_image/' . $iName;
            //     $file->move($file_path, $iName);
            // }
            $dt->save();
            $this->activity_log('Profile', 'Profile Update', 'Profile Data Updated', 'Done', $dt->id);
            return $this->ResponseJson(false, 'Profile Updated Successfully', $dt, 200);
        }
    }



    public function login_for_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $this->activity_log('Registration', 'ThirdParty Registration', implode(",", $validator->messages()->all()), 'Failed', $request->username ?? '');
            return $this->sendError(true, 'Validation Error.', $validator->messages()->all(), 406);
        }

        // Optional early check: if the user exists but inactive, short-circuit
        $candidate = User::where('username', $request->username)->first();
        if ($candidate && !$this->isUserActive($candidate)) {
            $this->activity_log('Login', 'Default Login', 'User account not active', 'Failed', $request->username);
            return $this->ResponseJson(true, 'User account is not active. Please contact admin.', (object)[], 403);
        }

        // Attempt JWT auth
        $token = $this->guard()->attempt([
            'username' => $request->username,
            'password' => $request->password
        ]);

        if (!$token) {
            $this->activity_log('Login', 'Default Login', 'Login credentials not matched', 'Failed', $request->username);
            return $this->ResponseJson(true, "Invalid Credentials.", (object)[], 401);
        }

        // Safety check after auth too (in case status changed mid-way)
        /** @var User $user */
        $user = auth('api')->user();
        if (!$this->isUserActive($user)) {
            // Invalidate the just-issued token and deny access
            try {
                $this->guard()->logout(true);
            } catch (\Throwable $e) {
            }
            $this->activity_log('Login', 'Default Login', 'User account not active', 'Failed', $user->id);
            return $this->ResponseJson(true, 'User account is not active. Please contact admin.', (object)[], 403);
        }

        // Build response data
        $data = [
            "id"            => $user->id,
            "name"          => $user->name ?? '',
            "email"         => $user->email ?? '',
            "username"      => $user->username ?? '',
            "mobile"        => $user->mobile ?? '',
            "usertype"      => $user->usertype ?: 'User',
            "operator"      => $user->operator ? optional(Operator::find($user->operator))->name : '',
            "registered_by" => $user->registered_by ?? '',
        ];

        $authenticate_token = $this->respondWithToken($token);
        $data["jwt_token"] = $authenticate_token->getData(true);

        $this->activity_log('Login', 'Default Login', 'Login Successfully', 'Done', $user->id);
        return $this->ResponseJson(false, 'Login Successful!', $data, 200);
    }

    /**
     * Treat user as active only if matches your convention.
     * Adjust to your schema: e.g., is_active === 'Active' or is_active == 1.
     */
    private function isUserActive(User $user): bool
    {
        // Common patterns:
        // return (string)$user->is_active === 'Active';
        // return (int)$user->is_active === 1;
        // Support both (string flag or tinyint) to be safe:
        return ($user->is_active === 'Active') || ($user->is_active === 1) || ($user->is_active === '1');
    }

    public function userDelete($id = null)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_active' => 'Deactive'
        ]);

        return $this->ResponseJson(false, 'User Deleted Successfully', $user, 200);
    }
    public function userInfo($id = null)
    {
        // 1) Get user (by id or current auth user)
        if ($id) {
            $data = User::findOrFail($id);
        } else {
            $data = $this->guard()->user();
        }

        if (!$data) {
            return $this->sendError(true, 'User not found.', [], 404);
        }

        // 2) Convert to array
        $user = $data->toArray();

        // 3) Format dates
        if (!empty($data->created_at)) {
            $user['created_at'] = Carbon::parse($data->created_at)->format('d M Y, h:i A');
        }
        if (!empty($data->updated_at)) {
            $user['updated_at'] = Carbon::parse($data->updated_at)->format('d M Y, h:i A');
        }
        if (!empty($data->email_verified_at)) {
            $user['email_verified_at'] = Carbon::parse($data->email_verified_at)->format('d M Y, h:i A');
        }

        // 4) Replace null values with ""
        array_walk_recursive($user, function (&$v) {
            if (is_null($v)) {
                $v = "";
            }
        });

        return $this->ResponseJson(false, 'User Profile Data', $user, 200);
    }


    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }
}

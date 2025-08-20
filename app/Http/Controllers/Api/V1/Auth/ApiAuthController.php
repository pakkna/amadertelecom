<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Validator;
use App\Models\User;
use App\Models\Packages;
use App\Models\Category;
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
        $this->middleware('auth:api', ['except' => ['registration', 'login_for_user', 'LoginWithThirdPartyApi', 'getPacakgeList']]);
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

            $dt = User::findOrFail($request->user_id);

            $dt->name = isset($request->name) ? $request->name : $dt->name;
            $dt->mobile = isset($request->mobile) ? $request->mobile : $dt->mobile;
            $dt->operator = isset($request->operator) ? $request->operator : "";

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
            return $this->ResponseJson(false, 'Profile Updated Successfully', "", 200);
        }
    }


    public function login_for_user(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {

            $this->activity_log('Registration', 'ThirdParty Registration', implode(",", $validator->messages()->all()), 'Faild', $request->mobile);
            return $this->sendError(true, 'Validation Error.', $validator->messages()->all(), 406);
        } else {

            $token = $this->guard()->attempt(['username' => $request->username, 'password' => $request->password]);

            if ($token) {
                $user =  auth('api')->user();
                //$update_user = User::UpdateLoginDate($user->id);
                $data["id"] = $user->id;
                $data["name"] = $user->name;
                $data["email"] = $user->email != "" ? $user->email : '';
                $data["username"] = $user->username != "" ? $user->username : '';
                $data["mobile"] = $user->mobile != "" ? $user->mobile : '';
                $data["usertype"] = $user->usertype != "" ? $user->usertype : 'User';
                $data["operator"] = $user->operator != "" ? $user->operator : '';
                $data["registered_by"] = $user->registered_by;
                $authenticate_token = $this->respondWithToken($token);
                $data["jwt_token"] = $authenticate_token->getData(true);

                $this->activity_log('Login', 'Deafult Login', 'Login Successfully', 'Done', $user->id);
                return $this->ResponseJson(false, 'Login Successfull!', $data, 200);
            } else {
                $this->activity_log('Login', 'Deafult Login', 'Login Credentials Not Matched', 'Faild', $request->username);
                return $this->ResponseJson(true, "Invalid Credentials.", (object)[], 401);
            }
        }
    }


    public function userInfo()
    {
        $data = $this->guard()->user();
        return $this->ResponseJson(false, 'User Profile Data', $data, 200);
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

    public function getPacakgeList(Request $request)
    {
        $packageQuery = Packages::query()
            ->with(['operator', 'category'])
            ->where('packages.status', 1)
            ->when($request->filled('operator'), function ($q) use ($request) {
                $ops = is_array($request->operator)
                    ? $request->operator
                    : array_map('trim', explode(',', $request->operator));

                $q->whereHas('operator', fn ($oq) => $oq->whereIn('name', $ops));
            })
            ->when($request->filled('category'), function ($q) use ($request) {
                $cats = is_array($request->category)
                    ? $request->category
                    : array_map('trim', explode(',', $request->category));

                $q->whereHas('category', fn ($cq) => $cq->whereIn('name', $cats));
            })
            ->orderByDesc('packages.created_at');

        $getlist = $packageQuery->get();

        return $this->ResponseJson(false, 'Package List', $getlist, 200);
    }
}

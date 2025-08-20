<?php

namespace App\Http\Controllers\BackendControllers;

use App\Models\User;
use App\Models\Location;
use App\Models\AssignBus;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected function guard()
    {
        return Auth::guard('api');
    }
    public function registered_app_users()
    {
        return view('dashboard..app_users.registered_app_users');
    }

    public function app_users_data(Request $request)
    {
        $date = $request->get('columns')[5]['search']['value'];

        if ($date != '') {

            list($start_date, $end_date) = explode('~', preg_replace('/\s+/', '', $date));

            $start_date = date_validate($start_date);
            $end_date = date_validate($end_date);
        } else {

            $time = strtotime(date('Y-m-d') . '-30 days');
            $start_date = date_validate(date('Y-m-d', $time));
            $end_date = date_validate(date('Y-m-d'));
        }

        $query  = User::whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"])->where('usertype', 'User')->get();


        return Datatables::of($query)

            ->addColumn('is_active', function ($result) {

                if ($result->is_active == "Active") {
                    return '<div class="badge badge-pill badge-success">' . $result->is_active . '</div>';
                } else {
                    return '<div class="badge badge-pill badge-danger">' . $result->is_active . '</div>';
                }
            })
            ->addColumn('action', function ($result) {
                $dt = "'delete'";
                return '<div role="group" class="btn-group-md btn-group text-white">
                <a href="javascript:void(0)" onclick="ajaxStatus(' . $result->id . ',this,' . $dt . ')" class="btn-shadow btn btn-danger" title="Bus Remove"><i class="fa fa-trash"></i></a>
                </div>';
                /*  <a href="/route-update/' . $result->id . '"  class="btn-shadow btn btn-warning mr-3" title="Route Update"><i class="fa fa-edit"></i></a> */
            })
            ->rawColumns(['action', 'is_active'])
            ->addIndexColumn()
            ->make(true);
    }

    public function app_users_delete(Request $request)
    {
        $user = User::find($request->id);
        if ($user->delete()) {
            echo json_encode(['msg' => 'Success', 'type' => 'delete', 'action' => '1']);
        } else {
            echo json_encode(['msg' => 'Error', 'type' => 'delete', 'action' => '0']);
        }
    }

    public function show()
    {
        return view('dashboard.app_users.admin_profile');
    }
    public function edit(Request $request)
    {


        $data = DB::table('users')->select('profile_photo', 'password')->where('id', $request->id)->first();

        if (!empty($request->image)) {

            $image = $request->file('image');

            $input['image_name'] = time() . '.' . $image->getClientOriginalExtension();

            $destination_path = public_path('/assets/images/avatars');

            $image->move($destination_path, $input['image_name']);
        } else {
            $input['image_name'] = $data->profile_photo;
        }

        if (!empty($request->password)) {
            $pass = Hash::make($request->password);
        } else {
            $pass = $data->password;
        }


        $data = [

            "name" => $request->name,

            "email" => $request->email,

            "username" => $request->email,

            "profile_photo" => $input['image_name'],

            "mobile" => $request->mobile,

            "password" => $pass,

            "updated_at" => date('Y-m-d H:i:s')

        ];


        try {

            DB::table('users')->where('id', $request->id)->update($data);
            return redirect("edit-profile")->with("flashMessageSuccess", "Profile Changed Successfully");
        } catch (\Illuminate\Database\QueryException $e) {

            $errorCode = $e->errorInfo[1];
            if ($errorCode == '1062') {
                return redirect("edit-profile")->with("flashMessageDanger", "User Name Already Exists ! Try Another One.");
            }
        }
    }

    public function driver_bus_info()
    {
        $user = $this->guard()->user();

        if (empty($user)) {
            return $this->ResponseJson(false, 'User Auth Token Expired!', (object)[], 200);
        }


        $singleAssignBus = AssignBus::select(
            'bus_list.id as bus_id',
            'bus_list.bus_name',
            'bus_list.bus_number',
            'route_list.id as route_id',
            'route_list.route_name',
            'route_list.route_code',
            'route_list.route_details',
            'route_list.start_time_slot',
            'route_list.departure_time_slot',
            'route_list.route_map_url',
        )
            ->join('bus_list', 'bus_list.id', 'assign_bus_route_to_driver.bus_id')
            ->join('route_list', 'route_list.id', 'assign_bus_route_to_driver.route_id')
            ->where('assign_bus_route_to_driver.driver_user_id', $user->id)->first();

        if (!empty($singleAssignBus)) {
            $data = [
                "driver_id" => $user->id,
                "bus_id" =>  $singleAssignBus->bus_id,
                "route_id" =>  $singleAssignBus->route_id,
                "bus_name" =>  $singleAssignBus->bus_name,
                "bus_number" =>  $singleAssignBus->bus_number,
                "route_name" => $singleAssignBus->route_name,
                "route_code" => $singleAssignBus->route_code,
                "route_details" => $singleAssignBus->route_details,
                "start_time_slot" => json_decode($singleAssignBus->start_time_slot),
                "departure_time_slot" => json_decode($singleAssignBus->departure_time_slot),
                "route_map_url" => $singleAssignBus->route_map_url
            ];
            return $this->ResponseJson(false, 'Driver Bus Assign Info', $data, 200);
        } else {

            return $this->ResponseJson(false, 'Driver Not Assign Any Bus', $data, 200);
        }
    }

    public function driver_bus_location_post(Request $request)
    {
        $user = $this->guard()->user();

        if (empty($user)) {
            return $this->ResponseJson(false, 'User Auth Token Expired!', (object)[], 200);
        }

        $validator = \Validator::make($request->all(), [
            'lat' => 'required',
            'long' => 'required',
            'bus_id' => 'required|numeric|exists:bus_list,id',
            'route_id' => 'required|numeric|exists:route_list,id',
            'user_id' => 'required|numeric|exists:users,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError(true, 'Validation Error.', $validator->messages()->all(), 406);
        } else {



            try {
                $todayLocation = Location::where('bus_id', $request->bus_id)
                    ->where('route_id', $request->route_id)
                    ->where('user_id', $user->id)
                    ->whereDate('created_at', date('Y-m-d'));

                if (!$todayLocation->exists()) {
                    $data = [
                        'lat' => $request->lat,
                        'long' => $request->long,
                        'bus_id' => $request->bus_id,
                        'route_id' => $request->route_id,
                        'user_id' => $user->id,
                    ];
                    $saveLocation = Location::create($data);
                } else {
                    $data = [
                        'lat' => $request->lat,
                        'long' => $request->long,
                    ];
                    $updateLocation =  $todayLocation->update($data);
                }

                return $this->ResponseJson(false, 'Bus location save', ['lat' => $request->lat, 'long' => $request->long], 200);
            } catch (\Throwable $th) {
                return $this->sendError(true, 'Registration Insert Error', $th->getMessage(), 406);
            }
        }
    }
    public function bus_location_get(Request $request)
    {
        $user = $this->guard()->user();

        if (empty($user)) {
            return $this->ResponseJson(false, 'User Auth Token Expired!', (object)[], 200);
        }

        $validator = \Validator::make($request->all(), [
            'bus_id' => 'required|numeric|exists:bus_list,id',
            'route_id' => 'required|numeric|exists:route_list,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError(true, 'Validation Error.', $validator->messages()->all(), 406);
        } else {

            try {
                $todayLocation = DB::table('location')->where('bus_id', $request->bus_id)
                    ->where('route_id', $request->route_id)
                    ->whereDate('created_at', date('Y-m-d'))->first();


                return $this->ResponseJson(false, 'Bus Location Info', $todayLocation, 200);
            } catch (\Throwable $th) {
                return $this->sendError(true, 'Registration Insert Error', $th->getMessage(), 406);
            }
        }
    }
}

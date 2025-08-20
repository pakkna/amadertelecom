<?php

namespace App\Http\Controllers\BackendControllers;

use DB;
use App\Models\User;
use App\Models\Driver;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function registration_view()
    {

        return view('dashboard.driver.adddriver');
    }
    public function driverStore(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'email|unique:users,email',
            'mobile' => 'numeric|unique:users,mobile',
            'address' => 'required',
            'dob' => 'required|date',
            'nid_no' => 'required|numeric'
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
        ]);


        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {

            try {

                if ($request->hasFile('nid_image')) {

                    $image = $request->file('nid_image');

                    $name = time() . '.' . $image->getClientOriginalExtension();

                    $destination_path = public_path('/assets/images/avatars/driver_nid/');

                    $image_path = '/assets/images/avatars/driver_nid/' . $name;
                } else {
                    $image_path = '/assets/images/avatars/driver_nid/demonidpic.jpg';
                }

                DB::beginTransaction();

                $userData = [
                    'name' => $request->name,
                    'username' => $request->mobile,
                    'email' => $request->email ?? '',
                    'mobile' => $request->mobile ?? '',
                    'address' => $request->address ?? '',
                    'usertype' => 'Driver',
                    'password' => Hash::make($request->password),
                    'register_by' => 'System Administrator',
                    'is_active' => 'Active',
                ];

                $userSave = User::create($userData);

                if ($userSave->id > 0) {

                    $driverData = [
                        'driver_name' => ucfirst($request->name),
                        'primary_contact' => $request->mobile ?? '',
                        'license_number' => $request->license_number ?? '',
                        'license_photo' => $request->license_photo ?? '',
                        'address' => $request->address ?? '',
                        'date_of_birth' => date('Y-m-d', strtotime($request->dob)),
                        'sex' => "Male",
                        'nid_number' => $request->nid_no,
                        'nid_photo' => $image_path,
                        'user_id' => $userSave->id,
                        'is_sign_in' => "None",
                    ];

                    $driverSave = Driver::create($driverData);
                } else {
                    return redirect()->back()->withErrors("flashMessageDanger", "User Not Save");
                }

                DB::commit();

                if ($request->hasFile('nid_image')) {
                    $image->move($destination_path, $name);
                }

                //$this->send_raw_email_with_attachment($request->Email, $password);

                return redirect()->back()->with("flashMessageSuccess", "Your Driver info Stored Succesfully");
            } catch (\Throwable $th) {
                DB::rollback();

                return redirect()->back()->withErrors("flashMessageDanger", $th->getMessage());
            }
        }
    }
    public function registered_drivers()
    {
        return view('dashboard.driver.showdrivers');
    }
    function registered_drivers_data(Request $request)
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

        $query  = User::join('driver_info', 'driver_info.user_id', 'users.id')
            ->where('usertype', 'Driver')
            ->whereBetween('users.created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"])
            ->get();

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
                    <a href="javascript:void(0)" onclick="ajaxStatus(' . $result->user_id . ',this,' . $dt . ')" class="btn-shadow btn btn-danger" title="Bus Remove"><i class="fa fa-trash"></i></a>
                    </div>';
                /*  <a href="/route-update/' . $result->id . '"  class="btn-shadow btn btn-warning mr-3" title="Route Update"><i class="fa fa-edit"></i></a> */
            })
            ->rawColumns(['action', 'is_active'])
            ->addIndexColumn()
            ->make(true);
    }
    public function driver_delete(Request $request)
    {

        $user = User::find($request->id);
        if ($user->driver->delete()) {
            $user->delete();
            echo json_encode(['msg' => 'Success', 'type' => 'delete', 'action' => '1']);
            //return redirect()->back()->with("flashMessageSuccess", "Driver Deleted Succesfully");
        } else {
            echo json_encode(['msg' => 'Error', 'type' => 'delete', 'action' => '0']);
            //return redirect()->back()->withErrors("flashMessageDanger", "Driver Deletion Error!");
        }
    }
}

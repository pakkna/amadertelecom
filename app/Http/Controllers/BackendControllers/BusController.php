<?php

namespace App\Http\Controllers\BackendControllers;

use DB;
use App\Models\Bus;
use App\Models\User;
use App\Models\Route;
use App\Models\AssignBus;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use PhpParser\Node\Expr\Assign;

class BusController extends Controller
{
    public function bus_registration_view()
    {

        return view('dashboard.bus.addbus');
    }
    public function busStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'bus_name' => 'required|max:255',
            'bus_number' => 'required|unique:bus_list,bus_number',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {

            try {
                $BusSave = Bus::create($request->all());
                return redirect()->back()->with("flashMessageSuccess", "Your Bus info Stored Succesfully");
            } catch (\Throwable $th) {
                //DB::rollback();

                return redirect()->back()->withErrors("flashMessageDanger", $th->getMessage());
            }
        }
    }
    public function busList(Request $request)
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

        $query  = Bus::whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"])->orderBy('created_at', 'DESC');


        return Datatables::of($query)
            ->addColumn('is_active', function ($result) {

                if ($result->is_active == "Active") {
                    return '<div class="badge badge-pill badge-success">' . $result->is_active . '</div>';
                } else {
                    return '<div class="badge badge-pill badge-danger">' . $result->is_active . '</div>';
                }
            })
            ->addColumn('action', function ($result) {

                $statusShow = $result->is_active == "Active" ? '<i class="fa fa-ban mr-2"></i>InActive' : '<i class="fa fa-check-circle mr-2"></i>Active';
                $class = $result->is_active == "Active" ? "btn-warning" : "btn-success";

                $dt = "'delete'";
                return '<div role="group" class="btn-group-md btn-group text-white">
                <a href="/bus-status/' . $result->is_active . '/' . $result->id . '"  class="btn-shadow btn ' . $class . ' mr-3" title="Change Status">' . $statusShow . '</a>
                <a href="javascript:void(0)" onclick="ajaxStatus(' . $result->id . ',this,' . $dt . ')" class="btn-shadow btn btn-danger" title="Bus Remove"><i class="fa fa-trash"></i></a>
                </div>';
            })
            ->rawColumns(['action', 'is_active'])
            ->addIndexColumn()
            ->make(true);
    }
    public function busStatusChange($status = null, $id = null)
    {;
        $status = ($status == "Active") ? "InActive" : "Active";
        $bus = Bus::find($id);
        $bus->is_active = $status;
        if ($bus->update()) {
            return redirect()->back()->with("flashMessageSuccess", "Your Bus Status Updated Succesfully");
        } else {
            return redirect()->back()->with("flashMessageDanger", "Your Bus Status Updated Faild");
        }
    }
    public function busDelete(Request $request)
    {

        $bus = Bus::find($request->id);
        if ($bus->delete()) {
            echo json_encode(['msg' => 'Success', 'type' => 'delete', 'action' => '1']);
        } else {
            echo json_encode(['msg' => 'Error', 'type' => 'delete', 'action' => '0']);
        }
    }

    public function busAssignToRoute()
    {
        $assignBuslist = AssignBus::pluck('bus_id')->toArray();
        $driverUserlist = AssignBus::pluck('driver_user_id')->toArray();

        $busList = Bus::select('id', 'bus_name', 'bus_number')
            ->where('is_active', 'Active')
            ->whereNotIn('id', array_values($assignBuslist))
            ->orderBy('created_at', 'DESC')
            ->get();

        $driver_list = User::where('usertype', 'Driver')
            ->where('is_active', 'Active')
            ->whereNotIn('id', array_values($driverUserlist))
            ->orderBy('created_at', 'DESC')
            ->get();
        $routeList = Route::orderBy('created_at', 'DESC')->get();
        return view('dashboard.bus.assignbus_driver', compact('busList', 'driver_list', 'routeList'));
    }
    public function assign_bus_store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'bus_id' => 'required|numeric|unique:assign_bus_route_to_driver,bus_id',
                'driver_user_id' => 'required|unique:assign_bus_route_to_driver,driver_user_id',
                'route_id' => 'required|numeric',
            ],
            [
                'bus_id.unique' => 'This Bus Already Assign A Driver!',
                'driver_user_id.unique' => 'This Driver Already Assign With A Bus!',
                'bus_id.required' => 'The Bus field is required.',
                'driver_user_id.required' => 'The Driver field is required.',
                'route_id.required' => 'The Route field is required.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {

            try {
                $AssinBusSave = AssignBus::create($request->all());
                return redirect()->back()->with("flashMessageSuccess", "Your Bus Assigned Succesfully");
            } catch (\Throwable $th) {
                //DB::rollback();
                return redirect()->back()->withErrors("flashMessageDanger", $th->getMessage());
            }
        }
    }
    public function assign_bus_data(Request $request)
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

        $query  = AssignBus::select(
            'assign_bus_route_to_driver.id as id',
            'bus_list.bus_name',
            'bus_list.bus_number',
            'users.name',
            'users.mobile',
            'route_list.route_name',
            'route_list.route_code',
            'assign_bus_route_to_driver.created_at'
        )
            ->join('bus_list', 'bus_list.id', 'assign_bus_route_to_driver.bus_id')
            ->join('users', 'users.id', 'assign_bus_route_to_driver.driver_user_id')
            ->join('route_list', 'route_list.id', 'assign_bus_route_to_driver.route_id')
            ->whereBetween('assign_bus_route_to_driver.created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"])
            ->orderBy('assign_bus_route_to_driver.created_at', 'DESC');


        return Datatables::of($query)
            ->addColumn('action', function ($result) {
                $dt = "'delete'";
                return '<div role="group" class="btn-group-md btn-group text-white">
                <a href="javascript:void(0)" onclick="ajaxStatus(' . $result->id . ',this,' . $dt . ')"  class="btn-shadow btn btn-danger" title="Bus Remove"><i class="fa fa-trash"></i></a>
                </div>';
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function AssignBusDelete(Request $request)
    {

        $Assignbus = AssignBus::find($request->id);
        if ($Assignbus->delete()) {
            echo json_encode(['msg' => 'Success', 'type' => 'delete', 'action' => '1']);
        } else {
            echo json_encode(['msg' => 'Error', 'type' => 'delete', 'action' => '0']);
        }
    }
    public function AssignBusRouteList()
    {
        return view('dashboard.bus.assign_bus_route_list');
    }
    public function AssignBusRouteListData(Request $request)
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

        $query  = DB::table('assign_bus_route_to_driver as assign_bus')->select(
            'assign_bus.id as id',
            'bus_list.bus_name',
            'bus_list.bus_number',
            'users.name',
            'users.mobile',
            'route_list.id as route_id',
            'route_list.route_name',
            'route_list.route_code',
            'route_list.start_time_slot',
            'route_list.departure_time_slot',
            'route_list.route_map_url',
            'assign_bus.created_at'
        )
            ->join('bus_list', 'bus_list.id', 'assign_bus.bus_id')
            ->join('users', 'users.id', 'assign_bus.driver_user_id')
            ->join('route_list', 'route_list.id', 'assign_bus.route_id')
            ->whereBetween('assign_bus.created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"])
            ->orderBy('assign_bus.created_at', 'DESC');

        return Datatables::of($query)
            ->addColumn('start_time_slot', function ($result) {
                $array = json_decode($result->start_time_slot);
                $html = '';
                foreach ($array as $time) {
                    $html .= '<span class="badge badge-pill badge-success mr-1">' . $time . '</span>';
                }
                return $html;
            })
            ->addColumn('departure_time_slot', function ($result) {
                $array = json_decode($result->departure_time_slot);
                $html = '';
                foreach ($array as $time) {
                    $html .= '<span class="badge badge-pill badge-primary mr-1">' . $time . '</span>';
                }
                return $html;
            })
            ->addColumn('route_map_url', function ($result) {
                return '<div role="group" style="cursor: pointer;" class="btn-group-md btn-group text-white" onclick=go_map("' . $result->route_map_url . '")>
                <img src="assets/images/map.png" class="img-responsive" alt="Map" title="map"
                </div>';
            })
            ->addColumn('action', function ($result) {
                $dt = "'delete'";
                return '<div role="group" class="btn-group-md btn-group text-white">
                <a href="javascript:void(0)" onclick="ajaxStatus(' . $result->id . ',this,' . $dt . ')" class="btn-shadow btn btn-danger" title="Bus Remove"><i class="fa fa-trash"></i></a>
                </div>';
                /*  <a href="/route-update/' . $result->id . '"  class="btn-shadow btn btn-warning mr-3" title="Route Update"><i class="fa fa-edit"></i></a> */
            })
            ->rawColumns(['action', 'start_time_slot', 'departure_time_slot', 'route_details', 'route_map_url'])
            ->addIndexColumn()
            ->make(true);
    }
    public function unassign_bus_list()
    {
        return view('dashboard.bus.unassign_bus_list');
    }
    public function unassign_bus_data(Request $request)
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

        $assignBuslist = AssignBus::pluck('bus_id')->toArray();

        $query = Bus::whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"])->whereNotIn('id', array_values($assignBuslist))->orderBy('created_at', 'DESC');


        return Datatables::of($query)
            ->addColumn('is_active', function ($result) {

                if ($result->is_active == "Active") {
                    return '<div class="badge badge-pill badge-success">' . $result->is_active . '</div>';
                } else {
                    return '<div class="badge badge-pill badge-danger">' . $result->is_active . '</div>';
                }
            })
            ->addColumn('status', function ($result) {

                return '<div class="badge badge-pill badge-danger">Unassign</div>';
            })
            ->addColumn('action', function ($result) {

                $statusShow = $result->is_active == "Active" ? '<i class="fa fa-ban mr-2"></i>InActive' : '<i class="fa fa-check-circle mr-2"></i>Active';
                $class = $result->is_active == "Active" ? "btn-warning" : "btn-success";

                $dt = "'delete'";
                return '<div role="group" class="btn-group-md btn-group text-white">
                </a>
                <a href="/assgin-bus" class="btn-shadow btn btn-primary mr-3" title="Bus Add"><i class="metismenu-icon pe-7s-network mr-2"></i> Assign</a>
                <a href="/bus-status/' . $result->is_active . '/' . $result->id . '"  class="btn-shadow btn ' . $class . ' mr-3" title="Change Status">' . $statusShow . '</a>
                <a href="javascript:void(0)" onclick="ajaxStatus(' . $result->id . ',this,' . $dt . ')" class="btn-shadow btn btn-danger" title="Bus Remove"><i class="fa fa-trash"></i></a>
                </div>';
            })
            ->rawColumns(['action', 'is_active', 'status'])
            ->addIndexColumn()
            ->make(true);
    }
}

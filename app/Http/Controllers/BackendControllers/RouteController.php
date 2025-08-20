<?php


namespace App\Http\Controllers\BackendControllers;

use Validator;
use App\Models\Route;
use App\Models\AssignBus;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RouteController extends Controller
{
    protected function guard()
    {
        return Auth::guard('api');
    }

    public function route_create()
    {

        return view('dashboard.route.addroute');
    }
    public function routeStore(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'route_name' => 'required|max:255|unique:route_list,route_code',
            'route_code' => 'required|unique:route_list,route_code',
            'route_details' => 'required|string',
            'start_time_slot' => 'required|array|min:1',
            'departure_time_slot' => 'required|array|min:1',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } else {

            try {

                $data = [
                    'route_name' => $request->route_name,
                    'route_code' => $request->route_code,
                    'route_details' =>  $request->route_details,
                    'start_time_slot' => json_encode($request->start_time_slot),
                    'departure_time_slot' => json_encode($request->departure_time_slot),
                    'route_map_url' => $request->route_map_url,
                ];

                $RouteSave = Route::create($data);
                return redirect()->back()->with("flashMessageSuccess", "Your Route info Stored Succesfully");
            } catch (\Throwable $th) {
                //DB::rollback();
                return redirect()->back()->withErrors("flashMessageDanger", $th->getMessage());
            }
        }

        //return view('dashboard.route.addroute');
    }
    public function routeList()
    {
        return view('dashboard.route.routelist');
    }
    public function routeData(Request $request)
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

        $query  = Route::whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"])->orderBy('created_at', 'DESC');


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
            ->addColumn('route_details', function ($result) {
                return '<div role="group" class="btn-group-md btn-group text-white">
                <a href="javascript:void(0);" onclick="get_modal_html(' . $result->id . ')"  data-toggle="modal" data-target=".bd-example-modal-lg"  class="btn-shadow btn btn-sm btn-warning" title="Route details"><i class="fa fa-question-circle mr-2"></i>View Details</a>
                </div>';
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
    public function routeInfoGet(Request $request)
    {

        $info = Route::find($request->id);

        return $info->route_details ?? '';
    }
    public function routedelete(Request $request)
    {
        $route = Route::find($request->id);
        if ($route->delete()) {
            echo json_encode(['msg' => 'Success', 'type' => 'delete', 'action' => '1']);
        } else {
            echo json_encode(['msg' => 'Error', 'type' => 'delete', 'action' => '0']);
        }
    }

    public function route_schedule_list(Request $request)
    {
        //$user_id = $this->guard()->id ?? 0;
        $routes = Route::all();
        $routeSchedules = [];

        foreach ($routes as $singleRoute) {
            $data = [
                "id" =>  $singleRoute->id,
                "route_name" => $singleRoute->route_name,
                "route_code" => $singleRoute->route_code,
                "route_details" => $singleRoute->route_details,
                "start_time_slot" => json_decode($singleRoute->start_time_slot),
                "departure_time_slot" => json_decode($singleRoute->departure_time_slot),
                "route_map_url" => $singleRoute->route_map_url
            ];

            array_push($routeSchedules, $data);
        }


        return $this->ResponseJson(false, 'Route Schedule', $routeSchedules, 200);
    }
    public function route_wise_bus(Request $request)
    {
        //$user_id = $this->guard()->id ?? 0;
        $routes = Route::all();

        $routeWiseBusInfo = [];

        foreach ($routes as $singleRoute) {
            $businfo = DB::table('assign_bus_route_to_driver as assign_bus')
                ->leftJoin('bus_list', 'bus_list.id', 'assign_bus.bus_id')
                ->leftJoin('users', 'users.id', 'assign_bus.driver_user_id')
                ->select(
                    'bus_list.id as bus_id',
                    'bus_list.bus_name',
                    'bus_list.bus_number',
                    'users.name as driver_name'
                )
                ->where('assign_bus.route_id', $singleRoute->id);
            //->groupBy('assign_bus.id');

            $total_bus = $businfo->get()->count();
            $dateWiseBusInfo = $businfo->leftJoin('location', 'location.bus_id', 'assign_bus.bus_id')->whereBetween('location.created_at', [date('Y-m-d') . " 00:00:00", date('Y-m-d') . " 23:59:59"]);



            $data = [
                "route_id" =>  $singleRoute->id,
                "route_name" => $singleRoute->route_name,
                "route_code" => $singleRoute->route_code,
                "total_bus" => $total_bus,
                "active_bus" => $dateWiseBusInfo->get()->count() ?? 0,
            ];
            array_push($routeWiseBusInfo, $data);
        }


        return $this->ResponseJson(false, 'Route Schedule', $routeWiseBusInfo, 200);
    }
    public function active_bus_list($route_id = null)
    {

        $activeBusList = DB::table('assign_bus_route_to_driver as assign_bus')
            ->leftJoin('bus_list', 'bus_list.id', 'assign_bus.bus_id')
            ->leftJoin('route_list', 'route_list.id', 'assign_bus.route_id')
            ->leftJoin('users', 'users.id', 'assign_bus.driver_user_id')
            ->leftJoin('location', 'location.bus_id', 'assign_bus.bus_id')->whereBetween('location.created_at', [date('Y-m-d') . " 00:00:00", date('Y-m-d') . " 23:59:59"])
            ->select(
                'bus_list.id as bus_id',
                'route_list.id as route_id',
                'users.id as driver_id',
                'bus_list.bus_name',
                'bus_list.bus_number',
                'route_list.route_name',
                'route_list.route_code',
                'users.name as driver_name',

                'location.lat as last_lat',
                'location.long as last_long'
            )
            ->where('assign_bus.route_id', $route_id)->get();

        return $this->ResponseJson(false, 'Route Schedule', $activeBusList, 200);
    }
}

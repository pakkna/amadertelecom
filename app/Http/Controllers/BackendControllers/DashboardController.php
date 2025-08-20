<?php

namespace App\Http\Controllers\BackendControllers;

use App\Models\Bus;
use App\Models\User;
use App\Models\Route;
use App\Models\Driver;
use App\Models\AssignBus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;


class DashboardController extends Controller
{
    public function index(Request $request)
    {


        $totalBus = Bus::count();
        $totalDriver = Driver::count();
        $assignBuslist = AssignBus::pluck('bus_id')->toArray();
        $unAssignBus = Bus::whereNotIn('id', array_values($assignBuslist))->count();
        $assignBus = Bus::whereIn('id', array_values($assignBuslist))->count();
        $totalRoute = Route::count();
        $totalUser = User::where('usertype', 'User')->count();


        return  view('dashboard/index', compact('totalBus', 'totalDriver', 'totalRoute', 'unAssignBus', 'assignBus', 'totalUser'));
    }

    public function all_clear()
    {
        $exitCode1 = Artisan::call('cache:clear');
        $exitCode2 = Artisan::call('route:clear');
        $exitCode3 = Artisan::call('config:clear');
        $exitCode1 = Artisan::call('cache:clear');
        $exitCode1 = Artisan::call('view:clear');

        return redirect('dashboard');
    }
}

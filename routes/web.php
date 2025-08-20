<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BackendControllers\PackageController;
use App\Http\Controllers\BackendControllers\OrderController;

use App\Http\Controllers\BackendControllers\BusController;
use App\Http\Controllers\BackendControllers\UserController;
use App\Http\Controllers\BackendControllers\RouteController;
use App\Http\Controllers\BackendControllers\DriverController;
use App\Http\Controllers\BackendControllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes(['register' => false]);

Route::group(['middleware' => 'auth'], function () {
    //Cache Clear
    Route::get('clear', [DashboardController::class, 'all_clear']);
    //Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Package routes
    Route::get('/packages', [PackageController::class, 'package_list']);
    Route::get('/active-packages', [PackageController::class, 'active_package_list']);
    Route::get('/most-selling-packages', [PackageController::class, 'most_selling_packages']);
    Route::post('/package-store', [PackageController::class, 'packageStore'])->name("package.store");
    Route::post('/edit-package/{id}', [PackageController::class, 'packageStore'])->name("package.edit");
    Route::get('/packages/delete/{id}', [PackageController::class, 'packageDestroy'])->name('package.delete');

    //Order List
    Route::get('/order-list', [OrderController::class, 'order_list']);
    Route::get('/pending-order-list', [OrderController::class, 'pending_order_list']);
    Route::get('/order-completed', [OrderController::class, 'order_completed']);


    //Driver Registration
    Route::get('/driver-registration', [DriverController::class, 'registration_view']);
    Route::post('/driver-store', [DriverController::class, 'driverStore'])->name("driver.store");
    Route::get('/registered-drivers', [DriverController::class, 'registered_drivers'])->name("driver.all");
    Route::post('/registered-drivers-data', [DriverController::class, 'registered_drivers_data'])->name("driver.data");
    Route::post('/driver-delete', [DriverController::class, 'driver_delete'])->name("driver.delete");

    // Bus Registration
    Route::get('/bus-registration', [BusController::class, 'bus_registration_view']);
    Route::post('/bus-store', [BusController::class, 'busStore'])->name("bus.store");
    Route::post('/bus-list', [BusController::class, 'busList'])->name("bus.list");
    Route::get('/bus-status/{status}/{id}', [BusController::class, 'busStatusChange'])->name("bus.status");
    Route::post('/bus-delete', [BusController::class, 'busDelete'])->name("bus.delete");

    // Bus Registration
    Route::get('/route-create', [RouteController::class, 'route_create']);
    Route::post('/route-store', [RouteController::class, 'routeStore'])->name("route.store");
    Route::get('/route-list', [RouteController::class, 'routeList'])->name("route.list");
    Route::post('/route-list-data', [RouteController::class, 'routeData'])->name("route.data");
    Route::post('/route-info-get', [RouteController::class, 'routeInfoGet'])->name("route.info.get");
    Route::post('/route-delete', [RouteController::class, 'routedelete'])->name("route.delete");

    //Assign Bus to Route and Driver
    Route::get('/assgin-bus', [BusController::class, 'busAssignToRoute']);
    Route::post('/assign-bus-store', [BusController::class, 'assign_bus_store'])->name("bus.assign");
    Route::post('/assign-bus-list', [BusController::class, 'assign_bus_data'])->name("assign.bus.data");
    Route::get('/assign-route-list', [BusController::class, 'AssignBusRouteList']);
    Route::post('/assign-route-data', [BusController::class, 'AssignBusRouteListData'])->name("assign.route.data");

    //Assign Bus to Route and Driver Delete
    Route::post('/assign-bus-delete', [BusController::class, 'AssignBusDelete'])->name("assign.bus.delete");

    //Unassign bus list
    Route::get('/unassign-bus-list', [BusController::class, 'unassign_bus_list']);
    Route::post('/unassign-bus-data', [BusController::class, 'unassign_bus_data'])->name("uassign.bus.data");

    //Registered App Users
    //Unassign bus list
    Route::get('/registered-app-users', [UserController::class, 'registered_app_users']);
    Route::post('/registered-app-users-data', [UserController::class, 'app_users_data'])->name("app.users.data");
    Route::post('/app-user-delete', [UserController::class, 'app_users_delete'])->name("app.users.delete");

    //Edit user profile
    Route::get('edit-profile', [UserController::class, 'show']);
    Route::post('edit-data', [UserController::class, 'edit'])->name('edit-data');
});

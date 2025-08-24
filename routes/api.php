<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ApiPackageController;
use App\Http\Controllers\Api\V1\Auth\ApiAuthController;
use App\Http\Controllers\BackendControllers\UserController;
use App\Http\Controllers\BackendControllers\RouteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth api
Route::group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers\Api\V1\Auth'], function () {

    Route::post('user_registration', [ApiAuthController::class, 'registration']);
    Route::post('user_login', [ApiAuthController::class, 'login_for_user']);
    Route::post('LoginWithThirdPartyApi', [ApiAuthController::class, 'LoginWithThirdPartyApi']);
    Route::post('profileUpdate', [ApiAuthController::class, 'UserProfileUpdate']);
    Route::any('logout', [ApiAuthController::class, 'logout']);
    Route::post('refresh', [ApiAuthController::class, 'refresh']);
    Route::get('userinfo/{id?}', [ApiAuthController::class, 'userInfo']);
    Route::get('user-delete/{id}', [ApiAuthController::class, 'userDelete']);
});

//Afer Login Route List
Route::group(['middleware' => 'api'], function () {

    Route::post('/package-list', [ApiPackageController::class, 'getPacakgeList']);
    Route::post('/add-money', [ApiPackageController::class, 'add_money']);
    Route::get('/transation-history/{userId}', [ApiPackageController::class, 'transactionHistory']);

    Route::post('/order-request', [ApiPackageController::class, 'order_create']);
    Route::post('/order-list', [ApiPackageController::class, 'orderHistory']);

    Route::post('/refund-request', [ApiPackageController::class, 'refund_request']);
    Route::get('/refunds/{userId}', [ApiPackageController::class, 'refund_list']);

    Route::get('/special_offers/{userId}', [ApiPackageController::class, 'SpecialOfferList']);

    Route::get('/route-schedule-list', [RouteController::class, 'route_schedule_list']);
    Route::get('/route-wise-bus', [RouteController::class, 'route_wise_bus']);
    Route::get('/active-bus-list/{route_id}', [RouteController::class, 'active_bus_list']);

    //Driver Bus Info
    Route::get('/driver-bus-info', [UserController::class, 'driver_bus_info']);
    Route::post('/driver-bus-location-send', [UserController::class, 'driver_bus_location_post']);
    Route::post('/bus-location-get', [UserController::class, 'bus_location_get']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //return view('welcome');
    return redirect(route('login'));
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

//Admin
Route::group(['prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin','middleware' => ['auth', 'check.role.permission']], function () {
    Route::resources([
        'roles'           => RoleController::class,
        'users'           => 'UserController',
        'branch'          => 'BranchController',
        'restaurant_menu' => 'RestaurantMenuController',
        'reservation'     => 'ReservationController'
    ]);
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {  

    Route::get('/reservation', 'WebappController@reservation');
    Route::post('/reservation', 'WebappController@storeReservation')->name('reservation.store');
    
    Route::get('/information/{orderID}', 'WebappController@information');
    Route::post('/information', 'WebappController@storeInformation')->name('information.store');

    Route::get('/reservation-confirm/{orderID}', 'WebappController@reservationConfirm');

    Route::get('/menu/{orderID}', 'WebappController@menuList');
    Route::get('/menu/category/{id}', 'WebappController@getMenuItems');
    Route::get('/menu/{orderID}/detail/{id}', 'WebappController@getMenuDetail');
    Route::post('/menu/{orderID}/add-to-cart', 'WebappController@addToCart')->name('add-to-cart');;
    Route::get('/order-summary/{orderID}', 'WebappController@getOrderSummary');
    Route::post('/order-summary/{orderID}/confirm', 'WebappController@confirmOrder')->name('order.confirm');;

});



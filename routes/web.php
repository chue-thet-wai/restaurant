<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TableManagementController;
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
        //'reservation'     => 'ReservationController',
    ]);

    Route::get('/table-management', [TableManagementController::class, 'index'])->name('table-management.index');
    Route::post('/table-management/fetch-table-list', [TableManagementController::class, 'fetchTableList'])->withoutMiddleware(['auth', 'check.role.permission']);
    Route::post('/table-management/add-table', [TableManagementController::class, 'addTable'])->withoutMiddleware(['auth', 'check.role.permission']);
    Route::post('/table-management/toggle-availability', [TableManagementController::class, 'toggleAvailability'])->withoutMiddleware(['auth', 'check.role.permission']);

    Route::match(['get', 'post'], 'reservation', 'ReservationController@index')->name('admin_reservation.index');
    Route::resource('reservation', 'ReservationController', ['only' => ['create', 'store', 'edit', 'update', 'destroy'],
    'names' => [
        'create' => 'admin_reservation.create',
        'store' => 'admin_reservation.store',
        'edit' => 'admin_reservation.edit',
        'update' => 'admin_reservation.update',
        'destroy' => 'admin_reservation.destroy',
    ]]);

    Route::resource('order_management', 'OrderManagementController', ['only' => ['create', 'store', 'edit', 'update', 'destroy','show']]);
    Route::match(['get', 'post'], 'order_management', 'OrderManagementController@index')->name('order_management.index');

    Route::get('/setting/edit', 'SettingController@editProfile')->name('setting.edit');
    Route::post('/setting/update', 'SettingController@updateProfile')->name('setting.update');
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {  

    Route::get('/reservation', 'WebappController@reservation');
    Route::post('/reservation', 'WebappController@storeReservation')->name('webapp.reservation.store');

    Route::get('/reservation-status/{orderID}', 'WebappController@reservationStatus');
    
    Route::get('/information/{orderID}', 'WebappController@information');
    Route::post('/information', 'WebappController@storeInformation')->name('information.store');

    Route::get('/reservation-confirm/{orderID}', 'WebappController@reservationConfirm');
    Route::get('/reservation-confirm-download/{orderID}', 'WebappController@downloadReservationConfirm');

    Route::get('/menu/{orderID}', 'WebappController@menuList');
    Route::get('/menu/{orderID}/category/{id}', 'WebappController@getMenuItems');
    Route::get('/menu/{orderID}/detail/{id}', 'WebappController@getMenuDetail');
    Route::post('/menu/{orderID}/add-to-cart', 'WebappController@addToCart')->name('add-to-cart');;
    Route::get('/order-summary/{orderID}', 'WebappController@getOrderSummary');
    Route::post('/order-summary/{orderID}/confirm', 'WebappController@confirmOrder')->name('order.confirm');

    Route::get('/congratulation', 'WebappController@congratulation');

    Route::get('/get-available-times','HomeController@getAvailableTimes');
    Route::get('/get-available-tables','HomeController@getAvailableTablesList');

});



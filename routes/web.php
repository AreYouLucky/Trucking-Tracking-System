<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

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
    return view('auth.login');
});

//Auth
Route::post('/login',[App\Http\Controllers\Auth\LoginController::class,'login']);
Route::get('/logout',[App\Http\Controllers\Auth\LoginController::class,'logout']);


//ADDRESS
Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);



//Administrator
Route::middleware(['auth','role:ADMIN'])->group(function(){
    Route::get('/admin-dashboard', function () {
        return view('Admin.AdminDashboard');
    });

    Route::get('/customers-dashboard', function () {
        return view('Admin.customers');
    });
    Route::get('/drivers-dashboard', function () {
        return view('Admin.drivers');
    });
    Route::get('/vehicles-dashboard', function () {
        return view('Admin.vehicles');
    });
    Route::get('/pickups-dashboard', function () {
        return view('Admin.pickup');
    });
    Route::get('/deliveries-dashboard', function () {
        return view('Admin.deliveries');
    });
    Route::post('/add-customer',[App\Http\Controllers\CustomerController::class,'store']);
    Route::get('/show-customers',[App\Http\Controllers\CustomerController::class,'show']);
    Route::post('/delete-customer/{id}',[App\Http\Controllers\CustomerController::class,'destroy']);
    Route::get('/edit-customer/{id}',[App\Http\Controllers\CustomerController::class,'edit']);
    Route::post('/update-customer',[App\Http\Controllers\CustomerController::class,'update']);


    Route::post('/add-driver',[App\Http\Controllers\Admin\DriverController::class,'store']);
    Route::get('/show-drivers',[App\Http\Controllers\Admin\DriverController::class,'show']);
    Route::post('/delete-driver/{id}',[App\Http\Controllers\Admin\DriverController::class,'destroy']);
    Route::get('/edit-driver/{id}',[App\Http\Controllers\Admin\DriverController::class,'edit']);
    Route::post('/update-driver',[App\Http\Controllers\Admin\DriverController::class,'update']);

    Route::get('/show-vehicles',[App\Http\Controllers\vehicle\VehicleController::class,'show']);
    Route::post('/add-vehicle',[App\Http\Controllers\vehicle\VehicleController::class,'store']);
    Route::post('/delete-vehicle/{id}',[App\Http\Controllers\vehicle\VehicleController::class,'destroy']);
    Route::get('/edit-vehicle/{id}',[App\Http\Controllers\vehicle\VehicleController::class,'edit']);
    Route::post('/update-vehicle',[App\Http\Controllers\vehicle\VehicleController::class,'update']);

    Route::post('/add-delivery',[App\Http\Controllers\DeliveryController::class,'store']);
    Route::get('/load-deliveries',[App\Http\Controllers\DeliveryController::class,'loadDeliveries']);

    Route::get('/load-vehicles',[App\Http\Controllers\DeliveryController::class,'loadVehicles']);
    Route::get('/load-customers',[App\Http\Controllers\DeliveryController::class,'loadCustomers']);
    Route::get('/load-drivers',[App\Http\Controllers\DeliveryController::class,'loadDrivers']);

    Route::get('/view-delivery/{id}',[App\Http\Controllers\getLocation::class,'showID']);
    Route::get('/delivery-location/{id}',[App\Http\Controllers\getLocation::class,'getLocation']);
    Route::get('/driver-delivery-location/{id}',[App\Http\Controllers\getLocation::class,'getDriverLocation']);
    Route::get('/driver-location-data/{id}',[App\Http\Controllers\getLocation::class,'getDriverLocationData']);
    Route::get('/proof/{id}',[App\Http\Controllers\getLocation::class,'proof']);
    Route::get('/get-proof/{id}',[App\Http\Controllers\getLocation::class,'getProof']);

    Route::get('/images/{filename}', function ($filename) {
        $path = storage_path('app/public/proofs/' . $filename);
        
        if (!Storage::exists($path)) {
            abort(404);
        }
    
        $file = Storage::get($path);
        $type = Storage::mimeType($path);
    
        return (new Response($file, 200))->header('Content-Type', $type);
    });
    

});


//Customers
Route::middleware(['auth','role:CUSTOMER'])->group(function(){
    Route::get('/customer-dashboard', function () {
        return view('Customer.CustomerDashboard');
    });

});
//Driver
Route::middleware(['auth','role:DRIVER'])->group(function(){
    Route::get('/driver-dashboard', function () {
        return view('Driver.DriverDashboard');
    });
    Route::get('/load-driver-profile',[App\Http\Controllers\DriverDashboardController::class,'getDriver']);
    Route::get('/driver-delivery',[App\Http\Controllers\DriverDashboardController::class,'getLocation']);
    Route::post('/start-delivery/{id}',[App\Http\Controllers\DriverDashboardController::class,'startDelivery']);
    Route::post('/mid-delivery/{id}',[App\Http\Controllers\DriverDashboardController::class,'midDelivery']);
    Route::post('/delivery-proof',[App\Http\Controllers\DriverDashboardController::class,'storeProof']);
    Route::post('/driver-location',[App\Http\Controllers\DriverDashboardController::class,'updateDriverloc']);
    Route::post('/fin-delivery/{id}',[App\Http\Controllers\DriverDashboardController::class,'finDelivery']);




});


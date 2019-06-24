<?php

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
    return view('welcome');
});

/*
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');*/


Route::get('/clear', function() {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});

Route::group(['prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function() {


    Route::group(['namespace'=>'Auth','middleware'=>'guest'],function (){
        Route::get('/','AuthController@formLogin')->name('get.login');
        Route::get('/','AuthController@formLogin')->name('login');
        Route::post('/','AuthController@login')->name('post.login');
        Route::get('rest/password','AuthController@resetPassword')->name('get.reset.password');
        Route::post('rest/password','AuthController@postResetPassword')->name('post.reset.password');
        Route::get('rest/password/{token}','AuthController@reset')->name('get.reset');
        Route::post('rest/password/{token}','AuthController@postReset')->name('post.reset');

    });

    #route super admin and admin
    Route::namespace('Admin')->name('admin.')
        ->prefix('admin')
        ->middleware('admin')->group(function (){

            Config::set('auth.defines','admin');
            #home
            Route::get('/dashboard','HomeController@dashboard')->name('home');

            #admin
            Route::get('admin','AdminController@index')->name('admin.index');
            Route::get('admin/create','AdminController@create')->name('admin.create');
            Route::post('admin/store','AdminController@store')->name('admin.store');
            Route::get('admin/edit/{id}','AdminController@edit')->name('admin.edit');
            Route::post('admin/update','AdminController@update')->name('admin.update');
            Route::delete('admin/destroy/{id}','AdminController@destroy')->name('admin.destroy');
            Route::delete('admin/delete/all','AdminController@multiDelete')->name('admin.delete.all');


            #company
            Route::get('company','CompanyController@index')->name('company.index');
            Route::get('company/create','CompanyController@create')->name('company.create');
            Route::post('company/store','CompanyController@store')->name('company.store');
            Route::get('company/edit/{id}','CompanyController@edit')->name('company.edit');
            Route::post('company/update','CompanyController@update')->name('company.update');
            Route::get('company/status/{id}','CompanyController@destroy')->name('company.destroy');



            #logout
            Route::get('logout','HomeController@logout')->name('logout');

        });

    # route company
    Route::namespace('Company')->name('company.')
        ->prefix('vendor')
        ->middleware('company')->group(function (){
            Config::set('auth.defines','company');
            Route::get('/dashboard','HomeController@dashboard')->name('home');


            #user vendor
            Route::get('user-vendor','UserVendorController@index')->name('userVendor.index');
            Route::get('user-vendor/create','UserVendorController@create')->name('userVendor.create');
            Route::post('user-vendor/store','UserVendorController@store')->name('userVendor.store');
            Route::get('user-vendor/edit/{id}','UserVendorController@edit')->name('userVendor.edit');
            Route::post('user-vendor/update','UserVendorController@update')->name('userVendor.update');
            Route::get('user-vendor/status/{id}','UserVendorController@status')->name('userVendor.status');
            Route::get('user-vendor/destroy/{id}','UserVendorController@destroy')->name('userVendor.destroy');

            #supervisor
            Route::get('supervisor','SupervisorController@index')->name('supervisor.index');
            Route::get('supervisor/create','SupervisorController@create')->name('supervisor.create');
            Route::post('supervisor/store','SupervisorController@store')->name('supervisor.store');
            Route::get('supervisor/edit/{id}','SupervisorController@edit')->name('supervisor.edit');
            Route::post('supervisor/update','SupervisorController@update')->name('supervisor.update');
            Route::get('supervisor/status/{id}','SupervisorController@status')->name('supervisor.status');
            Route::get('supervisor/destroy/{id}','SupervisorController@destroy')->name('supervisor.destroy');

            #carrier
            Route::get('carrier','CarrierController@index')->name('carrier.index');
            Route::get('carrier/create','CarrierController@create')->name('carrier.create');
            Route::post('carrier/store','CarrierController@store')->name('carrier.store');
            Route::get('carrier/edit/{id}','CarrierController@edit')->name('carrier.edit');
            Route::post('carrier/update','CarrierController@update')->name('carrier.update');
            Route::get('carrier/status/{id}','CarrierController@status')->name('carrier.status');
            Route::get('carrier/destroy/{id}','CarrierController@destroy')->name('carrier.destroy');
            Route::get('carrier/carrierPath/{id}','CarrierController@carrierPath')->name('carrier.carrierPath');
            Route::get('carrier/set_price/{carrier_id}/{path_id}','CarrierController@set_price_view')->name('carrier.set_price_view');
            Route::post('carrier/price/update','CarrierController@price_update')->name('carrier.price.update');


            #driver
            Route::get('driver','DriverController@index')->name('driver.index');
            Route::get('driver/create','DriverController@create')->name('driver.create');
            Route::post('driver/store','DriverController@store')->name('driver.store');
            Route::get('driver/edit/{id}','DriverController@edit')->name('driver.edit');
            Route::post('driver/update','DriverController@update')->name('driver.update');
            Route::get('driver/status/{id}','DriverController@status')->name('driver.status');
            Route::get('driver/destroy/{id}','DriverController@destroy')->name('driver.destroy');


            #member
            Route::get('member','MemberController@index')->name('member.index');
            Route::get('member/create','MemberController@create')->name('member.create');
            Route::post('member/store','MemberController@store')->name('member.store');
            Route::get('member/edit/{id}','MemberController@edit')->name('member.edit');
            Route::post('member/update','MemberController@update')->name('member.update');
            Route::get('member/status/{id}','MemberController@status')->name('member.status');
            Route::get('member/destroy/{id}','MemberController@destroy')->name('member.destroy');


            #guide
            Route::get('guide','GuideController@index')->name('guide.index');
            Route::get('guide/create','GuideController@create')->name('guide.create');
            Route::post('guide/store','GuideController@store')->name('guide.store');
            Route::get('guide/edit/{id}','GuideController@edit')->name('guide.edit');
            Route::post('guide/update','GuideController@update')->name('guide.update');
            Route::get('guide/status/{id}','GuideController@status')->name('guide.status');
            Route::get('guide/destroy/{id}','GuideController@destroy')->name('guide.destroy');

            #bus
            Route::get('bus','BusController@index')->name('bus.index');
            Route::get('bus/create','BusController@create')->name('bus.create');
            Route::get('getcarrier/{id}','BusController@getcarrier');
            Route::post('bus/store','BusController@store')->name('bus.store');
            Route::get('bus/edit/{id}','BusController@edit')->name('bus.edit');
            Route::post('bus/update','BusController@update')->name('bus.update');
            Route::get('bus/status/{id}','BusController@status')->name('bus.status');
            Route::get('bus/destroy/{id}','BusController@destroy')->name('bus.destroy');


            #destination
            Route::get('destination','DestinationController@index')->name('destination.index');
            Route::get('destination/create','DestinationController@create')->name('destination.create');
            Route::post('destination/store','DestinationController@store')->name('destination.store');
            Route::get('destination/edit/{id}','DestinationController@edit')->name('destination.edit');
            Route::post('destination/update','DestinationController@update')->name('destination.update');
            Route::get('destination/status/{id}','DestinationController@status')->name('destination.status');
            Route::get('destination/destroy/{id}','DestinationController@destroy')->name('destination.destroy');


            #path
            Route::get('path','PathController@index')->name('path.index');
            Route::get('path/create','PathController@create')->name('path.create');
            Route::post('path/store','PathController@store')->name('path.store');
            Route::get('path/edit/{id}','PathController@edit')->name('path.edit');
            Route::post('path/update','PathController@update')->name('path.update');
            Route::get('path/status/{id}','PathController@status')->name('path.status');
            Route::get('path/destroy/{id}','PathController@destroy')->name('path.destroy');


            #trip
            Route::get('trip','TripController@index')->name('trip.index');
            Route::get('trip/create/{typeStatus?}','TripController@create')->name('trip.create'); //  Assigned   or Scheduled 
            Route::post('trip/store','TripController@store')->name('trip.store');
            Route::get('trip/edit/{id}','TripController@edit')->name('trip.edit');
            Route::post('trip/update','TripController@update')->name('trip.update');
            Route::get('trip/status/{id}','TripController@status')->name('trip.status');
            Route::get('trip/destroy/{id}','TripController@destroy')->name('trip.destroy');
            Route::get('trip/management','TripController@trip_management')->name('trip.management');
            Route::get('trip/edit_by_calendar/{id}','TripController@edit_by_calendar')->name('trip.edit_by_calendar');
            Route::post('trip/update_by_calendar','TripController@update_by_calendar')->name('trip.update_by_calendar');
            Route::delete('trip/destroy_by_calendar/{id}','TripController@destroy_by_calendar')->name('trip.destroy_by_calendar');

            #logout
            Route::get('logout','HomeController@logout')->name('logout');
        });


    # route user vendor
    Route::namespace('UserVendor')->name('user.vendor.')
        ->prefix('user-vendor')
        ->middleware('user_vendor')->group(function (){
            Config::set('auth.defines','user_vendor');
            Route::get('/dashboard','HomeController@dashboard')->name('home');


            #supervisor
            Route::get('supervisor','SupervisorController@index')->name('supervisor.index');
            Route::get('supervisor/create','SupervisorController@create')->name('supervisor.create');
            Route::post('supervisor/store','SupervisorController@store')->name('supervisor.store');
            Route::get('supervisor/edit/{id}','SupervisorController@edit')->name('supervisor.edit');
            Route::post('supervisor/update','SupervisorController@update')->name('supervisor.update');
            Route::get('supervisor/status/{id}','SupervisorController@status')->name('supervisor.status');
            Route::get('supervisor/destroy/{id}','SupervisorController@destroy')->name('supervisor.destroy');

            #carrier
            Route::get('carrier','CarrierController@index')->name('carrier.index');
            Route::get('carrier/create','CarrierController@create')->name('carrier.create');
            Route::post('carrier/store','CarrierController@store')->name('carrier.store');
            Route::get('carrier/edit/{id}','CarrierController@edit')->name('carrier.edit');
            Route::post('carrier/update','CarrierController@update')->name('carrier.update');
            Route::get('carrier/status/{id}','CarrierController@status')->name('carrier.status');
            Route::get('carrier/destroy/{id}','CarrierController@destroy')->name('carrier.destroy');

            #driver
            Route::get('driver','DriverController@index')->name('driver.index');
            Route::get('driver/create','DriverController@create')->name('driver.create');
            Route::post('driver/store','DriverController@store')->name('driver.store');
            Route::get('driver/edit/{id}','DriverController@edit')->name('driver.edit');
            Route::post('driver/update','DriverController@update')->name('driver.update');
            Route::get('driver/status/{id}','DriverController@status')->name('driver.status');
            Route::get('driver/destroy/{id}','DriverController@destroy')->name('driver.destroy');


            #member
            Route::get('member','MemberController@index')->name('member.index');
            Route::get('member/create','MemberController@create')->name('member.create');
            Route::post('member/store','MemberController@store')->name('member.store');
            Route::get('member/edit/{id}','MemberController@edit')->name('member.edit');
            Route::post('member/update','MemberController@update')->name('member.update');
            Route::get('member/status/{id}','MemberController@status')->name('member.status');
            Route::get('member/destroy/{id}','MemberController@destroy')->name('member.destroy');


            #guide
            Route::get('guide','GuideController@index')->name('guide.index');
            Route::get('guide/create','GuideController@create')->name('guide.create');
            Route::post('guide/store','GuideController@store')->name('guide.store');
            Route::get('guide/edit/{id}','GuideController@edit')->name('guide.edit');
            Route::post('guide/update','GuideController@update')->name('guide.update');
            Route::get('guide/status/{id}','GuideController@status')->name('guide.status');
            Route::get('guide/destroy/{id}','GuideController@destroy')->name('guide.destroy');

            #bus
            Route::get('bus','BusController@index')->name('bus.index');
            Route::get('bus/create','BusController@create')->name('bus.create');
            Route::post('bus/store','BusController@store')->name('bus.store');
            Route::get('bus/edit/{id}','BusController@edit')->name('bus.edit');
            Route::post('bus/update','BusController@update')->name('bus.update');
            Route::get('bus/status/{id}','BusController@status')->name('bus.status');
            Route::get('bus/destroy/{id}','BusController@destroy')->name('bus.destroy');

            #destination
            Route::get('destination','DestinationController@index')->name('destination.index');
            Route::get('destination/create','DestinationController@create')->name('destination.create');
            Route::post('destination/store','DestinationController@store')->name('destination.store');
            Route::get('destination/edit/{id}','DestinationController@edit')->name('destination.edit');
            Route::post('destination/update','DestinationController@update')->name('destination.update');
            Route::get('destination/status/{id}','DestinationController@status')->name('destination.status');
            Route::get('destination/destroy/{id}','DestinationController@destroy')->name('destination.destroy');


            #path
            Route::get('path','PathController@index')->name('path.index');
            Route::get('path/create','PathController@create')->name('path.create');
            Route::post('path/store','PathController@store')->name('path.store');
            Route::get('path/edit/{id}','PathController@edit')->name('path.edit');
            Route::post('path/update','PathController@update')->name('path.update');
            Route::get('path/status/{id}','PathController@status')->name('path.status');
            Route::get('path/destroy/{id}','PathController@destroy')->name('path.destroy');


            #trip
            Route::get('trip','TripController@index')->name('trip.index');
            Route::get('trip/create','TripController@create')->name('trip.create');
            Route::post('trip/store','TripController@store')->name('trip.store');
            Route::get('trip/edit/{id}','TripController@edit')->name('trip.edit');
            Route::post('trip/update','TripController@update')->name('trip.update');
            Route::get('trip/status/{id}','TripController@status')->name('trip.status');
            Route::get('trip/destroy/{id}','TripController@destroy')->name('trip.destroy');

            #logout
            Route::get('logout','HomeController@logout')->name('logout');
        });

    # route supervisor
    Route::namespace('Supervisor')->name('supervisor.')
        ->prefix('supervisor')
        ->middleware('supervisor')->group(function (){
           //Route::get('supervisor');
            Config::set('auth.defines','supervisor');
            Route::get('/dashboard','HomeController@dashboard')->name('home');

            #trip
            Route::get('trip','TripController@index')->name('trip.index');
            Route::get('trip/create','TripController@create')->name('trip.create');
            Route::post('trip/store','TripController@store')->name('trip.store');
            Route::get('trip/edit/{id}','TripController@edit')->name('trip.edit');
            Route::post('trip/update','TripController@update')->name('trip.update');
            Route::get('trip/status/{id}','TripController@status')->name('trip.status');
            Route::get('trip/destroy/{id}','TripController@destroy')->name('trip.destroy');

            Route::get('logout','HomeController@logout')->name('logout');
        });

});



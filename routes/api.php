<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test','ApiController@test');
Route::get('/notification_to_android/{tokens}/{msg}','ApiController@notification_to_android');
Route::post('/login','ApiController@login');
Route::post('/getFirebaseToken','ApiController@getFirebaseToken');
Route::post('/playerIds','ApiController@playerIds');

Route::post('/storeFirebase','ApiController@storeFirebase');

Route::post('/getGuide','ApiController@getGuide');
Route::post('/getBus','ApiController@getBus');
Route::post('/getDriver','ApiController@getDriver');
Route::post('/getPath','ApiController@getPath');

Route::post('/addTrip','ApiController@addTrip');
Route::post('/getTrip','ApiController@getTrip');

Route::post('/filterByStatus','ApiController@filterByStatus');
Route::post('/filterByDate','ApiController@filterByDate');
Route::post('/editTrip','ApiController@editTrip');
Route::post('/editTripStatus','ApiController@editTripStatus');

Route::post('/startTrip','ApiController@startTrip');
Route::post('/startTripGuide','ApiController@startTripGuide');
Route::post('/startTripDriver','ApiController@startTripDriver');
Route::post('/tripPause','ApiController@tripPause');
Route::post('/getTripsDriver','ApiController@getTripsDriver');
Route::post('/getTripsGuide','ApiController@getTripsGuide');
Route::post('/requestPauseTrip','ApiController@requestPauseTrip');
Route::post('/getRequestPauseTrip','ApiController@getRequestPauseTrip');
Route::post('/requestAnswer','ApiController@requestAnswer');
Route::post('/getNotifications','ApiController@getNotifications');
Route::post('/endTripSupervisor','ApiController@endTripSupervisor');
Route::post('/endTripDriver','ApiController@endTripDriver');
Route::post('/endTripGuide','ApiController@endTripGuide');
Route::post('/answerRequestMember','ApiController@answerRequestMember');
Route::post('/getRequestMember','ApiController@getRequestMember');
Route::post('/getRequestMemberByTripsID','ApiController@getRequestMemberByTripsID');
Route::post('/getRequestPauseTripGuide','ApiController@getRequestPauseTripGuide');
Route::post('/getSupervisors','ApiController@getSupervisors');
Route::post('/getTripsMemberSupervisor','ApiController@getTripsMemberSupervisor');




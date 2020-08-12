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
/*

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    return view('auth.login');
});

*/

// Auth::routes();

Route::get('/', function () {
    return redirect('/home/56');
});

//Route::get('/','MainController@indexconv');

Route::get('home/{convid}','MainController@indexconv');
Route::get('home/{convid}/goto/{selecteddate}', 'MainController@gotoconv');
Route::get('home/{convid}/feed/{selecteddate}', 'MainController@getfeed');
Route::get('home/{convid}/stella/{selecteddate}', 'MainController@getstelladata');
Route::get('home/{convid}','MainController@indexconv');
Route::get('home/{convid}/forecast','MainController@forecast');
Route::get('home/{convid}/weight','MainController@getweight');
Route::get('logout','MainController@melogout');

Route::get('alarms','AlarmController@alarms');
Route::get('alarms/getalarms', 'AlarmController@getalarms')->name('alarms.getalarms');
Route::get('alarms/getactunack', 'AlarmController@getactunack')->name('alarms.getactunack');
Route::post('alarms/ack/{id}', 'AlarmController@acknowledge');

Route::get('events','EventController@events');
Route::get('events/getevents', 'EventController@getevents')->name('events.getevents');

Route::get('trends','MainController@trends');
Route::get('trends/getsections/{conv}','MainController@trendsgetsections');
Route::get('trends/getaxis/{conv}/{section}','MainController@trendsgetaxes');
Route::get('trends/getsensor/{conv}/{id}','MainController@trendsgetsensor');

Route::get('settings','PlcsettingsController@index');
Route::get('settings/getsetting/{id}','PlcsettingsController@getselectedsetting');
Route::post('settings/setsetting/{id}','PlcsettingsController@storesetting');








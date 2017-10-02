<?php

use Illuminate\Http\Request;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\FriendsController;
use App\Http\Controllers\WiFiController;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::get('/user/friends', function (Request $request) {
    return FriendsController::getUsersFriends($request);
});

Route::post('/location_friends', function (Request $request) {
    return FriendsController::locationFriends($request);
});

Route::get('/user/wifi', function (Request $request) {
    return WiFiController::getWiFis($request);
});

Route::post('/user/location_wifi', function (Request $request) {
    return WiFiController::locationWifis($request);
});

Route::get('/test', function (Request $request) {
    return "Test123";
});

Route::post('/login', function (Request $request) {
    return UsersController::login($request);
});

Route::post('/register', function (Request $request) {
    return UsersController::register($request);
});

Route::post('/update', function (Request $request) {
    return UsersController::update($request);
});

Route::get('/user', function (Request $request) {
    return UsersController::getUser($request);
});

Route::get('/user/{id}', function (Request $request, $id) {
    return UsersController::getOtherUser($request, $id);
});

Route::post('/user/points/add', function (Request $request) {
    return UsersController::addPoints($request);
});

Route::post('/user/points/subtract', function (Request $request) {
    return UsersController::subtractPoints($request);
});

Route::post('/location', function (Request $request){
    return UsersController::postCurrentLocation($request);
});

Route::post('/user/friends', function (Request $request) {
    return FriendsController::addFriend($request);
});

Route::post('/user/friends/remove', function (Request $request) {
    return FriendsController::removeFriend($request);
});

Route::get('/wifi', function (Request $request) {
    return WiFiController::getAllWifis($request);
});

Route::post('/location_wifi', function (Request $request) {
    return WiFiController::locationAllWifis($request);
});

Route::post('/wifi', function (Request $request) {
    return WiFiController::postWiFi($request);
});

Route::get('/users', function(Request $request){
   return \App\User::all(); 
});

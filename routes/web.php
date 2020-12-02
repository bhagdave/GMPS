<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/profile', 'HomeController@profile')->name('profile')->middleware('auth');
Route::post('/profile', 'HomeController@saveProfile')->middleware('auth');

Route::get('/groups', 'GroupController@index')->name('groups')->middleware('auth');
Route::prefix('group')->middleware('auth')->group(function(){
    Route::get('/add', 'GroupController@add');
    Route::post('/add', 'GroupController@store');
    Route::get('/{uuid}', 'GroupController@view');
    Route::get('/invite/{uuid}', 'ParticipantController@invite');
});

Route::prefix('participant')->group(function(){
    Route::post('/invite/{uuid}', 'ParticipantController@sendInvite')->middleware('auth');
    Route::get('/invite/{uuid}/accept/{email}', 'ParticipantController@accept')->name('participant.accept');
    Route::post('/accept/invite', 'ParticipantController@registerFromAccept')->name('participant.accept.invite');
    Route::get('/remove/{uuid}/{userid}', 'ParticipantController@remove')->middleware('auth');
});

Route::prefix('organisation')->middleware('auth')->group(function(){
    Route::get('/{uuid}', 'OrganisationController@view');
    Route::get('/invite/{uuid}', 'OrganisationController@inviteUser');
});

Route::prefix('user')->group(function(){
    Route::delete('/delete', 'UserController@delete')->middleware('auth');
    Route::post('/invite/{uuid}', 'UserController@sendInvite')->middleware('auth');
    Route::get('/invite/{uuid}/accept/{email}', 'UserController@accept')->name('user.accept');
    Route::post('/accept/invite', 'UserController@registerFromAccept')->name('user.accept.invite');
});

Route::prefix('room')->middleware('auth')->group(function(){
    Route::get('/{uuid}', 'RoomController@index');
});

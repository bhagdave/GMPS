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
Route::get('/group/add', 'GroupController@add')->middleware('auth');
Route::post('/group/add', 'GroupController@store')->middleware('auth');
Route::get('/group/{uuid}', 'GroupController@view')->middleware('auth');
Route::post('/group/invite/{uuid}', 'GroupController@sendInvite')->middleware('auth');


Route::get('/group/invite/{uuid}', 'ParticipantController@invite')->middleware('auth');
Route::post('/participant/invite/{uuid}', 'ParticipantController@sendInvite')->middleware('auth');
Route::get('/participant/invite/{uuid}/accept/{email}', 'ParticipantController@accept')->name('participant.accept');
Route::post('/participant/accept/invite', 'ParticipantController@registerFromAccept')->name('participant.accept.invite');

Route::get('/organisation/{uuid}', 'OrganisationController@view')->middleware('auth');

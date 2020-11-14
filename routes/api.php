<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::apiResource('groups', 'Api\GroupController')->middleware('auth:api');
Route::apiResource('organisations', 'Api\OrganisationController')->middleware('auth:api');
Route::apiResource('users', 'Api\UserController')->middleware('auth:api');

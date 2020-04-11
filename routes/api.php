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


Route::namespace('Api')->group(function () {


    Route::group(['middleware' => 'guest'], function () {
        Route::post('login', 'AuthController@login')->name('login.api');
        Route::post('register', 'AuthController@register')->name('register.api');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::resource('users', 'UserController')->except('create');


        Route::resource('projects/{project}/tasks', 'ProjectTaskController')->except('create');
        Route::resource('projects', 'ProjectController')->except('create');

        Route::post('logout', 'AuthController@logout');
    });


});

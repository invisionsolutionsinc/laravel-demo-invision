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

Route::get('/', function () {
    return '<h1><center>SERVER IS UP AND API IS WORKING</center></h1>';
});

// User Routes
Route::group([
    'prefix' => 'user',
    ], function () {
        Route::get('/', function () {
            return '<h1><center>SERVER IS UP AND API IS WORKING FOR USERS</center></h1>';
        });
        Route::group([
            'namespace' => 'App\Http\Controllers\User',
            ], function () {
            //Without Auth
            Route::post('login', 'AuthController@login');
            Route::post('register', 'UserController@register');


            Route::group([
                'middleware' => ['auth:users'],
            ], function () {



                Route::post('logout', 'AuthController@logout');

                ############################ Report Controller Start ############################

                Route::get('user/get', 'DummyController@getAllUsers');   //Get All Users
                Route::post('user/edit', 'DummyController@editUser');   //Edit User
                Route::post('user/delete', 'DummyController@deleteUser');   //Delete User
                Route::get('order/get', 'DummyController@getAllOrders');   //Get All Orders
                Route::get('kyc/get', 'DummyController@getAllKyc');   //Get All KYC
                Route::post('kyc/update', 'DummyController@updateKyc');   //Update KYC
                Route::post('order/update', 'DummyController@updateCustomerOrder');   //Update order


                ############################ Report Controller End ############################

            });
        });

        //User Routes in Admin Namespace

        Route::group([
            'namespace' => 'App\Http\Controllers\Admin',
            ], function () {

            Route::group([
                'middleware' => ['auth:users'],
            ], function () {

                //Book
                Route::group([
                    'prefix' => 'dummy',
                ], function () {
                    Route::get('get', 'DummyController@get');
                    Route::post('create', 'DummyController@create');
                    Route::post('edit/{id}', 'DummyController@edit');
                    Route::get('delete/{id}', 'DummyController@destroy');
                });

                });

            });
        
});

// Admin Routes
Route::group([
    'prefix' => 'admin',
    ], function () {
        Route::get('/', function () {
            return '<h1><center>SERVER IS UP AND API IS WORKING FOR ADMIN</center></h1>';
        });
        Route::group([
            'namespace' => 'App\Http\Controllers\Admin',

        ], function () {
            //Without Auth
            Route::post('login', 'AuthController@login');


            Route::group([
                'middleware' => ['auth:admins'],
            ], function () {

                Route::post('register', 'UserController@register');
                Route::post('logout', 'AuthController@logout');

                

            });
        });
});

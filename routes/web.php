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

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'UserController@index')->name('users.index');
    Route::group(['prefix' => 'transfers', 'as' => 'transfers.'], function () {
        Route::get('/create', function () {
            return view('transfers.create');
        })->name('create');
        Route::post('/', 'TransferController@store')->name('store');
    });
});

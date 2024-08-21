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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');
Route::get('user', 'Api\AuthController@show');
Route::get('logincheck')->middleware('checkRole:admin');

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('logout', 'Api\AuthController@logout');

    Route::get('Profile', 'Api\ProfileController@index');
    Route::get('Profile/{id}', 'Api\ProfileController@show');
    Route::get('ProfileDetail/{id}', 'Api\ProfileController@showDetail');
    Route::post('Profile', 'Api\ProfileController@store');
    Route::put('Profile/{id}', 'Api\ProfileController@update');
    Route::delete('Profile/{id}', 'Api\ProfileController@destroy');


    Route::get('Nilai', 'Api\NilaiController@index');
    Route::get('Nilai/{id}', 'Api\NilaiController@show');
    Route::get('NilaiDetail/{id}', 'Api\NilaiController@showDetail');
    Route::post('Nilai', 'Api\NilaiController@store');
    Route::put('Nilai/{id}', 'Api\NilaiController@update');
    Route::delete('Nilai/{id}', 'Api\NilaiController@destroy');

    Route::get('Sertifikat', 'Api\SertifikatController@index');
    Route::get('Sertifikat/{id}', 'Api\SertifikatController@show');
    Route::get('SertifikatDetail/{id}', 'Api\SertifikatController@showDetail');
    Route::post('Sertifikat', 'Api\SertifikatController@store');
    Route::put('Sertifikat/{id}', 'Api\SertifikatController@update');
    Route::delete('Sertifikat/{id}', 'Api\SertifikatController@destroy');

    Route::get('Wawancara', 'Api\WawancaraController@index');
    Route::get('Wawancara/{id}', 'Api\WawancaraController@show');
    Route::get('WawancaraDetail/{id}', 'Api\WawancaraController@showDetail');
    Route::post('Wawancara', 'Api\WawancaraController@store');
    Route::put('Wawancara/{id}', 'Api\WawancaraController@update');
    Route::delete('Wawancara/{id}', 'Api\WawancaraController@destroy');

    Route::get('KelolaWawancara', 'Api\KelolaWawancaraController@index');
    Route::get('KelolaWawancara/{id}', 'Api\KelolaWawancaraController@show');
    Route::post('KelolaWawancara', 'Api\KelolaWawancaraController@store');
    Route::put('KelolaWawancara/{id}', 'Api\KelolaWawancaraController@update');
    Route::delete('KelolaWawancara/{id}', 'Api\KelolaWawancaraController@destroy');

    Route::get('KelolaHasil', 'Api\KelolaHasilController@index');
    Route::post('KelolaHasil', 'Api\KelolaHasilController@store');
    Route::put('KelolaHasil/{id}', 'Api\KelolaHasilController@update');
});

<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('signup/activate/{token}', 'AuthController@signupActivate');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::get('ciclos','Api\CicloController@index');
Route::get('users/{email}/available','Api\UserController@isEmailAvailable');


Route::group(['middleware' => 'auth:api'], function() {
    Route::get('menu', 'Api\MenuController@index');
    Route::get('ciclos/{id}','Api\CicloController@show');
    Route::apiResources(
        [   'alumnos'   => 'Api\AlumnoController',
        ],
        ['except' => ['destroy','store']]);
    Route::apiResources(
        [   'users' => 'Api\UserController']);
    Route::apiResource('ofertas','Api\OfertaController',['except'=>['update','store']]);

    Route::put('ofertas/{id}/alumno', 'Api\OfertaController@AlumnoInterested');
    // Modificada
    Route::put('alumnos/{alumno}/ciclo/{id}','Api\AlumnoController@ValidaCiclo')->name('alumnos.ciclo.update');
    Route::get('ofertas-arxiu', 'Api\OfertaController@indexArxiu');
});

Route::group(['middleware' => ['auth:api','role:administrador,responsable,empresa']], function() {
    Route::apiResources(
        [   'empresas'  => 'Api\EmpresaController',
        ],
        ['except' => ['store']]);
    Route::post('ofertas','Api\OfertaController@store')->name('ofertas.store');
    Route::put('ofertas/{id}','Api\OfertaController@update')->name('ofertas.update');
});

Route::group(['middleware' => ['auth:api','role:administrador,responsable,alumno']], function() {
    Route::delete('alumnos/{alumno}','Api\AlumnoController@destroy');
});

Route::group(['middleware' => ['auth:api','role:administrador']], function() {
    Route::apiResource('responsables','Api\ResponsableController',['except'=>['update']]);
    Route::apiResources(
        [   'ciclos'    =>'Api\CicloController'
        ],
        ['except' => ['destroy','index','show']]);
    Route::put('menu/{id}', 'Api\MenuController@update');

});

Route::group(['middleware' => ['auth:api','role:administrador,responsable']], function() {
    Route::put('ofertas/{id}/validar','Api\OfertaController@Valida');
    Route::put('responsables/{id}','Api\ResponsableController@update');
});

Route::group([
    'namespace' => 'Auth',
    'middleware' => 'api',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

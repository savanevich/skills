<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['prefix' => 'api/v1/'], function () {
    Route::get('/', function () {
        return ['Api' => 'v1'];
    });

    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){
        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@login');

        Route::group(['middleware' => 'jwt.auth'], function(){
            Route::get('/user', 'AuthController@getAuthenticatedUser');
            Route::get('/skills', 'AuthController@getSkillsOfAuthenticatedUser');
        });
    });

    Route::group(['prefix' => 'users'], function(){
        Route::get('/', 'UserController@index');
        Route::get('/{id}', 'UserController@show');
        Route::post('/', 'UserController@store');
        Route::delete('/{id}', 'UserController@destroy');
        Route::get('/{userId}/skills', 'UserController@getSkills');
        Route::get('/{userId}/img', 'UserController@getAvatarLink');
        Route::group(['middleware' => 'jwt.auth'], function() {
            Route::put('/{id}', 'UserController@update');
            Route::post('/{userId}/add-img', 'UserController@saveAvatar');
            Route::post('/{userId}/skills', 'UserController@addSkill');
            Route::put('/{userId}/skills/{technologyId}', 'UserController@updateSkill');
            Route::delete('/{userId}/skills/{technologyId}', 'UserController@deleteSkill');
            Route::post('/{userId}/follower', 'UserController@followUser');
            Route::delete('/{userId}/follower', 'UserController@unFollowUser');
        });
    });


    Route::group(['prefix' => 'technologies'], function(){
	   	Route::get('/', 'TechnologyController@index');
	   	Route::get('/{id}', 'TechnologyController@show');
	    Route::post('/', 'TechnologyController@store');
	    Route::put('/{id}', 'TechnologyController@update');
	    Route::delete('/{id}', 'TechnologyController@destroy');
        Route::get('/{id}/users', 'TechnologyController@getUsers');
    });

    Route::group(['prefix' => 'categories'], function(){
        Route::get('/', 'CategoryController@index');
        Route::get('/{id}', 'CategoryController@show');
        Route::post('/', 'CategoryController@store');
        Route::put('/{id}', 'CategoryController@update');
        Route::delete('/{id}', 'CategoryController@destroy');
        Route::get('/{id}/technologies', 'CategoryController@getTechnologies');
        Route::get('/{id}/users', 'CategoryController@getUsers');
    });

    Route::get('/search', 'SearchController@searchSkills');
});


Route::get('/', function () {
    return ['Hello' => 'world'];
});



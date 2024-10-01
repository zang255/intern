<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->group(['prefix' => 'api'], function () use ($router) {
//     $router->get('books', 'BookController@index');
//     $router->get('books/{id}', 'BookController@show');
//     $router->post('books', 'BookController@store');
//     $router->put('books/{id}', 'BookController@update');
//     $router->delete('books/{id}', 'BookController@destroy');
// });

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('login', 'AuthController@login');

    $router->group(['middleware' => ['auth','checkAdmin']], function () use ($router) {
        $router->get('books', 'BookController@index');
        $router->get('books/{id}', 'BookController@show');
        $router->post('books', [
            'middleware' => 'checkValidate',
            'uses' => 'BookController@store'
        ]);
        $router->put('books/{id}', 'BookController@update');
        $router->delete('books/{id}', 'BookController@destroy');
        $router->get('get-trash', 'BookController@getDeletedBooks');
        $router->post('logout', 'AuthController@logout');
        $router->post('me', 'AuthController@me');
    });
});

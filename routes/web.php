<?php

use GuzzleHttp\Client;
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



$router->group([
    'prefix' => 'api'
], function($app)
{
    $app->post('register', 'UserController@create');
        
    $app->post('login', 'UserController@login');
    
    $app->group(['prefix' => 'posts'], function($app)
    {
        $app->group(['middleware' => 'jwt.auth'], function($app)
        {

            $app->get('list', 'PostController@index'); 
            
            $app->get('view/{id}', 'PostController@single');
            
        });
            $app->post('create', 'PostController@create');
        
            $app->put('edit/{id}', 'PostController@update');
        
            $app->delete('delete/{id}','PostController@delete');
        // test

    });
        

    $app->group(['prefix' => 'users'], function($app)
    {
        

        $app->group(['middleware' => 'jwt.auth'], function($app)
        {
            $app->get('list', 'UserController@index');
        
            $app->get('profile', 'UserController@profile');
             
            $app->put('edit', 'UserController@update');

            $app->delete('delete', 'UserController@delete');
        });

    });

    $app->get('logout', 'UserController@logout');

});
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
    
    $app->group(['prefix' => 'posts'], function($app)
    {
        $app->group(['middleware' => 'auth'], function($app)
        {
            $app->get('list', 'PostController@index'); 
            
            $app->get('view/{id}', 'PostController@single');
            
        });

        $app->group(['middleware' => ['auth','is_admin']], function($app)
        {
            $app->post('add', 'PostController@create');
        
            $app->put('edit/{id}', 'PostController@update');
        
            $app->delete('delete/{id}','PostController@delete');
        });
        
            
    });
        

    $app->group(['prefix' => 'users'], function($app)
    {
        $app->group(['middleware' => 'auth'], function($app)
        {
            $app->get('list', 'UserController@index');
        
            $app->get('view/{id}', 'UserController@single');
             
            $app->put('edit/{id}', 'UserController@update');
        });

        $app->post('add', 'UserController@create');

        $app->delete('delete/{id}','UserController@delete');
    });

   
});
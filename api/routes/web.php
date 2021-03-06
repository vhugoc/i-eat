<?php

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

$router->group(['prefix' => 'api'], function () use ($router) {

    /**
     * User Routing
     * 
     */
    $router->post('/register', 'UserController@register');
    $router->post('/signin', 'UserController@signin');
    $router->post('/signout', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'UserController@signout']);
    $router->get('/profile', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'UserController@show']);

    $router->group(['prefix' => 'user'], function () use ($router) {
        
    });


    /**
     * Posts/Departments Routing
     * 
     */
    $router->group(['prefix' => 'post'], function () use ($router) {
        $router->get('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'PostController@index']);
        $router->get('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'PostController@show']);
        $router->post('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'PostController@add']);
        $router->post('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'PostController@update']);
        $router->delete('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'PostController@delete']);
    });

    /**
     * Employees Routing
     * 
     */
    $router->group(['prefix' => 'employee'], function () use ($router) {
        $router->get('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'EmployeeController@index']);
        $router->get('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'EmployeeController@show']);
        $router->post('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'EmployeeController@add']);
        $router->post('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'EmployeeController@update']);
        $router->delete('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'EmployeeController@delete']);
    });

    /**
     * Clients Routing
     * 
     */
    $router->group(['prefix' => 'client'], function () use ($router) {
        $router->get('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ClientController@index']);
        $router->get('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ClientController@show']);
        $router->post('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ClientController@add']);
        $router->post('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ClientController@update']);
        $router->delete('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ClientController@delete']);
    });

    /**
     * Product Categories Routing
     * 
     */
    $router->group(['prefix' => 'category'], function () use ($router) {
        $router->get('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'CategoryController@index']);
        $router->get('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'CategoryController@show']);
        $router->post('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'CategoryController@add']);
        $router->post('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'CategoryController@update']);
        $router->delete('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'CategoryController@delete']);
    });

    /**
     * Products Routing
     * 
     */
    $router->group(['prefix' => 'product'], function () use ($router) {
        $router->get('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ProductController@index']);
        $router->get('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ProductController@show']);
        $router->post('/', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ProductController@add']);
        $router->post('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ProductController@update']);
        $router->delete('/{id}', ['middleware' => App\Http\Middleware\AuthorizationMiddleware::class, 'uses' => 'ProductController@delete']);
    });
});

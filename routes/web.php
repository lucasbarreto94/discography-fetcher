<?php
use Illuminate\Http\Request;
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

$config = include('../config.php');
$baseRoute = $config['baseRouteApi'];

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get($baseRoute.'/albums', 'DiscographyController@getAlbums');

$router->get('/login', function(){
    return view('login');
});

$router->get('/authentication', ['as'=>'authentication', 'uses'=>'AuthenticationController@authenticate']);

$router->get('/callback',  ['as'=>'callback', 'uses'=>'AuthenticationController@callback']);
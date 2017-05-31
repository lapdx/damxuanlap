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

$app->get('/',['as' => 'frontend::home::index', 'uses' => 'HomeController@index']);
$app->post('/api/sheet',['as' => 'service::api::sheet', 'uses' => 'Services\GoogleSheetService@pushToSheet']);

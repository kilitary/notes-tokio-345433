<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Request as REQ;


// parse version
$version = substr(preg_replace("/[^0-9]/","",REQ::header('x-app-version', '1')),0,1);

$namespace = "\\App\\Http\\Controllers\\Ux\\v{$version}";

if(!is_numeric($version) || !class_exists($namespace."\UxController")) {
    $namespace="\\App\\Http\\Controllers\\Ux\\v1";
}

Route::group([
    'namespace' => $namespace,
    'middleware' => "ux",
], function ($router) {

    // Notes
    Route::get('/notes', 'NotesController@list');
    Route::get('/notes/{id}', 'NotesController@getone');
    Route::post('/notes', 'NotesController@create');
    Route::delete('/notes/{id}', 'NotesController@delete');
    Route::put('/notes/{id}', 'NotesController@update');

});

<?php

use Illuminate\Support\Facades\Route;
use App\Events\TaskAdded;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/tasks', function () {
    $task = ['id' => 1, 'name' => 'メールの確認']; 
    event(new TaskAdded($task));
});

Route::get('person', 'App\Http\Controllers\PersonController@index')->name('person');

Route::get('person/work', 'App\Http\Controllers\PersonController@job');
<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() {
    return redirect()->route('tasks.index');
})->name('index');

Route::get('/home', 'TasksController@index')->name('home'); // delete
Route::get('/tasks/search', 'TasksController@search')->name('tasks.search');
Route::get('/tasks/filter', 'TasksController@filter')->name('tasks.filter');
Route::resource('/tasks', 'TasksController');

Route::post('/tasks/forward', 'TasksController@forward')->name('tasks.forward');
Route::post('/tasks/change_status/{task}', 'TasksController@changestatus')->name('tasks.changestatus');


Route::resource('/projects', 'ProjectsController');
Route::get('/project_filter', 'ProjectsController@filter')->name('projects.filter');
Route::get('/projects_search', 'ProjectsController@search')->name('projects.search');

Route::get('/users_search', 'UsersController@search')->name('users.search');

Route::resource('/comments', 'CommentsController')->only(['store', 'update', 'destroy']); // make only used methods (EXCEPTIONS)

Auth::routes();
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

Route::get('/', 'TasksController@index')->name('index');
Route::get('/home', 'TasksController@index')->name('home');

Route::resource('/tasks', 'TasksController');
Route::get('/tasks_filter', 'TasksController@filter')->name('tasks.filter');
Route::get('/tasks_forward', 'TasksController@forward')->name('tasks.forward');
Route::get('/tasks_change_status', 'TasksController@changestatus')->name('tasks.changestatus');
Route::get('/tasks_search', 'TasksController@search')->name('tasks.search');

Route::resource('/projects', 'ProjectsController');
Route::get('/project_filter', 'ProjectsController@filter')->name('projects.filter');
Route::get('/projects_search', 'ProjectsController@search')->name('projects.search');

Route::get('/users_search', 'UsersController@search')->name('users.search');

Route::resource('/comments', 'CommentsController');

Auth::routes();
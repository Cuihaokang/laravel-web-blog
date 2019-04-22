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

Route::get('/', function () {
    return view('welcome');
});
//RESTful资源控制器和路由
// Route::resource('articles', 'ArticlesController');

//分开来写路由
Route::get('/articles', 'ArticlesController@index')->name('articles.index');
// Route::get('/articles/{id}', 'ArticlesController@show')->name('articles.show');
Route::get('create', 'ArticlesController@create')->name('articles.create');
Route::post('/articles', 'ArticlesController@store')->name('articles.store');
Route::get('/articles/{id}/edit', 'ArticlesController@edit')->name('articles.edit');
Route::patch('/articles/{id}', 'ArticlesController@update')->name('articles.update');
Route::delete('/articles/{id}', 'ArticlesController@destroy')->name('articles.destroy');

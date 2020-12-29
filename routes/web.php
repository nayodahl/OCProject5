<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', 'PostController@showHomePage')->name('app_homepage');
Route::get('post/{id}', 'PostController@showSinglePost')->name('app_post_show');
Route::post('post/{id}', 'CommentController@store')->name('app_comment_add');
Route::get('posts', 'PostController@showAllPosts')->name('app_posts_show');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

////// BACKOFFICE ///////////////
Route::get('/admin/posts', 'AdminController@showAllPosts')->name('app_admin_posts_show');
Route::get('/admin/comments', 'AdminController@showAllComments')->name('app_admin_comments_show');
Route::get('/admin/users', 'AdminController@showAllUsers')->name('app_admin_users_show');
Route::get('/admin/create', 'AdminController@createPost')->name('app_admin_post_create');
Route::post('/admin/create', 'PostController@store');
Route::get('/admin/post/{id}', 'AdminController@updatePost')->name('app_admin_post_update');
Route::post('/admin/update/{id}', 'PostController@update');
Route::get('/admin/delete/{id}', 'PostController@deletePost')->name('app_admin_post_delete');
Route::get('/admin/approve/{id}', 'CommentController@approveComment')->name('app_admin_comment_approve');
Route::get('/admin/refuse/{id}', 'CommentController@destroy')->name('app_admin_comment_refuse');
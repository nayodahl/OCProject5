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
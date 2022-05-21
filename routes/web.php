<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'manager'], function () {
  Route::get('/login', 'ManagerAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'ManagerAuth\LoginController@login');
  Route::post('/logout', 'ManagerAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'ManagerAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'ManagerAuth\RegisterController@register');

  Route::post('/password/email', 'ManagerAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'ManagerAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'ManagerAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'ManagerAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'school'], function () {
  Route::get('/login', 'SchoolAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'SchoolAuth\LoginController@login');
  Route::post('/logout', 'SchoolAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'SchoolAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'SchoolAuth\RegisterController@register');

  Route::post('/password/email', 'SchoolAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'SchoolAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'SchoolAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'SchoolAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'teacher'], function () {
  Route::get('/login', 'TeacherAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'TeacherAuth\LoginController@login');
  Route::post('/logout', 'TeacherAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'TeacherAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'TeacherAuth\RegisterController@register');

  Route::post('/password/email', 'TeacherAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'TeacherAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'TeacherAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'TeacherAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'supervisor'], function () {
  Route::get('/login', 'SupervisorAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'SupervisorAuth\LoginController@login');
  Route::post('/logout', 'SupervisorAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'SupervisorAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'SupervisorAuth\RegisterController@register');

  Route::post('/password/email', 'SupervisorAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'SupervisorAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'SupervisorAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'SupervisorAuth\ResetPasswordController@showResetForm');
});

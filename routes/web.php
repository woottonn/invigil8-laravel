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

use Illuminate\Support\Facades\Auth;

//Other
Route::get('/', 'PagesController@index');
Route::get('/home', 'PagesController@index')->name('home');
Route::get('/logout-force', 'PagesController@logoutforce')->name('logout-force');
Route::get('/cookie-policy', 'PagesController@cookies')->name('cookies');
Route::get('/exams/today', 'ExamsController@today')->name('exams.today');

//Dashboards
Route::get('admin/dashboard', 'DashboardController@admin_index')->name('admin.dashboard')->middleware('role:Super Admin|Centre Admin');
Route::get('super-admin/dashboard', 'DashboardController@superadmin_index')->name('superadmin.dashboard')->middleware('role:Super Admin');
Route::get('centre-admin/dashboard', 'DashboardController@centreadmin_index')->name('centreadmin.dashboard')->middleware('role:Super Admin|Centre Admin');
Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware('role:Super Admin|Invigilator|Centre Admin');

//Site-wide session changes
Route::get('/centre-change', 'CentresController@change')->name('centre-change')->middleware('role:Super Admin');
Route::get('/season-change', 'SeasonsController@change')->name('season-change')->middleware('auth');
Route::get('/season-centre-change', 'PagesController@change')->name('season-centre-change')->middleware('auth');

//Super Admin
Route::resource('roles', 'RolesController')->middleware('role:Super Admin');
Route::resource('permissions', 'PermissionsController')->middleware('role:Super Admin');
Route::resource('centres', 'CentresController')->middleware('permission:CENTRES-modify-centres');

//Admin
Route::resource('users', 'UsersController')->middleware('permission:USERS-view');
Route::resource('locations', 'LocationsController')->middleware('permission:LOCATIONS-modify');
Route::resource('exams', 'ExamsController')->middleware('permission:EXAMS-view');
Route::resource('participations', 'ParticipationsController')->middleware('permission:EXAMS-signup|EXAMS-assign');

//Exams
Route::get('/exams/create/bulk', 'ExamsController@bulk')->name('exams.bulk')->middleware('role:Super Admin|Centre Admin');
Route::post('/exams/create/bulk/store', 'ExamsController@storebulk')->name('exams.storebulk')->middleware('role:Super Admin|Centre Admin');

//Mail
Route::get('mail/exam-updated/view', 'MailController@update');
Route::get('mail/exam-added', 'MailController@exam_add');

//Auth
Auth::routes();



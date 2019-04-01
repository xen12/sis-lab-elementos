<?php

use Illuminate\Support\Facades\Auth;
use App\User;
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
    return view('layouts.home');
});
Route::get('admin', function () {
    return view('layouts.logged');
});
Route::get('error', function () {
    return view('errors.404');
});
Route::get('register', function () {
    return view('layouts.register');
});
Route::get('login', function () {
    return view('layouts.login');
});


//roles
Route::get('admin', function () {
    return view('components.sections.adminSection');
});
Route::get('student', function () {
    return view('components.contents.student.studentContent');
});
Route::get('professor', ['uses'=> 'ProfessorController@index']);
Route::get('/admin/professors/create', ['uses'=> 'ProfessorController@create']);
Route::post('/admin/professors/create', ['uses'=> 'ProfessorController@store']);


Route::get('auxiliar', function () {
    return view('components.sections.auxiliarSection');
});


Auth::routes();


Route::get('/admin/subjectmatters','SubjectMatterController@index');
Route::get('/admin/subjectmatter/{id}','SubjectMatterController@show');
Route::get('/admin/subjectmatter/create','SubjectMatterController@create');
Route::post('/admin/subjectmatter/create','SubjectMatterController@store')->name('subjectmatters.create');
Route::get('/admin/subjectmatter/{id}/edit','SubjectMatterController@edit');
Route::post('/admin/subjectmatter/{id}/edit','SubjectMatterController@update')->name('subjectmatters.edit');
Route::delete('/admin/subjectmatter/{id}','SubjectMatterController@destroy')->name('subjectmatters.destroy');

Route::get('/admin/gestiones','ManagementController@index');

Route::get('/home', 'HomeController@index');

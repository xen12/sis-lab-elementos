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

Route::get('/', function () {
    return view('layouts.home');
});
Route::get('error', function () {
    return view('errors.404');
});
Route::get('register', function () {
    return view('layouts.register');
});

//roles
Route::get('admin', function () {
    return view('components.sections.adminSection');
});
Route::get('student', function () {
    return view('components.contents.student.studentContent');
});
Route::get('professor', function () {
    return view('components.sections.professorSection');
});
Route::get('/admin/professors/create', ['uses'=> 'ProfessorController@create']);
Route::post('/admin/professors/create', ['uses'=> 'ProfessorController@store']);

//Estudiante
Route::get('student', ['uses' => 'StudentController@index']);
Route::get('/admin/student/create', ['uses' => 'StudentController@create']);
Route::post('/admin/student/create', ['uses' => 'StudentController@store']);

Route::get('student/create', 'StudentController@create');
Route::post('student/register', 'StudentController@store')->name('student.register');
Route::get('student/{id}', 'StudentController@show');
Route::get('student/{id}/edit', 'StudentController@edit');
Route::post('student/{id}/edit', 'StudentController@update')->name('student.edit');
Route::delete('student/{id}', 'StudentController@destroy')->name('student.destroy');

Route::get('auxiliar', function () {
    return view('components.sections.auxiliarSection');
});

//child roles
Route::get('admin/lista', function () {
    return view('components.contents.admin.adminContent');
});


Auth::routes();

//registro de materias
Route::get('/admin/subjectmatters','SubjectMatterController@index');

Route::get('/admin/subjectmatter/create','SubjectMatterController@create');
Route::post('/admin/subjectmatter/create','SubjectMatterController@store')->name('subjectmatters.store');

Route::get('/admin/subjectmatter/{id}/edit','SubjectMatterController@edit');
Route::post('/admin/subjectmatter/{id}/edit','SubjectMatterController@update')->name('subjectmatters.update');
Route::delete('/admin/subjectmatter/{id}','SubjectMatterController@destroy')->name('subjectmatters.destroy');

//Gestiones
Route::get('/admin/gestiones','ManagementController@index');
 //Grupos
Route::resource('/admin/groups', 'GroupController');


Route::get('/home', 'HomeController@index');

//registro de auxiliares
Route::get('/admin/auxiliars','AuxiliarController@index');
Route::get('/admin/auxiliars/create', 'AuxiliarController@create');
Route::post('/admin/auxiliars/store','AuxiliarController@store');
Route::delete('/admin/auxiliars/{id}','AuxiliarController@destroy')->name('auxiliar.destroy');
Route::get('/admin/auxiliars/{id}/edit','AuxiliarController@edit');
Route::post('/admin/auxiliars/{id}/update','AuxiliarController@update')->name('auxiliar.update');
Route::get('/admin/groups/getCount/{id}', 'GroupController@getCountSubjects');
Route::get('/admin/groups/getProfessors/{id}', 'GroupController@getProfessors');

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
use \App\User;
use Illuminate\Support\Facades\Artisan;
Route::get('/', 'Auth\LoginController@showLoginForm');

Route::get('i', function () {
    Artisan::call('cache:clear');
    return redirect()->back();
});

Auth::routes();
Route::match(['get', 'post'], 'register', function(){
    return redirect('/');
});
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/home', 'HomeController@index')->name('home')->middleware('serial');
Route::get('/serial','SerialController@index')->name('serial');
Route::post('/serial-save','SerialController@store')->name('serial.save');
Route::get('locale/{locale}',function ($locale){
    Session::put('locale',$locale);
    \App::setlocale('fa');
    return redirect()->back();
})->name('change_lang');

Route::get('{path}','HomeController@index')->where( 'path', '([A-z\d\-\/_.]+)?' );
// Route::get('{path}','HomeController@index')->where( 'path', '([A-z\d\-\/_.]+)?' )->middleware('serial');
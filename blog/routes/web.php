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
    return view('form');// ajax로 화면에서 입력받은 url을 검사하도록 
});



Route::get('/test/{test}', 'TestController@test');
Route::get('/test/', 'test.php@test');
// Route::get('/test/{test}', function ($test) {
//     return $test;
// });
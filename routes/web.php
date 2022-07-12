<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook;

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


Route::get('/webhook', function () {
    return view('home');
});

Route::get('/', [Webhook::class, 'Login']);
Route::post('/', [Webhook::class, 'PostLogin']);


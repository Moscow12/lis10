<?php

use App\Http\Controllers\HL7Controller;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::post('/api/hl7/receive', [HL7Controller::class, 'receive']);

Route::get('/', function () {
    return view('welcome');
});

<?php

use App\Http\Controllers\HL7Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/api/hl7/receive', [HL7Controller::class, 'receive']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

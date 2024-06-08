<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RobotController;
use App\Http\Controllers\SearchThingController;
use App\Http\Controllers\ThingController;
use App\Models\User;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout',
        [AuthController::class, 'logout']
    );
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile'
]);
});

Route::prefix('robots')->controller(RobotController::class)->group(function (){
    Route::get('/show','show');
    Route::post('/update', 'update');
});

Route::prefix('things')->controller(ThingController::class)->group(function (){
    Route::get('/all','index');
    Route::post('/create', 'store');
    Route::post('/delete', 'destroy');
});


Route::prefix('notifications')->controller(NotificationController::class)->group(function (){
    Route::get('/', 'getAll');
    Route::post('/send', 'send');
});

Route::get('/users', function () {
    return response()->json(User::all());
});

<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\web\GroupMenuController;
use App\Http\Controllers\web\OptionMenuController;
use App\Http\Controllers\web\TypeUserController;
use App\Http\Controllers\web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/login', function () {
    return view('auth.login');
});
Route::get('index.html', function () {
    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth']], function () {

    Route::get('logout', [AuthController::class, 'logout']);

    Route::resource('vistaInicio', 'App\Http\Controllers\InicioController');
    Route::get('vistaInicio', [InicioController::class, 'index'])->name('vistaInicio');

    //USER
    Route::get('user', [UserController::class, 'index']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::post('user', [UserController::class, 'store']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    //GROUP MENU
    Route::get('groupmenu', [GroupMenuController::class, 'index']);
    Route::get('groupmenu/{id}', [GroupMenuController::class, 'show']);
    Route::post('groupmenu', [GroupMenuController::class, 'store']);
    Route::put('groupmenu/{id}', [GroupMenuController::class, 'update']);
    Route::delete('groupmenu/{id}', [GroupMenuController::class, 'destroy']);

    //GROUP MENU
    Route::get('options', [OptionMenuController::class, 'index']);
    Route::get('options/{id}', [OptionMenuController::class, 'show']);
    Route::post('options', [OptionMenuController::class, 'store']);
    Route::put('options/{id}', [OptionMenuController::class, 'update']);
    Route::delete('options/{id}', [OptionMenuController::class, 'destroy']);

    //TYPE USER
    Route::get('access', [TypeUserController::class, 'index']);
    Route::get('accessAll', [TypeUserController::class, 'all']);
    Route::get('access/{id}', [TypeUserController::class, 'show']);
    Route::post('access', [TypeUserController::class, 'store']);
    Route::put('access/{id}', [TypeUserController::class, 'update']);
    Route::delete('access/{id}', [TypeUserController::class, 'destroy']);
    Route::post('access/setAccess', [TypeUserController::class, 'setAccess']);

});

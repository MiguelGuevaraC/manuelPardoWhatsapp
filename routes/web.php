<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\web\CompromisoController;
use App\Http\Controllers\web\DashboardController;
use App\Http\Controllers\web\GroupMenuController;
use App\Http\Controllers\web\MessageController;
use App\Http\Controllers\web\MigrationController;
use App\Http\Controllers\web\OptionMenuController;
use App\Http\Controllers\web\StudentController;
use App\Http\Controllers\web\TypeUserController;
use App\Http\Controllers\web\UserController;
use App\Http\Controllers\web\WhatsappSendController;
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

Route::middleware(['ensureTokenIsValid'])->group(function () {
    return view('auth.login');
});

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth']], function () {

    Route::get('logout', [AuthController::class, 'logout']);

    Route::resource('vistaInicio', 'App\Http\Controllers\InicioController');
    Route::get('vistaInicio', [InicioController::class, 'index'])->name('vistaInicio');

    //USER
    Route::get('user', [UserController::class, 'index']);
    Route::get('userAll', [UserController::class, 'all']);
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

    //MIGRATION
    Route::get('migracion', [MigrationController::class, 'index']);
    Route::get('migracionAll', [MigrationController::class, 'all']);

    Route::get('migracion/{id}', [MigrationController::class, 'show']);
    Route::post('migracion', [MigrationController::class, 'store']);
    Route::put('migracion/{id}', [MigrationController::class, 'update']);
    Route::delete('migracion/{id}', [MigrationController::class, 'destroy']);

    //STUDENTS
    Route::get('estudiante', [StudentController::class, 'index']);
    Route::get('estudianteAll', [StudentController::class, 'all']);
    Route::post('importExcel', [StudentController::class, 'importExcel']);

    Route::get('estudiante/{id}', [StudentController::class, 'show']);
    Route::post('estudiante', [StudentController::class, 'store']);
    Route::put('estudiante/{id}', [StudentController::class, 'update']);
    Route::delete('estudiante/{id}', [StudentController::class, 'destroy']);

    //MIGRATION
    Route::get('compromiso', [CompromisoController::class, 'index']);
    Route::get('compromisoAll', [CompromisoController::class, 'all']);
    Route::get('compromisoAllId', [CompromisoController::class, 'allId']);
    Route::post('actualizarCarrito', [CompromisoController::class, 'actualizarCarrito']);
    Route::put('stateSendAll/{state}', [CompromisoController::class, 'stateSendAll']);
    Route::get('stateSend/{id}', [CompromisoController::class, 'stateSend']);

    Route::get('compromiso/{id}', [CompromisoController::class, 'show']);
    Route::post('compromiso', [CompromisoController::class, 'store']);
    Route::put('compromiso/{id}', [CompromisoController::class, 'update']);
    Route::delete('compromiso/{id}', [CompromisoController::class, 'destroy']);
    Route::post('importExcelCominments', [CompromisoController::class, 'importExcelCominments']);

    //MIGRATION
    Route::get('mensajeria', [WhatsappSendController::class, 'index']);
    Route::get('mensajeriaAll', [WhatsappSendController::class, 'all']);

    Route::get('mensajeria/{id}', [WhatsappSendController::class, 'show']);
    Route::post('mensajeria', [WhatsappSendController::class, 'store']);
    Route::put('mensajeria/{id}', [WhatsappSendController::class, 'update']);
    Route::delete('mensajeria/{id}', [WhatsappSendController::class, 'destroy']);

    //MIGRATION
    Route::get('message', [MessageController::class, 'index']);
    Route::get('message/showExample', [MessageController::class, 'showExample']);
    Route::get('message/{id}', [MessageController::class, 'show']);
    Route::post('message', [MessageController::class, 'store']);
    Route::put('message/{id}', [MessageController::class, 'update']);
    Route::delete('message/{id}', [MessageController::class, 'destroy']);

    Route::get('pdfExport', [WhatsappSendController::class, 'pdfExport'])->name('pdf.export');
    Route::get('excelExport', [WhatsappSendController::class, 'excelExport'])->name('excel.export');

    //DASHBOARD
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('dataDashboard', [DashboardController::class, 'dataDashboard']);
});

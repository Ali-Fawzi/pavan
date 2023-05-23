<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\BalanceController;

    Route::get('/users',[UserController::class,'index']);
    //Patients routes
    Route::get('patients', [PatientsController::class, 'getAll']);
    Route::get('patients/doctors', [PatientsController::class, 'getPatientsDoctor']);
    Route::put('patients', [PatientsController::class, 'create']);
    Route::get('patients/{id}', [PatientsController::class, 'getById']);
    Route::post('patients/{id}', [PatientsController::class, 'update']);
    Route::delete('patients/{id}', [PatientsController::class, 'delete']);

    //Doctor routes
    Route::get('doctors', [DoctorController::class, 'getAll']);
    Route::put('doctors', [DoctorController::class, 'create']);
    Route::get('doctors/{id}', [DoctorController::class, 'getById']);
    Route::post('doctors/{id}', [DoctorController::class, 'update']);
    Route::delete('doctors/{id}', [DoctorController::class, 'delete']);

    //Expense routes
    Route::get('expenses', [ExpenseController::class, 'getAll']);
    Route::put('expenses', [ExpenseController::class, 'create']);
    Route::get('expenses/{id}', [ExpenseController::class, 'getById']);
    Route::post('expenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('expenses/{id}', [ExpenseController::class, 'delete']);

    //Balance routes
    Route::get('balance', [BalanceController::class, 'getAll']);




Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

    Route::get('/user',[UserController::class, 'getLoggedInUserProfile']);

    Route::group(['middleware' => 'role:admin'], function () {
        // Route logic for admin-only route
        Route::get('/admin', function () {
            return response()->json(['message' => 'Admin route']);
        });

        // Add a new route for admin-only
        Route::get('/admin/new-route', function () {
            return response()->json(['message' => 'New admin-only route']);
        });
    });

    Route::group(['middleware' => 'role:doctor'], function () {
        // Route logic for editor-only route
        Route::get('/doctor', function () {
            return response()->json(['message' => 'Doctor route']);
        });

        // Add a new route for editor-only
        Route::get('/doctor/new-route', function () {
            return response()->json(['message' => 'New doctor-only route']);
        });
    });

    Route::group(['middleware' => 'role:secretary'], function () {
        // Route logic for editor-only route
        Route::get('/secretary', function () {
            return response()->json(['message' => 'secretary route']);
        });

        // Add a new route for editor-only
        Route::get('/secretary/new-route', function () {
            return response()->json(['message' => 'New secretary-only route']);
        });
    });



    // Forget Password Routes
    Route::post('/forgetpassword',[ForgetController::class, 'ForgetPassword']);
    // Reset Password Routes
    Route::post('/resetpassword',[ResetController::class, 'ResetPassword']);


    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/logineditor', [AuthController::class, 'logineditor']);
    Route::get('/get-admin-accounts', [AuthController::class, 'getAdminAccounts']);
    Route::get('/get-doctor-accounts', [AuthController::class, 'getDoctorAccounts']);
    Route::get('/get-secretary-accounts', [AuthController::class, 'getSecretaryAccounts']);






});

// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {

//     Route::post('/register', [AuthController::class, 'Register']);
//     Route::post('/login', [AuthController::class, 'login']);

    // Route::post('/logout', [AuthController::class, 'logout']);

    //
// });

<?php

use App\Http\Controllers\API\AsyncActionsController;
use App\Http\Controllers\API\HealthController;
use App\Http\Controllers\API\PatientsController;
use App\Http\Middleware\ApiBasicAuth;
use App\Http\Middleware\CheckPatientAccess;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', [HealthController::class, 'getHealth'])->middleware(ApiBasicAuth::class);
    Route::get('/patients', [PatientsController::class, 'getPatients'])->middleware(ApiBasicAuth::class);
    Route::post('/patients/{patient}/document', [PatientsController::class, 'postDocument'])->middleware([ApiBasicAuth::class, CheckPatientAccess::class]);
    Route::get('/async-action-status/{asyncAction}', [AsyncActionsController::class, 'getCheckStatus'])->name('async-action-status')->middleware([ApiBasicAuth::class]);
});




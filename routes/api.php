<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\TestDriveController;
use Illuminate\Support\Facades\Route;

Route::post('mobil/{id}/simulasi-kredit', [MobilController::class, 'simulasiKredit']);
Route::apiResource('mobil', MobilController::class);

Route::get('test-drives', [TestDriveController::class, 'index']);
Route::post('test-drives', [TestDriveController::class, 'store']);
Route::get('test-drives/{id}', [TestDriveController::class, 'show']);
Route::patch('test-drives/{id}/status', [TestDriveController::class, 'updateStatus']);
Route::delete('test-drives/{id}', [TestDriveController::class, 'destroy']);

Route::get('dashboard/summary', [DashboardController::class, 'summary']);

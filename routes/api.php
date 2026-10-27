<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\TestDriveController;
use Illuminate\Support\Facades\Route;

// 1. Endpoint Utama: Mobil (CRUD Lengkap)
Route::post('mobil/{id}/simulasi-kredit', [MobilController::class, 'simulasiKredit']);
Route::apiResource('mobil', MobilController::class);

// 2. Endpoint Nilai Tambah: Booking Test Drive
Route::get('test-drives', [TestDriveController::class, 'index']);
Route::post('test-drives', [TestDriveController::class, 'store']);
Route::get('test-drives/{id}', [TestDriveController::class, 'show']);
Route::patch('test-drives/{id}/status', [TestDriveController::class, 'updateStatus']);
Route::delete('test-drives/{id}', [TestDriveController::class, 'destroy']);

// 3. Endpoint Nilai Tambah: Dashboard Agregasi
Route::get('dashboard/summary', [DashboardController::class, 'summary']);

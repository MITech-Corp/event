<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeImportController;
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

Route::get('/', function () {
    return redirect()->route('attendance.index');
});

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Public attendance page (no login required)
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::post('/attendance/checkin', [AttendanceController::class, 'checkin'])->name('attendance.checkin');
Route::get('/attendance/employee', [AttendanceController::class, 'findEmployee'])->name('attendance.find');

// Admin pages (login required)
Route::middleware('auth')->group(function () {
    Route::get('/employees', [EmployeeImportController::class, 'index'])->name('employees.index');
    Route::get('/attendances', [AttendanceController::class, 'adminIndex'])->name('attendances.index');
});

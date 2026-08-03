<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');

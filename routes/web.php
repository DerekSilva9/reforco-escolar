<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('turmas', TeamController::class)
        ->parameters(['turmas' => 'team'])
        ->names('turmas')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update']);

    Route::resource('alunos', StudentController::class)
        ->parameters(['alunos' => 'student'])
        ->names('alunos')
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);

    Route::post('/alunos/{student}/observacoes', [StudentController::class, 'updateNotes'])->name('alunos.notes');

    Route::get('/presenca', [AttendanceController::class, 'index'])->name('presenca.index');
    Route::post('/presenca', [AttendanceController::class, 'save'])->name('presenca.store');

    Route::get('/financeiro', [FinanceController::class, 'index'])->name('financeiro.index');
    Route::post('/financeiro/{student}/pagar', [FinanceController::class, 'pay'])->name('financeiro.pay');

    Route::get('/admin/usuarios', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/usuarios/novo', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/usuarios', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::delete('/admin/usuarios/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

    // Compat (pode remover depois)
    Route::get('/teams/{team}/attendance', [AttendanceController::class, 'create'])->name('teams.attendance.create');
    Route::post('/teams/{team}/attendance', [AttendanceController::class, 'store'])->name('teams.attendance.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

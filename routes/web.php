<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PWA Offline Page
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

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

    Route::get('/alunos/exportar/excel', [StudentController::class, 'export'])
        ->name('alunos.export')
        ->middleware('throttle:5,60'); // 5 exports por hora

    Route::post('/alunos/{student}/observacoes', [StudentController::class, 'updateNotes'])->name('alunos.notes');

    Route::get('/presenca', [AttendanceController::class, 'index'])->name('presenca.index');
    Route::post('/presenca', [AttendanceController::class, 'save'])->name('presenca.store');

    Route::get('/financeiro', [FinanceController::class, 'index'])->name('financeiro.index');
    Route::post('/financeiro/{student}/pagar', [FinanceController::class, 'pay'])->name('financeiro.pay');
    Route::get('/financeiro/executivo', [FinanceController::class, 'executive'])->name('financeiro.executive');

    Route::get('/admin/usuarios', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/usuarios/novo', [UserManagementController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/usuarios', [UserManagementController::class, 'store'])->name('admin.users.store');
    Route::delete('/admin/usuarios/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/recados', [NoticeController::class, 'index'])->name('admin.notices.index');
    Route::post('/admin/recados', [NoticeController::class, 'store'])->name('admin.notices.store');
    Route::get('/admin/recados/{notice}/editar', [NoticeController::class, 'edit'])->name('admin.notices.edit');
    Route::patch('/admin/recados/{notice}', [NoticeController::class, 'update'])->name('admin.notices.update');
    Route::delete('/admin/recados/{notice}', [NoticeController::class, 'destroy'])->name('admin.notices.destroy');

    // Compat (pode remover depois)
    Route::get('/teams/{team}/attendance', [AttendanceController::class, 'create'])->name('teams.attendance.create');
    Route::post('/teams/{team}/attendance', [AttendanceController::class, 'store'])->name('teams.attendance.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

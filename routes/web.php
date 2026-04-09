<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\SupervisorController;

Route::get('/', fn() => view('welcome'))->name('welcome');
 
// Auth for guests
Route::middleware('guest')->group(function () {
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login']);
Route::get('/register',[AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class,'register']);

});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
 
Route::middleware('auth')->group(function () {
 
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
    // Placements
    Route::get('/placements',             [PlacementController::class, 'index'])->name('placements.index');
    Route::get('/placements/create',      [PlacementController::class, 'create'])->name('placements.create')
        ->middleware('role:student');
    Route::post('/placements',            [PlacementController::class, 'store'])->name('placements.store')
        ->middleware('role:student');
    Route::get('/placements/{placement}', [PlacementController::class, 'show'])->name('placements.show');
    Route::post('/placements/{placement}/validate', [PlacementController::class, 'validate'])->name('placements.validate')
        ->middleware('role:coordinator');
 
    // Reports
    Route::get('/reports',               [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create',        [ReportController::class, 'create'])->name('reports.create')
        ->middleware('role:student');
    Route::post('/reports',              [ReportController::class, 'store'])->name('reports.store')
        ->middleware('role:student');
    Route::get('/reports/{report}',      [ReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{report}/submit',  [ReportController::class, 'submit'])->name('reports.submit')
        ->middleware('role:student');
    Route::post('/reports/{report}/review', [ReportController::class, 'review'])->name('reports.review')
        ->middleware('role:academic_supervisor,coordinator');
    Route::post('/reports/{report}/comment',[ReportController::class, 'comment'])->name('reports.comment');
 
    // Tasks
    Route::get('/tasks',                [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create',         [TaskController::class, 'create'])->name('tasks.create')
        ->middleware('role:professional_supervisor');
    Route::post('/tasks',               [TaskController::class, 'store'])->name('tasks.store')
        ->middleware('role:professional_supervisor');
    Route::get('/tasks/{task}',         [TaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status')
        ->middleware('role:student');
    Route::post('/tasks/{task}/evaluate',[TaskController::class, 'evaluate'])->name('tasks.evaluate')
        ->middleware('role:professional_supervisor');
 
    // Evaluations: students blocked from seeing the evaluation
    Route::middleware('role:academic_supervisor,coordinator')->group(function () {
        Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
        Route::get('/evaluations/create/{placement}', [EvaluationController::class, 'create'])->name('evaluations.create');
        Route::post('/evaluations/{placement}', [EvaluationController::class, 'store'])->name('evaluations.store');
    });
 
    // Certificates
    Route::get('/certificates',        [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create')
        ->middleware('role:professional_supervisor');
    Route::post('/certificates',       [CertificateController::class, 'store'])->name('certificates.store')
        ->middleware('role:professional_supervisor');
 
    // Supervisors (coordinator only)
    Route::get('/supervisors',         [SupervisorController::class, 'index'])->name('supervisors.index')
        ->middleware('role:coordinator');
    Route::get('/supervisors/create',  [SupervisorController::class, 'create'])->name('supervisors.create')
        ->middleware('role:coordinator');
    Route::post('/supervisors',        [SupervisorController::class, 'store'])->name('supervisors.store')
        ->middleware('role:coordinator');
    Route::get('/supervisors/assign',  [SupervisorController::class, 'assignForm'])->name('supervisors.assign')
        ->middleware('role:coordinator');
    Route::post('/supervisors/assign', [SupervisorController::class, 'assign'])->name('supervisors.assign.store')
        ->middleware('role:coordinator');
});

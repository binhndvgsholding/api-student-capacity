<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoundController;
use App\Http\Controllers\Admin\ContestController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('rounds')->group(function () {
    Route::get('form-add', [RoundController::class, 'create'])->name('admin.round.create');
    Route::post('form-add-save', [RoundController::class, 'store'])->name('admin.round.store');
    Route::get('', [RoundController::class, 'index'])->name('admin.round.list');
    Route::get('{id}/edit', [RoundController::class, 'edit'])->name('admin.round.edit');
    Route::put('{id}', [RoundController::class, 'update'])->name('admin.round.update');
    Route::delete('{id}', [RoundController::class, 'destroy'])->name('admin.round.destroy');
});
Route::prefix('contests')->group(function () {
});

Route::prefix('teams')->group(function () {
    //list
    Route::get('', [TeamController::class, 'ListTeam'])->name('admin.teams'); // Api list Danh sách teams theo cuộc thi. phía view
    Route::get('api-teams', [TeamController::class, 'ApiContestteams'])->name('admin.contest.team');
    // end lisst
    Route::delete('{id}', [TeamController::class, 'deleteTeam'])->name('admin.delete.teams'); // Api xóa teams phía view
    Route::get('form-add', [TeamController::class, 'create'])->name('admin.teams.create');
    Route::post('form-add-save', [TeamController::class, 'store'])->name('admin.teams.store');
    Route::get('form-edit/{id}', [TeamController::class, 'edit'])->name('admin.teams.edit');
    Route::put('form-edit-save/{id}', [TeamController::class, 'update'])->name('admin.teams.update');
});
Route::prefix('users')->group(function () {
    Route::post('team-user-search', [UserController::class, 'TeamUserSearch'])->name('admin.user.TeamUserSearch');
});


Route::prefix('contests')->group(function () {
    Route::get('{id}/edit', [ContestController::class, 'edit'])->name('admin.contest.edit');
    Route::put('{id}', [ContestController::class, 'update'])->name('admin.contest.update');

    Route::get('', [ContestController::class, 'index'])->name('admin.contest.list');
    Route::get('form-add', [ContestController::class, 'create'])->name('admin.contest.create');
    Route::post('form-add-save', [ContestController::class, 'store'])->name('admin.contest.store');

    Route::post('un-status/{id}', [ContestController::class, 'un_status'])->name('admin.contest.un.status');
    Route::post('re-status/{id}', [ContestController::class, 're_status'])->name('admin.contest.re.status');

    Route::delete('{id}', [ContestController::class, 'destroy'])->name('admin.contest.destroy');
});
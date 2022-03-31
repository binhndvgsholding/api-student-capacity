<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Majorcontroller;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\Majorcontroller;
use App\Http\Controllers\Admin\RoundController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\TeamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('sponsors', [SponsorController::class, 'list']);

Route::get('contests', [ContestController::class, 'apiIndex']);

Route::get('majors', [Majorcontroller::class, 'listMajor']); // Chuyên Ngành

Route::get('users', [UserController::class, 'index']); // danh sách user

Route::get('company', [CompanyController::class, 'listCompany']); // Doanh nghiệp


// Route::prefix('rounds')->group(function () {
//     Route::get('', [RoundController::class, 'apiIndex'])->name('round.admin.index');
//     Route::put('{id}', [RoundController::class, 'update'])->name('round.update');
//     Route::delete('{id}', [RoundController::class, 'destroy'])->name('round.delete');
// });

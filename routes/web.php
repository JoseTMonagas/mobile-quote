<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GenerateQuote;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::resource("device", DeviceController::class)
        ->except(["show"]);

    Route::get('/devices/list', [DeviceController::class, 'list'])
        ->name("devices.list");

    Route::get(
        "device/{device}/issues",
        [DeviceController::class, "issuesTable"]
    )->name("device.issuesTable");

    Route::post(
        "device/{device}/issues",
        [DeviceController::class, "issues"]
    )->name("device.issues");


    Route::resource("issues", IssueController::class)->except(["show"]);

    Route::get('/issues/list', [IssueController::class, 'list'])
        ->name("issues.list");

    Route::resource("stores", StoreController::class)->except(["show"]);

    Route::resource("users", UserController::class)->except(["show"]);

    Route::get(
        "users/{user}/role",
        [UserController::class, "changeRole"]
    )->name('users.changeRole');

    Route::post(
        "users/{user}/role",
        [UserController::class, "updateRole"]
    )->name('users.updateRole');

    Route::get(
        "reports",
        [ReportController::class, "show"]
    )->name("reports.show");


    Route::post('/quote', GenerateQuote::class)->name('quote.generate');

    Route::post('reports', [ReportController::class, 'generate'])
        ->name('reports.generate');
});

Route::inertia("/landing", "GenerateQuote");

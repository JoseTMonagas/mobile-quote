<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\QuoteController;
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


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get("/", [QuoteController::class, "create"])
        ->name("quotes.create");
    Route::get("quote/{quote}/receipt", [QuoteController::class, "receipt"])
        ->name("quotes.receipt");
    Route::post("quote", [QuoteController::class, "store"])
        ->name('quotes.store');


    Route::get("dashboard", function () {
        return redirect()->route("quotes.create");
    });

    Route::inertia("dashboard", "Dashboard")->name("dashboard");

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

    Route::get("stores/{store}/users", [StoreController::class, "listUsers"])->name("stores.usersList");
    Route::post("stores/{store}/users", [StoreController::class, "users"])->name("stores.users");
    Route::put("stores/{store}/receipt", [StoreController::class, "storeReceiptSettings"])->name("stores.storeReceiptSettings");

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



    Route::post('reports', [ReportController::class, 'generate'])
        ->name('reports.generate');
});

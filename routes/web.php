<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Dashboard;

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
    Route::get("dashboard", Dashboard::class)->name("dashboard");

    Route::get("bulk", [QuoteController::class, "bulkCreate"])->name("bulkQuotes.create");
    Route::get("quote/{quote}/receipt", [QuoteController::class, "receipt"])
        ->name("quotes.receipt");

    Route::post("quote", [QuoteController::class, "store"])
        ->name('quotes.store');

    Route::delete("quotes/{quote}", [QuoteController::class, "destroy"])->name("quotes.destroy");


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

    Route::post(
        "device/{device}/prices",
        [DeviceController::class, "customPrice"]
    )->name("device.custom_price");


    Route::resource("issues", IssueController::class)->except(["show"]);

    Route::get('/issues/list', [IssueController::class, 'list'])
        ->name("issues.list");

    Route::resource("stores", StoreController::class)->except(["show"]);

    Route::get("stores/{store}/users", [StoreController::class, "listUsers"])->name("stores.usersList");
    Route::post("stores/{store}/users", [StoreController::class, "users"])->name("stores.users");
    Route::put("stores/{store}/receipt", [StoreController::class, "storeReceiptSettings"])->name("stores.storeReceiptSettings");
    Route::put("stores/{store}/cut", [StoreController::class, "updateStorePercent"])->name("stores.updateStorePercent");

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

    Route::get("user/locations", [LocationController::class, "userLocations"])->name("locations.list");
    Route::resource("stores.locations", LocationController::class)->shallow();
    Route::get("locations/{location}/users", [LocationController::class, "listUsers"])->name("locations.usersList");
    Route::post("locations/{location}/users", [LocationController::class, "users"])->name("locations.users");
});

Route::group(["prefix" => "inventory", "name" => "inventory."], function () {
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::delete("items/obliterate", [ItemController::class, "obliterate"])->name("items.obliterate");
        Route::put("items/hold", [ItemController::class, "hold"])->name("items.hold");
        Route::get("items/hold", [ItemController::class, "viewHold"])->name("items.viewHold");
        Route::put("items/unhold", [ItemController::class, "unhold"])->name("items.unhold");
        Route::put("items/return", [ItemController::class, "returnItem"])->name("items.return");
        Route::post("items/correct", [ItemController::class, "correct"])->name("items.correct");
        Route::post("items/update", [ItemController::class, "update"])->name("items.update");
        Route::get("items/{item}/label", [ItemController::class, "label"])->name("items.label");
        Route::resource("items", ItemController::class)
            ->except(["show", "update"]);

        Route::post("sales", [SaleController::class, "store"])->name("sales.store");
        Route::get("sale/{sale}/receipt", [SaleController::class, "receipt"])->name("sales.receipt");

        Route::get("report", [SaleController::class, "showReport"])->name("sales.report");
        Route::post("report", [SaleController::class, "generateReport"])->name("sales.generate_report");
    });

    Route::get("public", [ItemController::class, "public"])->name("items.public");
});

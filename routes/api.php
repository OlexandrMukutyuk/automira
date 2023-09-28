<?php


use App\Http\Controllers\Api\In\CounterpartiesController;
use App\Http\Controllers\Api\In\FiltersController;
use App\Http\Controllers\Api\In\OrderListController;
use App\Http\Controllers\Api\In\UpdateOrdersController;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ShelfRequest;
use App\Http\Requests\ShelfsRequest;
use Illuminate\Support\Facades\Route;


Route::get('storages', fn() => get_automira('/getStorages'));
Route::get('vendors', fn() => get_automira('/getVendors'));
Route::get('responsibles', fn() => get_automira('/getResponsibles'));

Route::post('product', function (ProductRequest $request) {
    $data = $request->validated();

    return post_automira('/getProduct', [
        'code' => $data['code']
    ]);
});
Route::post('shelfs', function (ShelfsRequest $request) {
    $data = $request->validated();
    return post_automira('/getShelfs', [
        'code' => $data['id']
    ]);
});
Route::post('shelf', function (ShelfRequest $request) {
    $data = $request->validated();

    return post_automira('/getShelf', [
        'code' => $data['code']
    ]);
});


Route::prefix('in')
    ->group(function () {
        Route::get('filters', FiltersController::class);
        Route::get('counterparties', CounterpartiesController::class);

        Route::prefix('orders')
            ->group(function () {
                Route::post('/', OrderListController::class);
//                Route::post('new', 'newOrder');
                Route::post('updated', UpdateOrdersController::class);

            });
    });


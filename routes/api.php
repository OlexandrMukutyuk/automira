<?php


use App\Http\Controllers\Api\InController;
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
    now()->toDateTimeLocalString();
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
    ->controller(InController::class)
    ->group(function () {
        Route::get('filters', 'filters');
        Route::get('counterparties', 'counterparties');

        Route::prefix('orders')
            ->group(function () {
                Route::post('new', 'newOrder');
            });
        Route::post('orders', 'orders');
        Route::post('update-orders', 'updateOrders');
    });


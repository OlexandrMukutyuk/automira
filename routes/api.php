<?php


use App\Http\Controllers\Api\InController;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ShelfRequest;
use App\Http\Requests\ShelfsRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


Route::get('storages', function () {
    return Http::automira()->get('/getStorages')->json();
});

Route::get('vendors', function () {
    return Http::automira()->get('/getVendors')->json();
});

Route::get('responsibles', function () {
    return Http::automira()->get('/getResponsibles')->json();
});

Route::post('product', function (ProductRequest $request) {
    $data = $request->validated();

    return Http::automira()->post('/getProduct', [
        'code' => $data['code']
    ])->json();
});


Route::post('shelfs', function (ShelfsRequest $request) {
    $data = $request->validated();

    return Http::automira()->post('/getShelfs', [
        'id' => $data['id']
    ])->json();
});


Route::post('shelf', function (ShelfRequest $request) {
    $data = $request->validated();

    return Http::automira()->post('/getShelf', [
        'code' => $data['code']
    ])->json();
});


Route::prefix('in')
    ->controller(InController::class)
    ->group(function () {
        Route::get('filters', 'filters');

        Route::get('counterparties', 'counterparties');

        Route::post('orders', 'orders');

        Route::post('update-orders', 'updateOrders');
    });


//Route::get('/getOutOrders', function (Request $request) {
//    $status = $request->status ?? null;
//    $storage = $request->storage ?? null;
//    $responsible = $request->responsible ?? null;
//    $manager = $request->manager ?? null;
//
//
////   $response = \Illuminate\Support\Facades\Cache::remember('getOutOrders', 600, function () {
//    $response = Http::withBasicAuth('AppAndroid', 'VF888e8nhU7LyiP')
//        ->get(BASE_URL.'/getOutOrders')
//        ->json();
////   });
//
//    $collection = collect($response);
//
//    $filtered = $collection->filter(function ($order) use ($status, $storage, $responsible, $manager) {
//        if (!!$status && $order['status'] != $status) {
//            return false;
//        }
//        if (!!$status && $order['storage'] != $storage) {
//            return false;
//        }
//        if (!!$status && $order['responsible'] !== $responsible) {
//            return false;
//        }
//        if (!!$status && $order['manager'] !== $manager) {
//            return false;
//        }
//
//        return true;
//    })->values();
//
//    return $filtered;
//});

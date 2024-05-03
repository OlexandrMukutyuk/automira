<?php


use App\Http\Controllers\Api\In\CounterpartiesController;
use App\Http\Controllers\Api\In\EditOrderController;
use App\Http\Controllers\Api\In\FiltersController;
use App\Http\Controllers\Api\In\NewOrderController;
use App\Http\Controllers\Api\In\OrderDetailedInfoController;
use App\Http\Controllers\Api\In\OrderListController;
use App\Http\Controllers\Api\In\ProveOrderController;
use App\Http\Controllers\Api\In\UpdateOrdersController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\OutFiltersController;
use App\Http\Controllers\Api\StockTaking\StockTakingController;
use App\Http\Controllers\Api\Autorization\AutorizationController;
use App\Http\Requests\ShelfRequest;
use App\Http\Requests\ShelfsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('storages', fn() => get_automira('/getStorages'));
Route::get('vendors', fn() => get_automira('/getVendors'));
Route::get('responsibles', fn() => get_automira('/getResponsibles'));


Route::prefix('product')
    ->controller(ProductController::class)
    ->group(function () {
        Route::post('barcode/add', 'bindBarcodeToProduct');
        Route::post('barcode', 'byBarcode');
        Route::post('article', 'byArticle');
    });


Route::post('shelfs', function (ShelfsRequest $request) {
    $data = $request->validated();
    return post_automira('/getShelfs', [
        'id' => $data['id']
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

                Route::post('new', NewOrderController::class);
                Route::put('edit', EditOrderController::class);
                Route::post('prove', ProveOrderController::class);

                Route::get('{uuid}', OrderDetailedInfoController::class);

                Route::post('updated', UpdateOrdersController::class);

            });
    });


Route::prefix('Out')
    ->group(function () {
        Route::get('getOutOrders', fn() => get_automira('/getOutOrders'));
        Route::post('getOutOrder', function (ShelfsRequest $request) {
            $data = $request->validated();
            return post_automira('/getOutOrder', $data);
        });
        Route::post('editOutOrder', function (Request $request) {
            $data = $request->toArray();
            return post_automira('/editOutOrder', $data);
        });
        Route::get('filters', fn(Request $request) => get_automira('/filtersOutOrder'));
          

});


Route::prefix('Get')
    ->group(function () {
        Route::get('getProduct', function (Request $request) {
            $data = $request->toArray();
            return post_automira('/getProduct', $data);
        });
        Route::get('getShelfs', function (Request $request) {
            $data = $request->toArray();
            return post_automira('/getShelfs', $data);
        });
        Route::get('getShelf', function (Request $request) {
            $data = $request->toArray();
            return post_automira('/getShelf', $data);
        });
        Route::get('getStorages', fn(Request $request) => get_automira('/getStorages'));     

});

Route::prefix('Move')
    ->group(function () {
        Route::get('filtersMoveOrder', fn(Request $request) => get_automira('/filtersMoveOrder'));
           

});


Route::post('/stocktaking',[StockTakingController::class, 'stockTaking']);


Route::prefix('autorizaion')
    ->group(function () {
        Route::get('users', fn() => get_automira('/authorization'));
        Route::post('login',function (Request $request) {
            $data = $request->validate([
                'login' => 'required|string',
                'password' => 'required|string',
            ]);
            $Autorization = $data['login'] . ':' . $data['password'];
            $Autorization = base64_encode($Autorization);
            $req = array('Authorization' => $Autorization);;
            return post_automira('/authorization',$req);
        });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

const BASE_URL = 'http://amos.avtomira.com.ua/MargaretGroup/hs/AppExchange';

Route::get('/getOutOrders', function (Request $request) {

    $status = $request->status ?? null;
    $storage = $request->storage ?? null;
    $responsible = $request->responsible ?? null;
    $manager = $request->manager ?? null;


//   $response = \Illuminate\Support\Facades\Cache::remember('getOutOrders', 600, function () {
       $response =  \Illuminate\Support\Facades\Http::withBasicAuth('AppAndroid', 'VF888e8nhU7LyiP')
           ->get(BASE_URL . '/getOutOrders')
           ->json();
//   });

   $collection = collect($response);

   $filtered = $collection->filter(function ($order) use ($status, $storage, $responsible, $manager) {
       if (!!$status && $order['status'] != $status) {
           return false;
       }
       if (!!$status && $order['storage'] != $storage) {
           return false;
       }
       if (!!$status && $order['responsible'] !== $responsible) {
           return false;
       }
       if (!!$status && $order['manager'] !== $manager) {
           return false;
       }

       return true;
   })->values();

   return $filtered;
});

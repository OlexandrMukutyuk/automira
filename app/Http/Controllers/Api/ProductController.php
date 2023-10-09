<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AutomiraException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * @throws AutomiraException
     */
    public function byBarcode(Request $request)
    {
        $data = $request->validate([
            'code' => ['required']
        ]);

        $product = post_automira('/getProduct', $data);

        if ($product[0]['find']) {
            return [[
                ...$product[0],
                'isShelf' => false
            ]];
        }

        $shelf = post_automira('/getShelf', $data);

        if ($shelf[0]['find']) {
            return [...$this->getProductShelf(true, true)];
        }

        return [...$this->getProductShelf(false, false)];
    }


    private function getProductShelf(bool $find, bool $isShelf): array
    {
        return [
            'find' => $find,
            'isShelf' => $isShelf
        ];
    }


    /**
     * @throws AutomiraException
     */
    public function byArticle(Request $request)
    {
        $data = $request->validate([
            'article' => ['required']
        ]);

        return post_automira('/getProductA', $data);
    }


    public function bindBarcodeToProduct(Request $request)
    {

        $data = $request->validate([
            'article' => ['required'],
            'barCode' => ['required']
        ]);


        post_automira('/editProduct', $data);

        return response()->json([[
            'find' => true
        ]]);
    }
}

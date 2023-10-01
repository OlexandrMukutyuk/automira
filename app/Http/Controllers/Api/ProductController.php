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

        return post_automira('/getProduct', $data);
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
}

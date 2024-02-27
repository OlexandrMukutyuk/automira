<?php

namespace App\Http\Controllers\Api\StockTaking;

use App\Exceptions\AutomiraException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StockTaking\StockTakingRequest;
use App\Http\Requests\StockTaking\StockTakingSecondRequest;
class StockTakingController extends Controller
{

    /**
     * @throws AutomiraException
     */

    public function stockTaking(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'action' => 'required',
        ]);

        if ($request->action == 'start'){
            $data = $request->validate((new StockTakingRequest())->rules());
            return post_automira('/stocktaking', $data);
        }
        if ($request->action == 'end'){
            $data = $request->validate((new StockTakingSecondRequest())->rules());
            
            return post_automira('/stocktaking', $data);
        }

        return response()->json(['error' => 'Неправильний action']);


    }

}

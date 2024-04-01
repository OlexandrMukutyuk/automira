<?php

namespace App\Http\Controllers\Api\Autorization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AutorizationController extends Controller
{
    
    public function login(Request $request){
        $data = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
        $Autorization = $data['login'] . ':' . $data['password'];
        $Autorization = base64_encode($Autorization);
        $req = array('Authorization' => $Autorization);;
        return post_automira('/authorization',$req);
    }

}

<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CallController extends Controller
{
    public function call(Request $request)
    {
        $phoneNumber = $request->input('phone_number');
        $response = [
            'phone_number' => $phoneNumber,
            'message' => 'Calling '.$phoneNumber
        ];
        return response()->json($response);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public static function responseSuccess($data = [])
    {
        return response()->json([
            'status' => 'true',
            'message' => '',
            'data' => $data
        ]);
    }
    public static function responseError($message = 'حدث خطاء ما')
    {
        return response()->json([
            'status' => 'false',
            'message' => $message,
        ], 400);
    }
}

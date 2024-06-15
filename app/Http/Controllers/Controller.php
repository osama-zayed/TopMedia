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
    public static function responseError($message = 'حدث خطاء ما', $statusCode = 400)
    {
        return response()->json([
            'status' => 'false',
            'message' => $message,
        ], $statusCode);
    }
    public static function formatPaginatedResponse($paginatedData, $formattedData)
    {
        return [
            'current_page' => $paginatedData->currentPage(),
            'data' => $formattedData,
            'first_page_url' => $paginatedData->url(1),
            'from' => $paginatedData->firstItem(),
            'last_page' => $paginatedData->lastPage(),
            'last_page_url' => $paginatedData->url($paginatedData->lastPage()),
            'next_page_url' => $paginatedData->nextPageUrl(),
            'path' => $paginatedData->path(),
            'per_page' => $paginatedData->perPage(),
            'prev_page_url' => $paginatedData->previousPageUrl(),
            'to' => $paginatedData->lastItem(),
            'total' => $paginatedData->total(),
        ];
    }
}

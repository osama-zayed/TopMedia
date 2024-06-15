<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    public static function showAll()
    {
        try {
            $Banner = Banner::select('id', 'image')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
            $BannerData = $Banner->map(function ($Banner) {
                return [
                    'id' => $Banner->id,
                    'image' => url('storage', $Banner->image),
                ];
            });
            return self::responseSuccess($BannerData);
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
   
}

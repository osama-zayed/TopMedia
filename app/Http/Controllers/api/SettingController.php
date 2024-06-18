<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public static function showAll()
    {
        $settings = Setting::pluck('value', 'key');
        return self::responseSuccess($settings);
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public static function showAll()
    {
        try {
            $Category = Category::select('id', 'categorie_name', 'image')->get();
            $CategoryData = $Category->map(function ($Category) {
                return [
                    'id' => $Category->id,
                    'name' => $Category->categorie_name,
                    'image' => url('storage', $Category->image),
                ];
            });
            return self::responseSuccess($CategoryData);
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
   
}

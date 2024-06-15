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
                    'image' => str_starts_with($Category->image, 'Category/') ? url('storage', $Category->image) : $Category->image,
                ];
            });
            return self::responseSuccess($CategoryData);
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
   
}

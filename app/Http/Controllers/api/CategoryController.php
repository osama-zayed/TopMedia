<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    // public function __construct(){
    //     $this->middleware("auth:sanctum");
    //     $this->middleware("Permission:view products");
    // }
    public static function showAll()
    {
        try {
            $Category = Category::select('id', 'category_name', 'image')->get();
            $CategoryData = $Category->map(function ($Category) {
                return [
                    'id' => $Category->id,
                    'name' => $Category->category_name,
                    'image' => str_starts_with($Category->image, 'Category/') ? url('storage', $Category->image) : $Category->image,
                ];
            });
            return self::responseSuccess($CategoryData);
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
   
}

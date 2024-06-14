<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public static function showAll()
    {
        try {
            $Product = Product::select(
                'id',
                'product_name',
                'product_description',
                'categorie_id',
                'product_status',
                'product_price',
                'image',
                'discount_percentage',
            )->get();
            $ProductData = $Product->map(function ($Product) {
                return [
                    'id' => $Product->id,
                    'name' => $Product->product_name,
                    'image' => url('storage/', $Product->image[0]),
                ];
            });
            return self::responseSuccess($ProductData);
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
}

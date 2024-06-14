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
                'product_price',
                'image',
                'discount_percentage',
            )->where('product_status', 1)->get();
            $ProductData = $Product->map(function ($Product) {
                return [
                    'id' => $Product->id,
                    'name' => $Product->product_name,
                    'categorie_name' => $Product->Category->categorie_name,
                    'product_description' => $Product->product_description,
                    'old_product_price' => $Product->product_price,
                    'discount_percentage' => $Product->discount_percentage,
                    'new_product_price' => $Product->product_price - ($Product->product_price * ($Product->discount_percentage / 100)),
                    'image' => url('storage/', $Product->image[0]),
                ];
            });
            return self::responseSuccess($ProductData);
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
}

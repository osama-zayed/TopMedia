<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public static function showAll(Request $request)
    {
        try {
            return self::responseSuccess(self::getProductsPaginated($request));
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
    public static function showById(Request $request)
    {
        try {
            return self::responseSuccess(self::getProductById($request->get('id', null)));
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
    public static function getProductsPaginated($request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');
        $category_id = $request->get('category_id');

        $query = Product::select(
            'id',
            'product_name',
            'product_price',
            'image',
            'discount_percentage'
        )
            ->where('product_status', 1);

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        $products = $query->paginate($perPage, "", "", $page);

        return self::formatPaginatedResponse($products, self::formatProductDataForDisplay($products->items()));
    }
    public static function getProductById($id)
    {

        $product = Product::select(
            'id',
            'product_name',
            'product_description',
            'category_id',
            'product_status',
            'product_price',
            'image',
            'discount_percentage',
        )
            ->where('product_status', 1)
            ->where('id', $id)
            ->first();

        return self::formatProductData($product);
    }
    private static function formatProductData($product)
    {
        return [
            'id' => $product->id,
            'product_name' => $product->product_name,
            'categorie_name' => $product->Category->categorie_name,
            'product_description' => $product->product_description,
            'old_product_price' => number_format($product->product_price, 2),
            'discount_percentage' => $product->discount_percentage,
            'new_product_price' => number_format($product->product_price - ($product->product_price * ($product->discount_percentage / 100)), 2),
            'image' => is_array($product->image) ? array_map(function ($image) {
                return url('storage', $image);
            }, $product->image) : [url('storage', $product->image)],
        ];
    }
    private static function formatProductDataForDisplay($products)
    {
        return array_map(function ($product) {
            $images = is_array($product->image) ? $product->image : [$product->image];
            // $firstImage = url('storage/', $images[0]);
            // $firstImage = $images[0];
            $firstImage = str_starts_with($images[0], 'Product/') ? url('storage', $images[0]) : $images[0];
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_price' => number_format($product->product_price, 2),
                'image' => $firstImage,
                'discount_percentage' => $product->discount_percentage,
                'new_product_price' => number_format($product->product_price - ($product->product_price * ($product->discount_percentage / 100)), 2),
            ];
        }, $products);
    }
}
